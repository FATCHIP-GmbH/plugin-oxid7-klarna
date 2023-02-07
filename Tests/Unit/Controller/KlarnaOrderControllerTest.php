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
        $stut->expects($this->once())->method('getSession')->willReturn(Registry::getSession());

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

    public function executeFailsDataProvider()
    {
        $sGetChallenge = Registry::getSession()->getSessionChallengeToken();
        $statusComplete = ['status' => 'checkout_complete'];
        $statusIncomplete = ['status' => 'checkout_incomplete'];

        return[
            [$sGetChallenge, 'id', 'id', $statusComplete, 1, 'thankyou'], // success
            [$sGetChallenge, 'id', 'id', $statusIncomplete, 0, 'KlarnaExpress'], // incomplete status
            [$sGetChallenge, 'id', 'newId', $statusComplete, 0, 'KlarnaExpress'], // klarna id mismatch
            [$sGetChallenge, null, null, $statusComplete, 0, 'KlarnaExpress'], // no klarna id
            [null, 'id', 'id', $statusComplete, 0, false] // no/invalid session challenge token
        ];
    }

    /**
     * @dataProvider executeFailsDataProvider
     * @param $sGetChallenge
     * @param $requestId
     * @param $sessionId
     * @param $statusData
     * @param $kcoExecuteCount
     * @param $expectedResult
     */
    public function testExecuteFails($sGetChallenge, $requestId, $sessionId, $statusData, $kcoExecuteCount, $expectedResult)
    {
        $oBasket = $this->getMockBuilder(KlarnaBasket::class)->setMethods(['getPaymentId'])->getMock();
        $oBasket->expects($this->atLeastOnce())->method('getPaymentId')->willReturn('klarna_checkout');
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['kcoBeforeExecute', 'kcoExecute','getSession','_getNextStep'])->getMock();
        $stut->expects($this->exactly($kcoExecuteCount))->method('kcoBeforeExecute');
        $stut->expects($this->exactly($kcoExecuteCount))->method('kcoExecute');
        $stut->expects($this->once())->method('getSession')->willReturn(Registry::getSession());

        $this->getSession()->setBasket($oBasket);
        $this->setRequestParameter('stoken', $sGetChallenge);
        $this->setSessionParam('klarna_checkout_order_id', $sessionId);
        $this->setRequestParameter('klarna_order_id', $requestId);
        $this->setProtectedClassProperty($stut, '_aOrderData', $statusData);

        $result = $stut->execute();
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testKcoExecute()
    {
        $oBasket = $this->getMockBuilder(KlarnaBasket::class)->setMethods(['calculateBasket'])->getMock();
        $oBasket->expects($this->any())->method('calculateBasket')->willReturn(true);
        $order = $this->getMockBuilder(Order::class)->setMethods(['finalizeOrder'])->getMock();
        $order->expects($this->any())->method('finalizeOrder')->willThrowException(new StandardException('test'));
        $stut = $this->getMockBuilder(OrderController::class)->setMethods(['kcoExecute'])->getMock();


        UtilsObject::setClassInstance(Order::class, $order);
        $this->setSessionParam('klarna_checkout_order_id', 1);
        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('kcoExecute');
        $method->setAccessible(true);

        $method->invokeArgs($stut, [$oBasket]);
        $this->assertEquals(null, $this->getSessionParam('klarna_checkout_order_id'));
        $this->assertNull($this->getSessionParam('klarna_checkout_order_id'));
        $result = unserialize($this->getSessionParam('Errors')['default'][0]);
        $this->assertInstanceOf(ExceptionToDisplay::class, $result);
        $result = $result->getOxMessage();
        $this->assertEquals('test', $result);

        $order = $this->getMockBuilder(Order::class)->setMethods(['finalizeOrder'])->getMock();
        $order->expects($this->once())->method('finalizeOrder')->willReturn(1);
        $oUser = $this->getMockBuilder(User::class)->setMethods(['getType', 'save', 'onOrderExecute', 'clearDeliveryAddress'])->getMock();
        $oUser->expects($this->any())->method('getType')->willReturn(KlarnaUser::NOT_REGISTERED);
        $oUser->expects($this->once())->method('save');
        $oUser->expects($this->once())->method('onOrderExecute');
        $oUser->expects($this->once())->method('clearDeliveryAddress');
        $oEmail = $this->getMockBuilder(Email::class)->setMethods(['sendForgotPwdEmail'])->getMock();
        $oEmail->expects($this->once())->method('sendForgotPwdEmail');
        $stut = $this->getMockBuilder(OrderController::class)->setMethods(['isRegisterNewUserNeeded'])->getMock();
        $stut->expects($this->exactly(2))->method('isRegisterNewUserNeeded')->willReturn(true);

        UtilsObject::setClassInstance(Order::class, $order);
        UtilsObject::setClassInstance(Email::class, $oEmail);
        $this->setProtectedClassProperty($stut, '_oUser', $oUser);
        $stut->kcoExecute($oBasket);

    }

    /**
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     * @throws \ReflectionException
     */
    public function testGetKlarnaAllowedExternalPayments()
    {
        $database = DatabaseProvider::getDB();
        $database->execute("UPDATE oxpayments SET oxactive=1, tcklarna_externalpayment=1 WHERE oxid='oxidpayadvance'");

        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('getKlarnaAllowedExternalPayments');
        $method->setAccessible(true);
        $stut = new KlarnaOrderController;
        $result = $method->invoke($stut);

        $this->assertEquals('oxidpayadvance', $result[0]);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetKlarnaOrderClient()
    {
        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('getKlarnaOrderClient');
        $method->setAccessible(true);
        $stut = new KlarnaOrderController;
        $result = $method->invokeArgs($stut, ['DE']);

        $this->assertInstanceOf(KlarnaOrderManagementClient::class, $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testKcoBeforeExecute()
    {


        $user = $this->getMockBuilder(User::class)->setMethods(['setNewsSubscription'])->getMock();
        $user->expects($this->once())->method('setNewsSubscription')->willReturn(true);
        $order = $this->getMockBuilder(KlarnaOrder::class)->disableOriginalConstructor()->setMethods(['isError'])->getMock();
        $order->expects($this->any())->method('isError')->willReturn(false);
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['validateUser', 'getUser', 'initKlarnaOrder'])->getMock();
        $stut->expects($this->once())->method('validateUser')->willReturn(true);
        $stut->expects($this->once())->method('getUser')->willReturn($user);
        $stut->expects($this->once())->method('initKlarnaOrder')->willReturn($order);

        $this->setProtectedClassProperty(
            $stut,
            '_aOrderData',
            ['merchant_requested' => ['additional_checkbox' => true]]
        );
        $this->setModuleConfVar('iKlarnaActiveCheckbox', 2);
        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('kcoBeforeExecute');
        $method->setAccessible(true);
        $result = $method->invoke($stut);
        $this->assertNull($result);

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['validateUser', 'initKlarnaOrder'])->getMock();
        $stut->expects($this->once())->method('validateUser')->willReturn(true);
        $stut->expects($this->once())->method('initKlarnaOrder')->willReturn($order);
        $this->setProtectedClassProperty(
            $stut,
            '_aOrderData',
            ['merchant_requested' => ['additional_checkbox' => true]]
        );
        $this->setModuleConfVar('iKlarnaActiveCheckbox', 2);

        $this->expectException(StandardException::class);
        $this->expectExceptionMessage('no user object');
        $method->invoke($stut);
        $result = $this->getProtectedClassProperty($stut, '_aResultErrors');
        $this->assertEquals('test', $result[0]);
    }

    /**
     * @throws \ReflectionException
     */
    public function testKcoBeforeExecuteException()
    {

        $order = $this->getMockBuilder(KlarnaOrder::class)->disableOriginalConstructor()->setMethods(['isError'])->getMock();
        $order->expects($this->once())->method('isError')->willReturn(false);
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['validateUser', 'initKlarnaOrder'])->getMock();
        $stut->expects($this->any())->method('validateUser')->willThrowException(new StandardException('test'));
        $stut->expects($this->any())->method('initKlarnaOrder')->willReturn($order);

        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('kcoBeforeExecute');
        $method->setAccessible(true);
        $method->invoke($stut);
        $result = $this->getProtectedClassProperty($stut, '_aResultErrors');
        $this->assertEquals('test', $result[0]);
    }

    /**
     * @dataProvider validateUserDataProvider
     * @param $type
     * @param $expected
     * @throws \ReflectionException
     */
    public function test_validateUser($type, $expected)
    {
        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['getType'])->getMock();
        $user->expects($this->once())->method('getType')->willReturn($type);
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['createUser'])->getMock();
        $stut->expects($this->any())->method('createUser')->willReturn(true);

        $class = new \ReflectionClass(KlarnaOrderController::class);
        $method = $class->getMethod('validateUser');
        $method->setAccessible(true);
        $this->setProtectedClassProperty($stut, '_oUser', $user);
        $result = $method->invoke($stut);
        $this->assertEquals($expected, $result);
    }

    public function validateUserDataProvider()
    {
        return [
            [0, true],
            [2, true],
            [3, null],

        ];
    }

    public function testRender()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $oBasket = $this->getMockBuilder(Basket::class)->setMethods(['getPaymentId'])->getMock();
        $oBasket->expects($this->any())->method('getPaymentId')->willReturn('klarna_pay_now');
        $stut = $this->getMockBuilder(OrderController::class)->setMethods(['isCountryHasKlarnaPaymentsAvailable', 'getPayment','getUser'])->getMock();
        $stut->expects($this->any())->method('isCountryHasKlarnaPaymentsAvailable')->willReturn(true);
        $stut->expects($this->once())->method('getPayment')->willReturn(
            $this->getMockBuilder(Payment::class)
        );

        $this->getSession()->setBasket($oBasket);
        $this->setSessionParam('klarna_session_data', ['client_token' => 'test']);
        $result = $stut->render();
        $locale = $stut->getViewData()['sLocale'];
        $clientToken = $stut->getViewData()['client_token'];
        $this->assertEquals('page/checkout/order', $result);
        $this->assertEquals('de-de', $locale);
        $this->assertEquals('test', $clientToken);
        $this->assertEquals(true, $this->getProtectedClassProperty($stut, 'loadKlarnaPaymentWidget'));
        $this->setSessionParam('paymentid', 'klarna_checkout');

        $stut->render();
        $stut->expects($this->never())->method("isCountryHasKlarnaPaymentsAvailable");
    }

    public function testIsCountryHasKlarnaPaymentsAvailable()
    {
        $oUser = oxNew(User::class);
        $oUser->oxuser__oxcountryid = new Field(self::COUNTRIES['AT'], Field::T_RAW);

        $oOrderController = $this->getMockBuilder(OrderController::class)->setMethods(['getUser'])->getMock();
        $oOrderController->expects($this->once())->method('getUser')->willReturn($oUser);

        $result = $oOrderController->isCountryHasKlarnaPaymentsAvailable();
        $this->assertTrue($result);

        $oUser->oxuser__oxcountryid = new Field(self::COUNTRIES['DE'], Field::T_RAW);
        $result = $oOrderController->isCountryHasKlarnaPaymentsAvailable($oUser);
        $this->assertTrue($result);

        $oUser->oxuser__oxcountryid = new Field(self::COUNTRIES['AF'], Field::T_RAW);
        $result = $oOrderController->isCountryHasKlarnaPaymentsAvailable($oUser);
        $this->assertFalse($result);
    }

    public function initDP()
    {
        $userClassName = User::class;
        $kcoExternalPayments = ['oxidpaypal'];

        return [
            ['KP', 'payId', null, null, false, $kcoExternalPayments],
            ['KCO', 'klarna_checkout', 'DE', null, $userClassName, $kcoExternalPayments],
            ['KCO', 'klarna_checkout', 'AT', null, $userClassName, $kcoExternalPayments],
            ['KCO', 'klarna_checkout', 'AT', null, $userClassName, $kcoExternalPayments],
            ['KCO', 'klarna_checkout', 'DE', null, $userClassName, $kcoExternalPayments],
            ['KCO', 'klarna_checkout', 'AF', null, $userClassName, $kcoExternalPayments],
            ['KCO', 'bestitamazon', 'DE', null, false, $kcoExternalPayments],
            ['KCO', 'oxidpaypal', 'DE', null, false, $kcoExternalPayments],
            ['KCO', 'oxidpaypal', 'AF', null, false, $kcoExternalPayments],
        ];
    }

    /**
     * @dataProvider initDP
     * @param $mode
     * @param $payId
     * @param $countryISO
     * @param $externalCheckout
     * @param $userClassName
     * @param $kcoExternalPayments
     */
    public function testInit($mode, $payId, $countryISO, $externalCheckout, $userClassName, $kcoExternalPayments)
    {
        Registry::getConfig()->setConfigParam("sSSLShopURL","https://test.de");

        $this->setModuleMode($mode);
        $this->setRequestParameter('externalCheckout', $externalCheckout);
        $this->setSessionParam('sCountryISO', $countryISO);
        $userClassName && $this->setSessionParam('klarna_checkout_order_id', 'kcoId');

        $oBasket = oxNew(Basket::class);
        $oBasket->setPayment($payId);
        Registry::getSession()->setBasket($oBasket);
        Registry::getSession()->freeze();

        $client = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['getOrder'])->getMock();
        $userClassName && $client->expects($this->once())->method('getOrder');

        $oOrderController = $this->getMockBuilder(OrderController::class)
            ->setMethods(['getKlarnaCheckoutClient', 'getKlarnaAllowedExternalPayments'])->getMock();
        $userClassName && $oOrderController->expects($this->once())->method('getKlarnaCheckoutClient')->willReturn(
            $client
        );
        $oOrderController->expects($this->any())->method('getKlarnaAllowedExternalPayments')->willReturn(
            $kcoExternalPayments
        );
        $oOrderController->init();

        $oUser = $oOrderController->getUser();
        $userClassName ? $this->assertInstanceOf($userClassName, $oUser) : $this->assertFalse($oUser);
        $this->assertEquals($externalCheckout, $this->getSessionParam('externalCheckout'));
        $this->assertEquals(
            $externalCheckout,
            $this->getProtectedClassProperty($oOrderController, 'isExternalCheckout')
        );
        // back to default
        $this->setModuleMode('KCO');
    }

    public function testInit_exception()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->expects($this->once())->method("showMessageAndExit")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);
        Registry::getConfig()->setConfigParam("sSSLShopURL","https://test.de");
        $this->setSessionParam('sCountryISO', 'DE');

        $oBasket = oxNew(Basket::class);
        $oBasket->setPayment('klarna_checkout');
        Registry::getSession()->setBasket($oBasket);
        Registry::getSession()->freeze();

        $client = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['getOrder'])->getMock();
        $client->expects($this->once())->method('getOrder')->willThrowException(new KlarnaClientException('Test'));

        $oOrderController = $this->getMockBuilder(OrderController::class)->setMethods(['getKlarnaCheckoutClient'])->getMock();
        $oOrderController->expects($this->once())->method('getKlarnaCheckoutClient')->willReturn($client);
        $oOrderController->init();

        // we will also test ignore ajax when checkout completed
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");

        $orderData = ['status' => 'checkout_complete'];
        $client = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['getOrder'])->getMock();
        $client->expects($this->once())->method('getOrder')->willReturn($orderData);

        $oOrderController = $this->getMockBuilder(OrderController::class)->setMethods(['getKlarnaCheckoutClient'])->getMock();
        $oOrderController->expects($this->once())->method('getKlarnaCheckoutClient')->willReturn($client);
        $oOrderController->init();
        $this->assertLoggedException(KlarnaClientException::class, 'Test');
    }

    public function testInit_countryChanged()
    {
        Registry::getConfig()->setConfigParam("sSSLShopURL","https://test.de");
        $this->setSessionParam('sCountryISO', 'DE');
        $newCountry = 'AT';

        $oBasket = oxNew(Basket::class);
        $oBasket->setPayment('klarna_checkout');
        Registry::getSession()->setBasket($oBasket);
        $oOrderController = $this->getMockBuilder(OrderController::class)->setMethods(['isCountryChanged'])->getMock();
        $oOrderController->expects($this->once())->method('isCountryChanged')->willReturn($newCountry);
        $oOrderController->init();

        $this->assertEquals($newCountry, $this->getSessionParam('sCountryISO'));
        $orderData = $this->getProtectedClassProperty($oOrderController, '_aOrderData');
        $this->assertEquals($newCountry, $orderData['billing_address']['country']);
        $this->assertEquals($newCountry, $orderData['shipping_address']['country']);

    }

    public function klarnaExternalCheckoutDP()
    {
        return [
            ['bestitamazon', 0, $this->getConfig()->getSslShopUrl().'index.php?cl=KlarnaEpmDispatcher&fnc=amazonLogin'],
            ['oxidpaypal', 1, null],
            ['other', 0, $this->getConfig()->getSslShopUrl().'index.php?cl=KlarnaExpress'],
        ];
    }

    /**
     * @dataProvider klarnaExternalCheckoutDP
     * @param $paymentId
     * @param $dispatcherCallsCount
     * @param $rUrl
     */
    public function testKlarnaExternalCheckout($paymentId, $dispatcherCallsCount, $rUrl)
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        if($rUrl) {
            $utilsMock->expects($this->once())->method("redirect")->willReturn("test");
        }else {
            $utilsMock->expects($this->never())->method("redirect")->willReturn("test");

        }
        Registry::set(Utils::class,$utilsMock);

        $dispatcher = $this->getMockBuilder(BaseController::class)
            ->setMethods(['setExpressCheckout'])
            ->getMock();
        $dispatcher->expects($this->exactly($dispatcherCallsCount))->method('setExpressCheckout');
        Registry::set(StandardDispatcher::class, $dispatcher);
        Registry::set(ExpressCheckoutDispatcher::class, $dispatcher);

        $oUserMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['tcklrnaHasValidInfo'])
            ->getMock();
        $oUserMock->expects($this->any())
            ->method('tcklrnaHasValidInfo')
            ->willReturn(true);

        $oOrderController = $this->getMockBuilder(OrderController::class)
            ->setMethods(['getUser'])
            ->getMock();
        $oOrderController->expects($this->any())
            ->method('getUser')
            ->willReturn($oUserMock);

        $this->setProtectedClassProperty($oOrderController, 'selfUrl', $rUrl);
        $oOrderController->klarnaExternalCheckout($paymentId);
    }

    public function testIncludeKPWidget()
    {
        $oSession = Registry::getSession();
        $oBasket = oxNew(Basket::class);
        $oBasket->setPayment('other');
        $oSession->setBasket($oBasket);
        $oSession->freeze();
        $oOrderController = oxNew(OrderController::class);
        $this->assertFalse($oOrderController->includeKPWidget());

        $oSession = Registry::getSession();
        $oBasket = oxNew(Basket::class);
        $oBasket->setPayment('klarna_pay_now');
        $oSession->setBasket($oBasket);
        $oSession->freeze();
        $oOrderController = oxNew(OrderController::class);
        $this->assertTrue($oOrderController->includeKPWidget());
    }

    /**
     * @dataProvider undefinedActionDataProvider
     * @param $request
     */
    public function testUpdateKlarnaAjaxUndefinedAction($request)
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $mock = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest'])->getMock();
        $mock->expects($this->once())->method('getJsonRequest')->willReturn($request);
        $mock->updateKlarnaAjax();
        $expected = [
            "action" => "undefined action",
            "status" => "error",
            "data" => null,
        ];
    }

    public function undefinedActionDataProvider()
    {
        return [
            [
                ['test' => 'test'],
            ],
            [
                ['action' => 'test'],
            ],
        ];
    }

    public function testUpdateKlarnaAjaxAddressChange()
    {
        $orderData = [
            'billing_address' => ['street_address' => 'testBilling'],
            'shipping_address' => ['street_address' => 'testShipping'],
        ];

        // test client exception
        $expected = [
            "action" => "shipping_address_change",
            "status" => null,
            "data" => null,
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest', 'updateKlarnaOrder'])->getMock();
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'shipping_address_change']);
        $stut->expects($this->once())->method('updateKlarnaOrder')->willThrowException(new KlarnaWrongCredentialsException());

        $this->setProtectedClassProperty($stut, '_aOrderData', $orderData);
        $this->setProtectedClassProperty($stut, 'forceReloadOnCountryChange', true);

        $stut->updateKlarnaAjax();
        $this->assertLoggedException(KlarnaWrongCredentialsException::class, 'KLARNA_UNAUTHORIZED_REQUEST');

        // test success
        $expected = [
            "action" => "shipping_address_change",
            "status" => 'changed',
            "data" => null,
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest', 'updateKlarnaOrder'])->getMock();
        $stut->expects($this->any())->method('getJsonRequest')->willReturn(['action' => 'shipping_address_change']);
        $stut->expects($this->any())->method('updateKlarnaOrder')->willReturn(null);
        $this->setProtectedClassProperty($stut, '_aOrderData', $orderData);

        $this->setProtectedClassProperty($stut, 'forceReloadOnCountryChange', true);

        $stut->updateKlarnaAjax();

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest', 'updateKlarnaOrder'])->getMock();
        $stut->expects($this->any())->method('getJsonRequest')->willReturn(['action' => 'shipping_address_change']);
        $stut->expects($this->any())->method('updateKlarnaOrder')->willReturn(null);
        $this->setProtectedClassProperty($stut, '_aOrderData', $orderData);
        $this->setProtectedClassProperty($stut, 'forceReloadOnCountryChange', true);

        $oBasketMock = $this->getMockBuilder(Basket::class)->setMethods(['getVouchers', 'klarnaValidateVouchers'])->getMock();
        $oBasketMock->expects( $this->any())->method( 'getVouchers' )->will($this->returnValue(['voucher1']));
        $this->getSession()->setBasket($oBasketMock);

        // test voucher widget update
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $stut->updateKlarnaAjax();

        $oBasketMock = $this->getMockBuilder(Basket::class)->setMethods(['getVouchers', 'klarnaValidateVouchers'])->getMock();
        $oBasketMock->method( 'getVouchers' )->will($this->returnValue(['voucher1']));
        $oBasketMock->expects( $this->once() )->method( 'klarnaValidateVouchers' );
        $this->getSession()->setBasket($oBasketMock);
        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxShippingOptionChange()
    {
        $expected = [
            "action" => "shipping_option_change",
            "status" => "error",
            "data" => null,
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest',])->getMock();
        $stut->expects($this->any())->method('getJsonRequest')->willReturn(['action' => 'shipping_option_change']);

        $stut->updateKlarnaAjax();

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest', 'updateKlarnaOrder'])->getMock();
        $stut->expects($this->once())->method('updateKlarnaOrder')->willThrowException(new StandardException('Test'));
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(
            ['action' => 'shipping_option_change', 'id' => '1']
        );

        $expected = [
            "action" => "shipping_option_change",
            "status" => "changed",
            "data" => [],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        $oBasket = $this->getMockBuilder(KlarnaBasket::class)->getMock();
        $oBasket->expects($this->once())->method('setShipping')->with(1);

        $this->getSession()->setBasket($oBasket);
        $stut->updateKlarnaAjax();


        $this->assertLoggedException(StandardException::class, 'Test');
    }

    public function testUpdateKlarnaAjaxUpdateSession()
    {
        $oCountry = $this->getMockBuilder(Country::class)->setMethods(['buildSelectString', 'assignRecord'])->getMock();
        $oCountry->expects($this->once())->method('buildSelectString')->willReturn(true);
        $oCountry->expects($this->once())->method('assignRecord')->willReturn(true);
        $oCountry->oxcountry__oxisoalpha2 = new Field('test');
        UtilsObject::setClassInstance(Country::class, $oCountry);

        $stut = $this->getMockBuilder(
            KlarnaOrderController::class)->setMethods(
            ['updateKlarnaOrder', 'getJsonRequest']
        )->getMock();
        $stut->expects($this->any())->method('updateKlarnaOrder')->willThrowException(new StandardException('Test'));
        $stut->expects($this->any())->method('getJsonRequest')->willReturn(
            ['action' => 'change', 'country' => 'country']
        );

        $this->setProtectedClassProperty(
            $stut,
            '_aOrderData',
            ['merchant_urls' => ['checkout' => 'url']]
        );

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->expects($this->at(0))->method("showMessageAndExit")->with($this->equalTo('{"action":"updateSession","status":"redirect","data":{"url":"url"}}'));
        Registry::set(Utils::class,$utilsMock);

        $stut->updateKlarnaAjax();
        $this->assertLoggedException(StandardException::class, 'Test');
        $this->assertTrue($this->getProtectedClassProperty($stut, 'forceReloadOnCountryChange'));
        $this->assertEquals('test', $this->getSessionParam('sCountryISO'));
    }

    public function updateKlarnaOrderDataProvider()
    {
        $orderData = [
            'billing_address' => 'testValue_1',
            'shipping_address' => 'testValue_2'
        ];

        return [
            ['user', 1, $orderData],
            ['user', 1, []],
            [null, 0, $orderData],
        ];
    }

    /**
     * @dataProvider updateKlarnaOrderDataProvider
     * @param string $setUser
     * @param $updatedCallsCount
     * @param $aOrderData
     */
    public function testUpdateKlarnaOrder($setUser, $updatedCallsCount, $aOrderData)
    {
        $oUser = ('user' === $setUser) ? oxNew(User::class) : null;

        // tested by calling updateKlarnaAjax
        $oClient = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['createOrUpdateOrder'])->getMock();
        $oClient->expects($this->exactly($updatedCallsCount))->method('createOrUpdateOrder')->with(
            $this->callback(function($subject) use($aOrderData){
                if(empty($aOrderData)) {
                    return true;
                }
                return strpos($subject, 'testValue_1') !== false && strpos($subject, 'testValue_2') !== false;
            })
        );

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $mock = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest', 'getKlarnaCheckoutClient'])->getMock();
        $mock->expects($this->any())->method('getJsonRequest')->willReturn(['action' => 'change', 'country' => 'country']);
        $mock->expects($this->exactly($updatedCallsCount))->method('getKlarnaCheckoutClient')->willReturn($oClient);
        $this->setProtectedClassProperty($mock, '_oUser', $oUser);
        $this->setProtectedClassProperty($mock, '_aOrderData', $aOrderData);
        $mock->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxCheckOrderStatus()
    {
        $expected = [
            "action" => "checkOrderStatus",
            "status" => "submit",
            "data" => null,
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        //INVALID SUBMIT TEST
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getJsonRequest'])->getMock();
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'checkOrderStatus']);
        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxCheckOrderStatus_2() {
        $expected = [
            'action' => "TopConcepts\Klarna\Controller\KlarnaOrderController::checkOrderStatus",
            'status' => "authorize",
            'data' =>
                [
                    'update' =>
                        [
                            'action' => "checkOrderStatus",
                        ],
                    'paymentMethod' => false,
                    'refreshUrl' => null,
                ],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        //VALID SUBMIT TEST
        $oPayment = $this->getMockBuilder(KlarnaPayment::class)->disableOriginalConstructor()
            ->setMethods(['isSessionValid', 'validateClientToken', 'isAuthorized'])->getMock();
        $oPayment->expects($this->once())->method('isSessionValid')->willReturn(true);
        $oPayment->expects($this->once())->method('validateClientToken')->willReturn(true);
        $oPayment->expects($this->any())->method('isAuthorized')->willReturn(false);
        $oPayment->paymentChanged = true;
        UtilsObject::setClassInstance(KlarnaPayment::class, $oPayment);

        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['resolveCountry'])->getMock();
        $user->expects($this->once())->method('resolveCountry')->willReturn('DE');

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getUser', 'getJsonRequest'])->getMock();
        $stut->expects($this->once())->method('getUser')->willReturn($user);
        $stut->expects($this->any())->method('getJsonRequest')->willReturn(['action' => 'checkOrderStatus']);

        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $this->setSessionParam('klarna_session_data', true);
//        $this->setSessionParam('sCountryISO', 'EN');
        $this->setSessionParam('reauthorizeRequired', true);

        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxCheckOrderStatus_3() {

        $expected = [
            'action' => "TopConcepts\Klarna\Controller\KlarnaOrderController::checkOrderStatus",
            'status' => "refresh",
            'data' => ['refreshUrl' => null],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $this->setSessionParam('klarna_session_data', true);

        //INVALID TOKEN TEST
        $oPayment = $this->getMockBuilder(KlarnaPayment::class)->disableOriginalConstructor()->setMethods(['isSessionValid', 'validateClientToken'])->getMock();
        $oPayment->expects($this->once())->method('isSessionValid')->willReturn(true);
        $oPayment->expects($this->once())->method('validateClientToken')->willReturn(false);

        UtilsObject::setClassInstance(KlarnaPayment::class, $oPayment);
        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['resolveCountry'])->getMock();
        $user->expects($this->once())->method('resolveCountry')->willReturn('DE');
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getUser', 'getJsonRequest'])->getMock();
        $stut->expects($this->once())->method('getUser')->willReturn($user);
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'checkOrderStatus']);

        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxCheckOrderStatus_4() {

        $expected = [
            'action' => "TopConcepts\Klarna\Controller\KlarnaOrderController::checkOrderStatus",
            'status' => "authorize",
            'data' =>
                [
                    'update' =>
                        [
                            'action' => "checkOrderStatus",
                        ],
                    'paymentMethod' => false,
                    'refreshUrl' => null,
                ],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $this->setSessionParam('klarna_session_data', true);

        //NOT AUTHORIZED PAYMENT TEST
        $oPayment = $this->getMockBuilder(KlarnaPayment::class)->disableOriginalConstructor()->setMethods(['isSessionValid', 'validateClientToken', 'isAuthorized'])->getMock();
        $oPayment->expects($this->once())->method('isSessionValid')->willReturn(true);
        $oPayment->expects($this->once())->method('validateClientToken')->willReturn(true);
        $oPayment->expects($this->any())->method('isAuthorized')->willReturn(false);
        UtilsObject::setClassInstance(KlarnaPayment::class, $oPayment);

        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['resolveCountry'])->getMock();
        $user->expects($this->once())->method('resolveCountry')->willReturn('DE');
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getUser', 'getJsonRequest'])->getMock();
        $stut->expects($this->once())->method('getUser')->willReturn($user);
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'checkOrderStatus']);

        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxCheckOrderStatus_5() {

        $expected = [
            'action' => "TopConcepts\Klarna\Controller\KlarnaOrderController::checkOrderStatus",
            'status' => "finalize",
            'data' =>
                [
                    'update' =>
                        [
                            'action' => "checkOrderStatus",
                        ],
                    'paymentMethod' => false,
                    'refreshUrl' => null,
                ],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        //REQUIRE FINALIZATION PAYMENT TEST
        $this->setSessionParam('reauthorizeRequired', false);
        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $this->setSessionParam('klarna_session_data', true);

        $oPayment = $this->getMockBuilder(KlarnaPayment::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSessionValid', 'validateClientToken', 'isAuthorized', 'isOrderStateChanged', 'isTokenValid', 'requiresFinalization'])
            ->getMock();
        $oPayment->expects($this->once())->method('isSessionValid')->willReturn(true);
        $oPayment->expects($this->once())->method('validateClientToken')->willReturn(true);
        $oPayment->expects($this->any())->method('isAuthorized')->willReturn(true);
        $oPayment->expects($this->once())->method('isOrderStateChanged')->willReturn(false);
        $oPayment->expects($this->once())->method('isTokenValid')->willReturn(true);
        $oPayment->expects($this->once())->method('requiresFinalization')->willReturn(true);
        UtilsObject::setClassInstance(KlarnaPayment::class, $oPayment);
        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['resolveCountry'])->getMock();
        $user->expects($this->once())->method('resolveCountry')->willReturn('DE');
        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getUser', 'getJsonRequest'])->getMock();
        $stut->expects($this->once())->method('getUser')->willReturn($user);
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'checkOrderStatus']);

        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxAddUserData()
    {
        $expected = [
            'action' => 'resetKlarnaPaymentSession',
            'status' => 'redirect',
            'data' => ['url' => Registry::getConfig()->getShopSecureHomeUrl().'cl=payment'],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        //INVALID SESSION
        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $this->setSessionParam('klarna_session_data', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");

        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['resolveCountry'])->getMock();
        $user->expects($this->once())->method('resolveCountry')->willReturn('DE');

        $oPayment = $this->getMockBuilder(KlarnaPayment::class)->disableOriginalConstructor()->setMethods(['isSessionValid'])->getMock();
        $oPayment->expects($this->once())->method('isSessionValid')->willReturn(false);

        UtilsObject::setClassInstance(KlarnaPayment::class, $oPayment);

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getUser', 'getJsonRequest'])->getMock();
        $stut->expects($this->once())->method('getUser')->willReturn($user);
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'addUserData']);

        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxAddUserData_1()
    {
        $expected = [
            'action' => "TopConcepts\Klarna\Controller\KlarnaOrderController::addUserData",
            'status' => "refresh",
            'data' => ['refreshUrl' => null],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        //VALID SESSION
        //INVALID CLIENT TOKEN
        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $this->setSessionParam('klarna_session_data', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");

        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['resolveCountry'])->getMock();
        $user->expects($this->once())->method('resolveCountry')->willReturn('DE');

        $oPayment = $this->getMockBuilder(KlarnaPayment::class)->disableOriginalConstructor()->setMethods(['isSessionValid', 'validateClientToken'])->getMock();
        $oPayment->expects($this->once())->method('isSessionValid')->willReturn(true);
        $oPayment->expects($this->once())->method('validateClientToken')->willReturn(false);
        UtilsObject::setClassInstance(KlarnaPayment::class, $oPayment);

        $stut = $this->getMockBuilder(KlarnaOrderController::class)->setMethods(['getUser', 'getJsonRequest'])->getMock();
        $stut->expects($this->once())->method('getUser')->willReturn($user);
        $stut->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'addUserData']);

        $stut->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxAddUserData_2()
    {
        $expected = [
            'action' => "TopConcepts\Klarna\Controller\KlarnaOrderController::addUserData",
            'status' => "updateUser",
            'data' => ['update' => []],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        //VALID SESSION
        //VALID CLIENT TOKEN
        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $this->setSessionParam('klarna_session_data', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");
        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['resolveCountry'])->getMock();
        $user->expects($this->once())->method('resolveCountry')->willReturn('DE');

        $oPayment = $this->getMockBuilder(KlarnaPayment::class)->disableOriginalConstructor()->setMethods(['isSessionValid', 'validateClientToken'])->getMock();
        $oPayment->expects($this->once())->method('isSessionValid')->willReturn(true);
        $oPayment->expects($this->once())->method('validateClientToken')->willReturn(true);

        UtilsObject::setClassInstance(KlarnaPayment::class, $oPayment);

        $mock = $this->getMockBuilder(KlarnaOrderController::class)
            ->setMethods(['getUser', 'getJsonRequest'])
            ->getMock();
        $mock->expects($this->once())->method('getUser')->willReturn($user);
        $mock->expects($this->once())->method('getJsonRequest')->willReturn(['action' => 'addUserData']);

        $mock->updateKlarnaAjax();
    }

    public function testUpdateKlarnaAjaxPaymentEnabled()
    {

        $expected = [
            'action' => "resetKlarnaPaymentSession",
            'status' => "redirect",
            'data' =>
                [
                    'url' => Registry::getConfig()->getShopSecureHomeUrl()."cl=basket",
                ],
        ];

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        $utilsMock->method("showMessageAndExit")->with($this->equalTo(json_encode($expected)));
        Registry::set(Utils::class,$utilsMock);

        $stut = oxNew(KlarnaOrderController::class);
        $this->setModuleConfVar('sKlarnaActiveMode', KlarnaConsts::MODULE_MODE_KP);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");
        $stut->updateKlarnaAjax();
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
