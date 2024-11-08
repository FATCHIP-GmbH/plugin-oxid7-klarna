<?php

namespace TopConcepts\Klarna\Testes\Unit\Controllers;


use OxidEsales\Eshop\Application\Controller\ThankYouController;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\BasketItem;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use TopConcepts\Klarna\Controller\KlarnaThankYouController;
use TopConcepts\Klarna\Core\Exception\KlarnaClientException;
use TopConcepts\Klarna\Core\Exception\KlarnaWrongCredentialsException;
use TopConcepts\Klarna\Model\KlarnaInstantBasket;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

/**
 * Class KlarnaThankYouControllerTest
 * @covers \TopConcepts\Klarna\Controller\KlarnaThankYouController
 * @package TopConcepts\Klarna\Testes\Unit\Controllers
 */
class KlarnaThankYouControllerTest extends ModuleUnitTestCase
{
    public function testRender_nonKCO()
    {
        $payId = 'other';
        $klSessionId = 'fake_session';

        $oBasketItem = oxNew(BasketItem::class);
        $this->setProtectedClassProperty($oBasketItem,'_sProductId', '_testArt');
        $oBasket = $this->getMockBuilder(Basket::class)->setMethods(['getContents', 'getProductsCount', 'getOrderId'])->getMock();
        $oBasket->expects($this->once())->method('getContents')->will($this->returnValue([$oBasketItem]));
        $oBasket->expects($this->once())->method('getProductsCount')->will($this->returnValue(1));
        $oBasket->expects($this->once())->method('getOrderId')->will($this->returnValue(1));

        $thankYouController = oxNew(ThankYouController::class);
        $this->setProtectedClassProperty($thankYouController, '_oBasket', $oBasket);
        $thankYouController->render();

        $this->assertArrayNotHasKey('sKlarnaIframe', $thankYouController->getViewData());
    }

    public function testSimpleRender()
    {
        $oBasketItem = oxNew(BasketItem::class);
        $this->setProtectedClassProperty($oBasketItem,'_sProductId', '_testArt');
        $oBasket = $this->getMockBuilder(Basket::class)->setMethods(['getContents', 'getProductsCount', 'getOrderId'])->getMock();
        $oBasket->expects($this->once())->method('getContents')->will($this->returnValue([$oBasketItem]));
        $oBasket->expects($this->once())->method('getProductsCount')->will($this->returnValue(1));
        $oBasket->expects($this->once())->method('getOrderId')->will($this->returnValue(1));

        $controller = $this->getMockBuilder(KlarnaThankYouController::class)->
        setMethods(['getNewKlarnaInstantBasket'])->getMock();

        $this->setProtectedClassProperty($controller, '_oBasket', $oBasket);

        $result = $controller->render();

        $expected = $this->getProtectedClassProperty($controller, '_sThisTemplate');

        $this->assertSame($expected, $result);
    }
}
