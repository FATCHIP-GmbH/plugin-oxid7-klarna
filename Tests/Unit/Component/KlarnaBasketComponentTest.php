<?php

namespace TopConcepts\Klarna\Tests\Unit\Component;


use OxidEsales\Eshop\Application\Component\BasketComponent;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use TopConcepts\Klarna\Component\KlarnaBasketComponent;
use TopConcepts\Klarna\Core\KlarnaCheckoutClient;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

/**
 * Class KlarnaBasketComponentTest
 * @package TopConcepts\Klarna\Tests\Unit\Components
 */
class KlarnaBasketComponentTest extends ModuleUnitTestCase {

    public function testChangebasket_kcoModeOn() {
        $oSession = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $oSession->expects($this->atLeastOnce())->method("getBasket");
        $oSession->method("hasVariable")->willReturn(true);
        Registry::set(Session::class,$oSession);

        $this->getConfig()->setConfigParam("sSSLShopURL","Test");

        $klMode = 'KCO';
        $klSessionId = 'fakeSessionId';
        $this->getConfig()->saveShopConfVar('str', 'sKlarnaActiveMode', $klMode, $this->getShopId(), 'module:tcklarna');
        Registry::getSession()->setVariable("klarna_checkout_order_id",$klSessionId);

        $cmpBasket = new KlarnaBasketComponent();

        $this->expectExceptionMessage("Call to a member function getKlarnaOrderLines() on null");
        $this->expectException(\Error::class);
        $cmpBasket->changebasket('abc', 11, 'sel', 'persparam', 'override');
    }

    public function testChangebasket_kcoModeOn_exception() {
        $this->expectException(StandardException::class);

        $oSession = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $oSession->method("hasVariable")->willThrowException(new StandardException('Test'));
        Registry::set(Session::class,$oSession);
        $this->getConfig()->setConfigParam("sSSLShopURL","Test");

        $klMode = 'KCO';
        $klSessionId = 'fakeSessionId';
        $this->getConfig()->saveShopConfVar('str', 'sKlarnaActiveMode', $klMode,$this->getShopId(),'module:tcklarna');
        $this->setSessionParam('klarna_checkout_order_id', $klSessionId);

        $cmpBasket = new KlarnaBasketComponent();
        $cmpBasket->changebasket('abc', 11, 'sel', 'persparam', 'override');

        $this->assertEquals(null, $this->getSessionParam('klarna_checkout_order_id'));
    }

    public function testChangebasket_kpModeOne() {
        $klMode = 'KP';
        $klSessionId = 'fakeSessionId';
        $this->getConfig()->saveShopConfVar('str', 'sKlarnaActiveMode', $klMode, $shopId = $this->getShopId(), $module = 'module:tcklarna');
        $this->setSessionParam('klarna_checkout_order_id', $klSessionId);

        $cmpBasket = $this->getMockBuilder(BasketComponent::class)->setMethods(['updateKlarnaOrder'])->getMock();
        $cmpBasket->expects($this->never())->method('updateKlarnaOrder');

        $cmpBasket->changebasket('abc', 11, 'sel', 'persparam', 'override');
    }

    public function testTobasket() {
        $this->expectException(\Error::class);

        $oSession = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $oSession->expects($this->atLeastOnce())->method("getBasket");
        $oSession->method("hasVariable")->willReturn(true);
        Registry::set(Session::class,$oSession);
        $this->getConfig()->setConfigParam("sSSLShopURL","Test");

        $klMode = 'KCO';
        $klSessionId = 'fakeSessionId';
        $this->getConfig()->saveShopConfVar('str', 'sKlarnaActiveMode', $klMode,$this->getShopId(),'module:tcklarna');
        $this->setSessionParam('klarna_checkout_order_id', $klSessionId);

        $cmpBasket = new KlarnaBasketComponent();

        $cmpBasket->tobasket();
    }

    public function testTobasket_WithException() {
        $this->expectException(StandardException::class);
        $oSession = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $oSession->method("hasVariable")->willThrowException(new StandardException('Test'));
        Registry::set(Session::class,$oSession);
        $this->getConfig()->setConfigParam("sSSLShopURL","Test");

        $klMode = 'KCO';
        $klSessionId = 'fakeSessionId';
        $this->getConfig()->saveShopConfVar('str', 'sKlarnaActiveMode', $klMode,$this->getShopId(),'module:tcklarna');;
        $this->setSessionParam('klarna_checkout_order_id', $klSessionId);

        $cmpBasket = new KlarnaBasketComponent();
        $cmpBasket->tobasket();

        $this->assertLoggedException(StandardException::class, 'Test');
        $this->assertEquals(null, $this->getSessionParam('klarna_checkout_order_id'));
    }

    public function testUpdateKlarnaOrder() {
        $basket = $this->getMockBuilder(Basket::class)->setMethods(['getKlarnaOrderLines'])->getMock();
        $basket->expects($this->once())->method('getKlarnaOrderLines')->willReturn(['test']);
        Registry::getSession()->setBasket($basket);

        $client = $this->getMockBuilder(KlarnaCheckoutClient::class)->setMethods(['createOrUpdateOrder'])->getMock();
        $client->expects($this->once())->method('createOrUpdateOrder')->willReturn(['testResult']);

        $basketComponent = $this->getMockBuilder(KlarnaBasketComponent::class)->setMethods(['getKlarnaCheckoutClient'])->getMock();
        $basketComponent->expects($this->once())->method('getKlarnaCheckoutClient')->willReturn($client);

        $class = new \ReflectionClass(KlarnaBasketComponent::class);
        $method = $class->getMethod('updateKlarnaOrder');
        $method->setAccessible(true);

        $result = $method->invoke($basketComponent);

        $this->assertEquals(['testResult'], $result);
    }
}
