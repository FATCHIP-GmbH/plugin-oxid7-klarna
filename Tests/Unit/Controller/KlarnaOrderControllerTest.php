<?php

namespace TopConcepts\Klarna\Tests\Unit\Controller;


use OxidEsales\Eshop\Application\Controller\OrderController;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Controller\BaseController;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DisplayError;
use OxidEsales\Eshop\Core\Exception\ExceptionToDisplay;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Utils;
use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge\ModuleActivationBridgeInterface;
use OxidEsales\PayPalModule\Controller\ExpressCheckoutDispatcher;
use OxidEsales\PayPalModule\Controller\StandardDispatcher;
use TopConcepts\Klarna\Controller\KlarnaOrderController;
use TopConcepts\Klarna\Core\Exception\KlarnaWrongCredentialsException;
use TopConcepts\Klarna\Core\KlarnaCheckoutClient;
use TopConcepts\Klarna\Core\KlarnaConsts;
use TopConcepts\Klarna\Core\KlarnaOrder;
use TopConcepts\Klarna\Core\KlarnaOrderManagementClient;
use TopConcepts\Klarna\Core\KlarnaPayment;
use TopConcepts\Klarna\Core\Exception\KlarnaClientException;
use TopConcepts\Klarna\Model\KlarnaBasket;
use TopConcepts\Klarna\Model\KlarnaUser;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;
use OxidEsales\Eshop\Core\UtilsObject;

class KlarnaOrderControllerTest extends ModuleUnitTestCase
{
    const COUNTRIES = [
        'AT' => 'a7c40f6320aeb2ec2.72885259',
        'DE' => 'a7c40f631fc920687.20179984',
        'AF' => '8f241f11095306451.36998225',
    ];

    public function testKlarnaExternalPaymentError()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->expects($this->once())->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['init'])->getMock();
        $stut->klarnaExternalPayment();
        $result = unserialize($this->getSessionParam('Errors')['default'][0]);

        $this->assertInstanceOf(DisplayError::class, $result);
        $result = $result->getOxMessage();
        $this->assertEquals('Ein Fehler ist aufgetreten. Bitte noch einmal versuchen.', $result);
    }

    /**
     * @dataProvider externalPaymentDataProvider
     * @param $paymentId
     * @param $moduleId
     */
    public function testKlarnaExternalPayment($paymentId, $moduleId)
    {
        $payment = $this->getMockBuilder(Payment::class)->setMethods(['load'])->getMock();
        $payment->expects($this->any())->method('load')->willReturn(true);
        $payment->oxpayments__oxactive = new Field(true);
        $oBasket = $this->getMockBuilder(KlarnaBasket::class)->setMethods(['onUpdate'])->getMock();
        $oBasket->expects($this->any())->method('onUpdate')->willReturn(true);
        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['isCreatable', 'save', 'onOrderExecute'])->getMock();
        $user->expects($this->any())->method('save')->willReturn(true);
        $user->expects($this->any())->method('onOrderExecute')->willReturn(true);
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['klarnaExternalCheckout', '_createUser'])->getMock();

        if($paymentId === 'bestitamazon') {
            $user->expects($this->once())->method('isCreatable')->willReturn(true);
            $stut->expects($this->once())->method('_createUser')->willReturn(true);
        }

        UtilsObject::setClassInstance(Payment::class, $payment);
        $this->getSession()->setBasket($oBasket);
        $this->setSessionParam('klarna_checkout_order_id', '1');
        $this->setRequestParameter('payment_id', $paymentId);

        $this->setProtectedClassProperty($stut, 'isExternalCheckout', false);
        $this->setProtectedClassProperty($stut, '_oUser', $user);
        $this->setProtectedClassProperty(
            $stut,
            '_aOrderData',
            ['selected_shipping_option' => ['id' => 'shippingOption']]
        );

        $result = $stut->klarnaExternalPayment();

        if ($paymentId == 'bestitamazon') {
            $this->assertEquals(
                Registry::getConfig()->getShopSecureHomeUrl()."cl=KlarnaEpmDispatcher&fnc=amazonLogin",
                \oxUtilsHelper::$sRedirectUrl
            );
        } elseif ($paymentId == 'oxidpaypal') {
            $this->assertEquals('basket', $result);
        } else {
            $this->assertEquals(null, $result);
        }

        $this->assertEquals($paymentId, $this->getProtectedClassProperty($oBasket, '_sPaymentId'));

    }

    public function externalPaymentDataProvider()
    {
        $amazonModule = $this->getIsModuleActive('bestitamazonpay4oxid');
        $paypalModule = $this->getIsModuleActive('oepaypal');

        $data = [
            ['test', null]
        ];

        if($amazonModule == true) {
            $data[1] = ['bestitamazon', 'bestitamazonpay4oxid'];
        }

        if($paypalModule == true) {
            $data[2] = ['oxidpaypal', 'oepaypal'];
        }

        return $data;
    }

    protected function getIsModuleActive($moduleid) {
        $container = ContainerFactory::getInstance()->getContainer();

        $moduleActivationBridge = $container->get(ModuleActivationBridgeInterface::class);

        try {
            return $moduleActivationBridge->isActive(
                $moduleid,
                Registry::getConfig()->getShopId()
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    public function testExecute()
    {
        $order = $this->getMockBuilder(Order::class)->setMethods(['finalizeOrder'])->getMock();
        $order->expects($this->once())->method('finalizeOrder')->willReturn(1);
        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['getType', 'onOrderExecute'])->getMock();
        $user->expects($this->any())->method('getType')->willReturn(0);
        $user->expects($this->once())->method('onOrderExecute')->willReturn(true);

        $oBasket = $this->getMockBuilder(KlarnaBasket::class)->setMethods(['getPaymentId', 'calculateBasket'])->getMock();
        $oBasket->expects($this->atLeastOnce())->method('getPaymentId')->willReturn('klarna_checkout');
        $oBasket->expects($this->once())->method('calculateBasket')->willReturn(true);
        $stut = $this->getMockBuilder(OrderController::class)
            ->setMethods(['kcoBeforeExecute', 'getDeliveryAddressMD5', 'klarnaCheckoutSecurityCheck','getSession','_getNextStep'])
            ->getMock();
        $stut->expects($this->once())->method('kcoBeforeExecute')->willReturn(true);
        $stut->expects($this->once())->method('getDeliveryAddressMD5')->willReturn('address');
        $stut->expects($this->once())->method('klarnaCheckoutSecurityCheck')->willReturn(true);

        $this->setProtectedClassProperty($stut, '_oUser', $user);
        $this->setProtectedClassProperty($stut, '_aOrderData', ['merchant_requested' => ['additional_checkbox' => true]]);
        $sGetChallenge = Registry::getSession()->getSessionChallengeToken();
        $this->setRequestParameter('stoken', $sGetChallenge);
        UtilsObject::setClassInstance(Order::class, $order);
        $this->getSession()->setBasket($oBasket);

        $stut->execute();
        $addressResult = $this->getSessionParam('sDelAddrMD5');
        $this->assertEquals('address', $addressResult);
        $paymentId = $this->getSessionParam('paymentid');
        $this->assertEquals('klarna_checkout', $paymentId);

    }

    /**
     * @throws \ReflectionException
     */
    public function test_createUser()
    {
        $orderData = [
            'billing_address' => [
                'street_address' => 'testBilling',
                'email' => 'test@email.io',
            ],
            'shipping_address' => ['street_address' => 'testShipping'],
            'customer' => ['date_of_birth' => 'test'],
        ];
        $user = $this->getMockBuilder(KlarnaUser::class)
            ->setMethods(['createUser', 'load', 'changeUserData', 'getId', 'updateDeliveryAddress'])->getMock();
        $user->expects($this->once())->method('createUser')->willReturn(true);
        $user->expects($this->once())->method('changeUserData')->willReturn(true);
        $user->expects($this->any())->method('getId')->willReturn('id');
        $user->expects($this->once())->method('load')->willReturn(true);
        $user->expects($this->once())->method('updateDeliveryAddress')->willReturn(true);
        $stut = $this->getMockBuilder(KlarnaOrderController::class)
            ->setMethods(['isRegisterNewUserNeeded'])->getMock();
        $stut->expects($this->any())->method('isRegisterNewUserNeeded')->willReturn(true);

        $this->setProtectedClassProperty($stut, '_oUser', $user);
        $this->setProtectedClassProperty($stut, '_aOrderData', $orderData);
        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('createUser');
        $method->setAccessible(true);
        $result = $method->invoke($stut);

        $this->assertEquals(new Field('test@email.io', Field::T_RAW), $user->oxuser__oxusername);
        $this->assertEquals(new Field(1, Field::T_RAW), $user->oxuser__oxactive);
        $this->assertEquals(new Field('test'), $user->oxuser__oxbirthdate);
        $this->assertEquals('id', $this->getSessionParam('usr'));
        $this->assertTrue($this->getSessionParam('blNeedLogout'));

        $this->assertTrue($result);
    }

    /**
     * @dataProvider initUserDataProvider
     * @param $expected
     * @param $isUserLoggedIn
     * @throws \ReflectionException
     */
    public function test_initUser($expected, $isUserLoggedIn)
    {
        $viewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['isUserLoggedIn'])->getMock();
        $viewConfig->expects($this->once())->method('isUserLoggedIn')->willReturn($isUserLoggedIn);
        $user = $this->getMockBuilder(User::class)->setMethods(['getKlarnaData'])->getMock();
        $user->expects($this->any())->method('getKlarnaData')->willReturn(['test']);
        $stut = $this->getMockBuilder(KlarnaOrderController::class)
            ->setMethods(['getUser', 'getViewConfig'])->getMock();
        $stut->expects($this->any())->method('getUser')->willReturn($user);
        $stut->expects($this->once())->method('getViewConfig')->willReturn($viewConfig);

        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('initUser');
        $method->setAccessible(true);
        $method->invoke($stut);

        $this->assertEquals($expected, $stut->getUser()->getType());
    }

    public function initUserDataProvider()
    {
        return [
            [KlarnaUser::LOGGED_IN,  true],
            [null, false]
        ];
    }

    public function testGetDeliveryAddressMD5()
    {
        $userEncodedAddress = 'test';
        $deliveryEncodedAddress = '0be02ac66a490e0183d722ed8e5d128a';
        $oUser = $this->getMockBuilder(User::class)->setMethods(['getEncodedDeliveryAddress'])->getMock();
        $oUser->expects($this->any())->method('getEncodedDeliveryAddress')->willReturn($userEncodedAddress);
        $this->setSessionParam('deladrid', '41b545c65fe99ca2898614e563a7108a');

        $sut = $this->getMockBuilder(KlarnaOrderController::class)
            ->setMethods(['getUser'])->getMock();
        $sut->expects($this->any())->method('getUser')->willReturn($oUser);
        $result = $sut->getDeliveryAddressMD5();
        $this->assertEquals($userEncodedAddress . $deliveryEncodedAddress, $result);

        $this->setSessionParam('deladrid', null);
        $stut = $this->getMockBuilder(KlarnaOrderController::class)
            ->setMethods(['getUser'])->getMock();
        $stut->expects($this->any())->method('getUser')->willReturn($oUser);
        $result = $stut->getDeliveryAddressMD5();
        $this->assertEquals($userEncodedAddress, $result);
    }

    public function testResetKlarnaCheckoutSession()
    {
        $stut = $this->getMockBuilder(KlarnaOrderController::class)
            ->setMethods(['getUser'])->getMock();
        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('resetKlarnaCheckoutSession');
        $method->setAccessible(true);
        $this->setSessionParam('klarna_checkout_order_id', '1');
        $this->assertEquals($this->getSessionParam('klarna_checkout_order_id'), '1');
        $method->invoke($stut);
        $this->assertEquals($this->getSessionParam('klarna_checkout_order_id'), null);
    }
}
