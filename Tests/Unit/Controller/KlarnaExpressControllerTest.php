<?php

namespace TopConcepts\Klarna\Testes\Unit\Controllers;


use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Core\Utils;
use OxidEsales\Eshop\Core\UtilsObject;
use OxidEsales\Eshop\Core\UtilsView;
use TopConcepts\Klarna\Controller\KlarnaExpressController;
use TopConcepts\Klarna\Core\Exception\KlarnaClientException;
use TopConcepts\Klarna\Core\Exception\KlarnaWrongCredentialsException;
use TopConcepts\Klarna\Core\KlarnaCheckoutClient;
use TopConcepts\Klarna\Core\Exception\KlarnaBasketTooLargeException;
use TopConcepts\Klarna\Core\Exception\KlarnaConfigException;
use TopConcepts\Klarna\Core\KlarnaUtils;
use TopConcepts\Klarna\Model\KlarnaUser;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

/**
 * Class KlarnaExpressControllerTest
 * @package TopConcepts\Klarna\Testes\Unit\Controllers
 * @covers \TopConcepts\Klarna\Controller\KlarnaExpressController
 */
class KlarnaExpressControllerTest extends ModuleUnitTestCase {
    /**
     * @dataProvider getBreadCrumbDataProvider
     * @param $iLang
     * @param $expectedResult
     */
    public function testGetBreadCrumb($iLang, $expectedResult) {
        $this->setLanguage($iLang);
        $expressController = new KlarnaExpressController();
        $result = $expressController->getBreadCrumb();

        $this->assertEquals($result[0]['title'], $expectedResult['title']);
    }

    public function getBreadCrumbDataProvider() {
        return [
            [0, ['title' => 'Kasse']],
            [1, ['title' => 'Checkout']],
        ];
    }

    public function testGetKlarnaModalFlagCountries() {
        $countryList = ['DE', 'AT', 'CH'];
        $expressController = oxNew(KlarnaExpressController::class);
        $result = $expressController->getKlarnaModalFlagCountries();

        $this->assertTrue(is_array($result) && count($result)>0);
        foreach ($result as $index => $country) {
            if (in_array($country->oxcountry__oxisoalpha2->rawValue, $countryList)) {
                unset($result[$index]);
            }
        }
        $this->assertEquals(0, count($result));
    }

    /**
     * @dataProvider userDataProvider
     * @param $isFake
     * @param $userId
     * @param $expectedResult
     */
    public function testGetFormattedUserAddresses($isFake, $userId, $expectedResult) {
        $oUser = $this->getMockBuilder(User::class)->setMethods(['isFake', 'getId'])->getMock();
        $oUser->expects($this->once())
            ->method('isFake')->willReturn($isFake);
        $oUser->expects($this->any())
            ->method('getId')->willReturn($userId);
        $kcoController = oxNew($this->getProxyClassName(KlarnaExpressController::class));
        $kcoController->setNonPublicVar('_oUser', $oUser);
        $result = $kcoController->getFormattedUserAddresses();
        $this->assertEquals($expectedResult, $result);
    }

    public function userDataProvider() {
        return [
            [true, null, false],
            [false, 'fake-id', false],
        ];
    }

    public function testSetKlarnaDeliveryAddress() {
        $configMock = $this->getMockBuilder(Config::class)->disableOriginalConstructor()->getMock();
        $configMock->method("getShopConfVar")->willReturn('KCO');

        Registry::set(Config::class,$configMock);

        $this->setRequestParameter('klarna_address_id', 'delAddressId');
        $kcoController = new KlarnaExpressController();
        $this->setProtectedClassProperty($kcoController, '_oRequest', Registry::get(Request::class));
        $kcoController->setKlarnaDeliveryAddress();
        $this->assertEquals('delAddressId', $this->getSessionParam('deladrid'));
        $this->assertEquals(1, $this->getSessionParam('blshowshipaddress'));
        $this->assertTrue($this->getSessionParam('klarna_checkout_order_id') === null);
    }

    //TODO: This test requires working setup
//    public function testGetActiveShopCountries() {
//        $kcoController = new KlarnaExpressController();
//        $result = $kcoController->getActiveShopCountries();
//
//        var_dump(count($result) > 0);
//        $this->assertTrue(count($result)>0);
//
//        $active = ['DE', 'AT', 'CH', 'US', 'GB'];
//        foreach ($result as $country) {
//            $index = array_search($country->oxcountry__oxisoalpha2->value, $active);
//            if ($index !== null) {
//                unset($active[$index]);
//            }
//        }
//        $this->assertEquals(0, count($result));
//    }


    public function testInit_KP_mode() {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->expects($this->once())->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $this->setModuleMode('KP');
        $kcoController = new KlarnaExpressController();
        $kcoController->init();
    }

    public function testInit_reset() {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $this->setModuleMode('KCO');
        $this->setSessionParam('klarna_checkout_order_id', 'fake_id');
        $this->setSessionParam('resetKlarnaSession', 1);

        $kcoController = new KlarnaExpressController();
        $kcoController->init();

        $this->assertEquals(null, $this->getSessionParam('klarna_checkout_order_id'));
    }

    /**
     * @param $sslredirect
     * @param $getCurrentShopURL
     * @param $expectRedirect
     * @dataProvider checkSslDataProvider
     */
    public function testCheckSsl($sslredirect, $UrlsEqual, $expectRedirect) {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        if($expectRedirect) {
            $utilsMock->expects($this->once())->method("redirect")->willReturn("test");
        }else {
            $utilsMock->expects($this->never())->method("redirect")->willReturn("test");
        }
        Registry::set(Utils::class,$utilsMock);

        if($UrlsEqual) {
            $this->setConfigParam('sShopURL', 'https://test.de');
            $this->setConfigParam('sSSLShopURL', 'https://test.de');
        }else {
            $this->setConfigParam('sShopURL', 'http://test.de');
            $this->setConfigParam('sSSLShopURL', 'https://test.de');
        }

        $oRequest = $this->getMockBuilder(Request::class)->setMethods(['getRequestEscapedParameter'])->getMock();
        $oRequest->method('getRequestEscapedParameter')->willReturn($sslredirect);
        $kcoController = oxNew(KlarnaExpressController::class);
        $kcoController->checkSsl($oRequest);
    }

    public function checkSslDataProvider() {
        return [
            ['forced',false,false],
            ['forced',true,false],
            ['asdf',true,false],
            ['asdf',false,true]
        ];
    }


    public function testRenderBlockIframeRender() {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $this->setRequestParameter('sslredirect', 'forced');
        $keController = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['getKlarnaOrder'])->getMock();
        $this->setProtectedClassProperty($keController, 'blockIframeRender', true);
        $keController->expects($this->never())->method('getKlarnaOrder');
        $keController->init();
        $result = $keController->render();
        $this->assertEquals('@tcklarna/checkout/tcklarna_checkout', $result);
    }

    public function testRenderException() {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock
            ->method("redirect")
            ->willReturn("test")
            ->withConsecutive(
                [$this->equalTo(Registry::getConfig()->getShopSecureHomeUrl() . "cl=basket")]
            );
        Registry::set(Utils::class,$utilsMock);

        $this->setRequestParameter('sslredirect', 'forced');
        $keController = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['getKlarnaOrder'])->getMock();
        $keController->expects($this->any())->method('getKlarnaOrder')->will($this->throwException(new KlarnaBasketTooLargeException()));
        $keController->init();
        $result = $keController->render();

        $this->assertEquals('@tcklarna/checkout/tcklarna_checkout', $result);
    }

    public function testGetKlarnaClient() {
        $keController = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['init'])->getMock();
        $result = $keController->getKlarnaClient('DE');
        $this->assertInstanceOf(KlarnaCheckoutClient::class, $result);
    }


    public function testShowCountryPopup() {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $methodReflection = new \ReflectionMethod(KlarnaExpressController::class, 'showCountryPopup');
        $methodReflection->setAccessible(true);


        $keController = oxNew(KlarnaExpressController::class);
        $keController->init();
        $sessionMock = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $sessionMock->method("getVariable")->willReturn("test");
        Registry::set(Session::class,$sessionMock);
        $result = $methodReflection->invoke($keController);
        $this->assertFalse($result);

        Registry::set(Session::class,new Session());

        $this->setSessionParam('sCountryISO', false);
        $keController = oxNew(KlarnaExpressController::class);
        $keController->init();
        $sessionMock = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $sessionMock->method("getVariable")->willReturn(false);
        Registry::set(Session::class,$sessionMock);
        $result = $methodReflection->invoke($keController);
        $this->assertTrue($result);

        Registry::set(Session::class,new Session());

        $this->setRequestParameter('reset_klarna_country', true);
        $keController = oxNew(KlarnaExpressController::class);
        $keController->init();
        $result = $methodReflection->invoke($keController);
        $this->assertTrue($result);
    }

    public function testRenderWrongMerchantUrls() {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $this->setRequestParameter('sslredirect', 'forced');
        $this->setSessionParam('wrong_merchant_urls', 'sds');
        $keController = oxNew(KlarnaExpressController::class);
        $keController->init();
        $result = $keController->render();
        $viewData = $this->getProtectedClassProperty($keController, '_aViewData');
        $this->assertTrue($viewData['confError']);
        $this->assertEquals('@tcklarna/checkout/tcklarna_checkout', $result);

    }

    public function testRenderKlarnaClient() {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")->willReturn("test");
        Registry::set(Utils::class,$utilsMock);

        $this->setRequestParameter('sslredirect', 'forced');
        $checkoutClient = $this->getMockBuilder(KlarnaCheckoutClient::class)
            ->setMethods(['createOrUpdateOrder'])->getMock();
        $checkoutClient->expects($this->any())->method('createOrUpdateOrder')
            ->will($this->throwException(new KlarnaWrongCredentialsException()));
        $keController = $this->getMockBuilder(KlarnaExpressController::class)
            ->setMethods(['getKlarnaClient', 'rebuildFakeUser', 'isCountryActiveInKlarnaCheckout'])->getMock();
        $keController->expects($this->any())->method('getKlarnaClient')->willReturn($checkoutClient);
        $keController->expects($this->any())->method('rebuildFakeUser')->willReturn(true);
        $keController->method('isCountryActiveInKlarnaCheckout')->willReturn(true);
        $keController->init();
        $template = $keController->render();
        $this->assertSame('@tcklarna/checkout/tcklarna_checkout', $template);
    }

    /**
     * @dataProvider lastResortRenderRedirectDataProvider
     * @param $sCountryISO
     * @param $expectedResult
     */
    public function testLastResortRenderRedirect($isCountryActive, $expectedResult) {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock
            ->method("redirect")
            ->willReturn("test")
            ->with($this->equalTo($expectedResult));
        Registry::set(Utils::class,$utilsMock);

        $mockObj = $this->getMockBuilder(\stdClass::class)->setMethods(['createOrUpdateOrder'])->getMock();
        $mockObj->expects($this->any())->method('createOrUpdateOrder')->willReturn(true);

        $oKlarnaOrder = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['getOrderData', 'initOrder', 'getHtmlSnippet'])->getMock();
        $oKlarnaOrder->expects($this->once())->method('getOrderData')->willReturn(['purchase_country' => "test"]);
        $oKlarnaOrder->expects($this->any())->method('initOrder')->willReturn($mockObj);
        $oKlarnaOrder->expects($this->any())->method('getHtmlSnippet')->willReturn(true);
        $controller = $this->getMockBuilder(KlarnaExpressController::class)
            ->setMethods(['getKlarnaOrder', 'checkSsl', 'showCountryPopup', 'getKlarnaClient','isCountryActiveInKlarnaCheckout'])->getMock();
        $controller->expects($this->once())->method('getKlarnaOrder')->willReturn($oKlarnaOrder);
        $controller->expects($this->once())->method('checkSsl')->willReturn(null);
        $controller->expects($this->once())->method('showCountryPopup')->willReturn(true);
        $controller->expects($this->any())->method('getKlarnaClient')->willReturn($oKlarnaOrder);
        $controller->method('isCountryActiveInKlarnaCheckout')->willReturn($isCountryActive);
        $controller->render();
    }

    public function lastResortRenderRedirectDataProvider() {
        return [
            [false, Registry::getConfig()->getShopUrl() . 'index.php?cl=user'],
            [true, null],
        ];
    }

    /**
     * @dataProvider handleLoggedInUserWithNonKlarnaCountryDataProvider
     * @param $resetCountry
     * @param $expectedResult
     */
    public function testHandleLoggedInUserWithNonKlarnaCountry($resetCountry, $expectedResult) {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("redirect")
            ->willReturn("test")
            ->with($this->equalTo(Registry::getConfig()->getShopSecureHomeUrl() . $expectedResult));
        Registry::set(Utils::class,$utilsMock);

        $oUser = $this->getMockBuilder(User::class)->setMethods(['getUserCountryISO2'])->getMock();
        $oUser->expects($this->any())->method('getUserCountryISO2')->willReturn('AF');
        $oRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getRequestEscapedParameter'])->getMock();
        $oRequest->expects($this->at(0))->method('getRequestEscapedParameter')->will($this->returnValue(null));
        $oRequest->expects($this->at(1))->method('getRequestEscapedParameter')->will($this->returnValue($resetCountry));
        $controller = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['getUser'])->getMock();
        $controller->expects($this->any())->method('getUser')->willReturn($oUser);
        $controller->determineUserControllerAccess($oRequest);
    }

    /**
     * @return array
     */
    public function handleLoggedInUserWithNonKlarnaCountryDataProvider() {
        return [
            [1, null],
            [null, 'cl=user&non_kco_global_country=AF'],
        ];
    }

    public function testResolveFakeUserRegistered() {
        $mockUser = $this->getMockBuilder(User::class)->setMethods(['checkUserType'])->getMock();
        $mockUser->oxuser__oxpassword = new Field('testPass');
        $mockUser->expects($this->once())->method('checkUserType');
        $this->setSessionParam('klarna_checkout_user_email', 'test@email');
        $controller = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['getUser'])->getMock();
        $controller->expects($this->any())->method('getUser')->willReturn($mockUser);
        $result = $controller->resolveUser();
        $this->assertInstanceOf(User::class, $result);
    }

    public function testResolveFakeUserNew() {
        $this->setSessionParam('klarna_checkout_user_email', 'test@email');
        $controller = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['getUser'])->getMock();
        $controller->expects($this->any())->method('getUser')->willReturn(false);
        $result = $controller->resolveUser();
        $this->assertInstanceOf(User::class, $result);
    }

    public function testRebuildFakeUser() {
        $orderId = 'testId';
        $email = 'test@mail.com';
        $oUser = oxNew(User::class);
        $oUser->oxuser__oxpassword = new Field('');
        $oBasket = new \stdClass();
        $aOrderData = [
            'order_id'        => $orderId,
            'billing_address' => ['email' => $email]
        ];
        $oClient = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['getOrder'])->getMock();
        $oClient->expects($this->any())->method('getOrder')->willReturn($aOrderData);
        $controller = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['getUser', 'getKlarnaCheckoutClient'])->getMock();
        $controller->expects($this->any())->method('getUser')->willReturn($oUser);
        $controller->expects($this->any())->method('getKlarnaCheckoutClient')->willReturn($oClient);
        $controller->rebuildFakeUser($oBasket);

        // assert we rebuild user context
        $this->assertEquals($orderId, $this->getSessionParam('klarna_checkout_order_id'));
        $this->assertEquals($email, $this->getSessionParam('klarna_checkout_user_email'));
        $this->assertEquals($oBasket, $this->getSession()->getBasket());
        $this->getSession()->setBasket(null); // clean up

        // exception
        $oClient = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['getOrder'])->getMock();
        $oClient->expects($this->once())->method('getOrder')->willThrowException(new KlarnaClientException('Test'));
        $oUser = $this->getMockBuilder(User::class)->setMethods(['logout'])->getMock();
        $oUser->expects($this->once())->method('logout');
        $oUser->oxuser__oxpassword = new Field('');
        $controller = $this->getMockBuilder(KlarnaExpressController::class)->setMethods(['getUser', 'getKlarnaCheckoutClient'])->getMock();
        $controller->expects($this->any())->method('getUser')->willReturn($oUser);
        $controller->expects($this->any())->method('getKlarnaCheckoutClient')->willReturn($oClient);
        $controller->rebuildFakeUser($oBasket);
    }
}
