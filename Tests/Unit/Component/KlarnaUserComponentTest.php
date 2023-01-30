<?php

namespace TopConcepts\Klarna\Tests\Unit\Component;


use OxidEsales\Eshop\Application\Component\UserComponent;
use OxidEsales\Eshop\Core\Controller\BaseController;
use OxidEsales\Eshop\Core\ViewConfig;
use ReflectionClass;
use TopConcepts\Klarna\Component\KlarnaUserComponent;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;
use OxidEsales\Eshop\Core\UtilsObject;
use function PHPUnit\Framework\assertTrue;

/**
 * Class KlarnaUserComponentTest
 * @package TopConcepts\Klarna\Tests\Unit\Components
 * @covers \TopConcepts\Klarna\Component\KlarnaUserComponent
 */
class KlarnaUserComponentTest extends ModuleUnitTestCase
{
    public function loginDataProvider()
    {
        return [
            ['KCO', true, false, false],
            ['KCO', true, true, true],
            ['KP', true, true, false],
        ];
    }

    /**
     * @dataProvider loginDataProvider
     * @param $klMode
     * @param $isEnabledPrivateSales
     * @param $isKlarnaController
     * @param $redirectUrl
     */
    public function testLogin_noredirect($klMode, $isEnabledPrivateSales, $isKlarnaController, $checkRedirect)
    {
        $this->setRequestParameter('lgn_usr', 'xxx');
        $this->setRequestParameter('lgn_pwd', 'xxx');

        $this->getConfig()->saveShopConfVar('str', 'sKlarnaActiveMode', $klMode, $shopId = $this->getShopId(), $module = 'module:tcklarna');

        $cmpUser = $this->getMockBuilder(UserComponent::class)->setMethods(['klarnaRedirect'])->getMock();
        $cmpUser->expects($this->any())->method('klarnaRedirect')->willReturn($isKlarnaController);

        $oParent = $this->getMockBuilder(\stdClass::class)->setMethods(array('isEnabledPrivateSales'))->getMock();
        $oParent->expects($this->any())->method('isEnabledPrivateSales')->willReturn($isEnabledPrivateSales);
        $cmpUser->setParent($oParent);

        $cmpUser->login_noredirect();

        if($checkRedirect) {
            assertTrue($cmpUser->klarnaRedirect());
        }
    }

    public function stateDataProvider()
    {
        return [
            ['KCO', 1, 1, 1, null],
            ['KCO', 1, 1, 1, 'fake_id'],
            ['KCO', 0, 1, null, null],
            ['KP', 1, null, null, null],
        ];
    }

    /**
     * @dataProvider stateDataProvider
     * @param $klMode
     * @param $showShippingAddress
     * @param $resetResult
     * @param $showShippingAddressResult
     * @param $addressIdResult
     */
    public function testChangeuser_testvalues($klMode, $showShippingAddress, $resetResult, $showShippingAddressResult, $addressIdResult)
    {
        $this->getConfig()->setConfigParam("sSSLShopURL","Test");
        $this->getConfig()->saveShopConfVar('str', 'sKlarnaActiveMode', $klMode, $this->getShopId(), 'module:tcklarna');
        $this->setRequestParameter('blshowshipaddress', $showShippingAddress);
        $this->setRequestParameter('oxaddressid', $addressIdResult);

        $cmpUser = $this->getMockBuilder(UserComponent::class)->setMethods(['changeUserWithoutRedirect'])->getMock();
        $cmpUser->expects($this->once())->method('changeUserWithoutRedirect')->willReturn(true);

        $cmpUser->changeuser_testvalues();
        $this->assertEquals($resetResult, $this->getSessionParam('resetKlarnaSession'));
        $this->assertEquals($showShippingAddressResult, $this->getSessionParam('blshowshipaddress'));
        $this->assertEquals($addressIdResult, $this->getSessionParam('deladrid'));
    }


    public function testGetKlarnaRedirect()
    {
        $this->setRequestParameter('cl', 'test');
        $userComp = oxNew(KlarnaUserComponent::class);

        $class  = new \ReflectionClass(KlarnaUserComponent::class);
        $method = $class->getMethod('klarnaRedirect');
        $method->setAccessible(true);

        $this->setProtectedClassProperty($userComp, '_aClasses', ['test']);

        $result = $method->invoke($userComp);

        $this->assertTrue($result);
    }
}
