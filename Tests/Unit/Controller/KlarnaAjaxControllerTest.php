<?php

namespace TopConcepts\Klarna\Tests\Unit\Controller;


use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Core\Utils;
use OxidEsales\Eshop\Core\UtilsObject;
use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\Eshop\Application\Model\User;
use TopConcepts\Klarna\Controller\KlarnaAjaxController;
use TopConcepts\Klarna\Core\KlarnaCheckoutClient;
use TopConcepts\Klarna\Core\Exception\KlarnaClientException;
use TopConcepts\Klarna\Model\KlarnaBasket;
use TopConcepts\Klarna\Model\KlarnaUser;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class KlarnaAjaxControllerTest extends ModuleUnitTestCase {

    public function testInitInvalidRequest()
    {

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")
            ->will($this->returnCallback(function ($response) {
                $this->assertEquals('{"action":"init","status":"Invalid request","data":null}', $response);
            }));
        Registry::set(Utils::class, $utilsMock);

        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->setMethods(['getKlarnaCheckoutClient'])->getMock();
        $ajaxController->init();
    }

    public function testInitInvalidPaymentId()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")
            ->will($this->returnCallback(function($response) {
                $this->assertEquals('Invalid payment ID', $response);
            }));
        Registry::set(Utils::class,$utilsMock);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");
        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->setMethods(['getKlarnaCheckoutClient'])->getMock();
        $ajaxController->init();
        $oBasket = $this->getMockBuilder(KlarnaBasket::class)->setMethods(['getPaymentId'])->getMock();
        $oBasket->expects($this->any())->method('getPaymentId')->willReturn('klarna_checkout');
        $session = Registry::getSession();
        $session->setBasket($oBasket);
    }

    public function testInitSuccess()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")
            ->will($this->returnCallback(function ($response) {
                $this->assertEquals('', $response);
            }));
        Registry::set(Utils::class, $utilsMock);

        $basketMock = $this->getMockBuilder(Basket::class)->disableOriginalConstructor()->getMock();
        $basketMock->method("getPaymentId")->willReturn('klarna_checkout');

        $sessionMock = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $sessionMock->method("getBasket")->willReturn($basketMock);

        Registry::set(Session::class, $sessionMock);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");
        $oOrder = ['test1', 'test2'];
        $client = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['getOrder'])->getMock();
        $client->expects($this->once())->method('getOrder')->willReturn($oOrder);
        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->setMethods(['getKlarnaCheckoutClient'])->getMock();
        $ajaxController->expects($this->once())->method('getKlarnaCheckoutClient')->willReturn($client);
        \oxUtilsHelper::$response = '';
        $ajaxController->init();
        $result = $this->getProtectedClassProperty($ajaxController, '_aOrderData');
        $this->assertEquals($oOrder, $result);

    }

    public function testInitReadOnly()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")
            ->will($this->returnCallback(function ($response) {
                $this->assertEquals('{"action":"ajax","status":"read_only","data":null}', $response);
            }));

        $basketMock = $this->getMockBuilder(Basket::class)->disableOriginalConstructor()->getMock();
        $basketMock->method("getPaymentId")->willReturn('klarna_checkout');
        $sessionMock = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $sessionMock->method("getBasket")->willReturn($basketMock);
        Registry::set(Session::class, $sessionMock);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");
        Registry::set(Utils::class, $utilsMock);
        $oOrder = ['test1', 'test2', 'status' => 'checkout_complete'];
        $client = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['getOrder'])->getMock();
        $client->expects($this->once())->method('getOrder')->willReturn($oOrder);
        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->setMethods(['getKlarnaCheckoutClient'])->getMock();
        $ajaxController->expects($this->once())->method('getKlarnaCheckoutClient')->willReturn($client);
        $ajaxController->init();
    }

    public function testRender() {
        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->setMethods(['getKlarnaCheckoutClient', 'updateKlarnaOrder'])->getMock();
        $ajaxController->expects($this->once())->method('updateKlarnaOrder')->willReturn(false);
        $ajaxController->render();

        $ajaxController = new KlarnaAjaxController();
        $this->setProtectedClassProperty($ajaxController, '_aErrors', ['test']);
        $result = $ajaxController->render();
        $this->assertNull($result);
    }

    public function testGetKlarnaCheckoutClient() {
        $ajaxController = new KlarnaAjaxController();
        $result = $ajaxController->getKlarnaCheckoutClient();
        $this->assertInstanceOf(KlarnaCheckoutClient::class, $result);
    }

    /**
     * @dataProvider vouchersdataProvider
     * @param $method
     * @throws \oxSystemComponentException
     */
    public function testVouchers($method) {
        $this->setRequestParameter('voucherNr', '1');
        $this->setRequestParameter('voucherId', '1');
        $ajaxController = new KlarnaAjaxController();
        $ajaxController->$method();
        $result = $ajaxController->getViewData()['aIncludes'];
        $expected = [
            'vouchers' => "tcklarna_checkout_voucher_data",
            'error'    => "tcklarna_checkout_voucher_errors",
        ];

        $this->assertEquals($expected, $result);

    }

    public function vouchersdataProvider() {
        return [
            ['addVoucher'],
            ['removeVoucher'],
        ];
    }

    public function testSetKlarnaDeliveryAddress() {
        $this->setRequestParameter('klarna_address_id', '1');
        $ajaxController = new KlarnaAjaxController();
        $ajaxController->setKlarnaDeliveryAddress();

        $deladrid = $this->getSessionParam('deladrid');
        $this->assertEquals($deladrid, '1');
        $blshowshipaddress = $this->getSessionParam('blshowshipaddress');
        $this->assertEquals($blshowshipaddress, 1);

        $orderId = $this->getSessionParam('klarna_checkout_order_id');
        $this->assertNull($orderId);
    }

    public function test_initUser() {

        $user = oxNew(User::class);
        $viewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['isUserLoggedIn'])->getMock();
        $viewConfig->expects($this->once())->method('isUserLoggedIn')->willReturn(false);
        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->setMethods(['getUser', 'getViewConfig'])->getMock();
        $ajaxController->expects($this->once())->method('getUser')->willReturn($user);
        $ajaxController->expects($this->once())->method('getViewConfig')->willReturn($viewConfig);
        $ajaxController->initUser();
        $result = $this->getProtectedClassProperty($user, '_type');
        $this->assertEquals(2, $result);


        $viewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['isUserLoggedIn'])->getMock();
        $viewConfig->expects($this->once())->method('isUserLoggedIn')->willReturn(true);
        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->setMethods(['getUser', 'getViewConfig'])->getMock();
        $ajaxController->expects($this->once())->method('getUser')->willReturn($user);
        $ajaxController->expects($this->once())->method('getViewConfig')->willReturn($viewConfig);
        $ajaxController->initUser();
        $result = $this->getProtectedClassProperty($user, '_type');
        $this->assertEquals(3, $result);
    }

    public function testUpdateUserObject() {
        $methodReflection = new \ReflectionMethod(KlarnaAjaxController::class, 'updateUserObject');
        $methodReflection->setAccessible(true);
        $user = $this->getMockBuilder(KlarnaUser::class)->setMethods(['updateDeliveryAddress', 'save', 'assign'])->getMock();
        $user->expects($this->once())->method('updateDeliveryAddress')->willReturn(true);
        $user->expects($this->once())->method('save')->willReturn(true);
        $user->expects($this->once())->method('assign')->willReturn(true);
        $user->oxuser__oxbirthdate = 'test';
        $order = [
            'customer'         => ['date_of_birth' => 'test'],
            'billing_address'  => ['street_address' => 'street address 1'],
            'shipping_address' => ['street_address' => 'street address 2'],
        ];
        $ajaxController = $this->getMockBuilder(KlarnaAjaxController::class)->getMock();
        $this->setProtectedClassProperty($ajaxController, '_aOrderData', $order);
        $this->setProtectedClassProperty($ajaxController, '_oUser', $user);
        $methodReflection->invoke($ajaxController);
        $result = $this->getProtectedClassProperty($ajaxController, '_oUser');
        $expected = new Field('test');
        $this->assertEquals($expected, $result->oxuser__oxbirthdate);
    }
}
