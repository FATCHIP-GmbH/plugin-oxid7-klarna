<?php

namespace TopConcepts\Klarna\Tests\Unit\Component;


use OxidEsales\Eshop\Application\Component\UserComponent;
use OxidEsales\Eshop\Core\Controller\BaseController;
use OxidEsales\Eshop\Core\ViewConfig;
use ReflectionClass;
use TopConcepts\Klarna\Component\KlarnaUserComponent;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;
use OxidEsales\Eshop\Core\UtilsObject;

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
            ['KP', true, true, null],
        ];
    }

    /**
     * @dataProvider loginDataProvider
     * @param $klMode
     * @param $isEnabledPrivateSales
     * @param $isKlarnaController
     * @param $redirectUrl
     */
    public function testLogin_noredirect($klMode, $isEnabledPrivateSales, $isKlarnaController, $redirectUrl)
    {
        $this->setRequestParameter('lgn_usr', 'xxx');
        $this->setRequestParameter('lgn_pwd', 'xxx');

        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveString('sKlarnaActiveMode', $klMode , 'tcklarna');

        $cmpUser = $this->getMockBuilder(UserComponent::class)->setMethods(['klarnaRedirect'])->getMock();
        $cmpUser->expects($this->any())->method('klarnaRedirect')->willReturn($isKlarnaController);

        $oParent = $this->getMockBuilder(\stdClass::class)->setMethods(array('isEnabledPrivateSales'))->getMock();
        $oParent->expects($this->any())->method('isEnabledPrivateSales')->willReturn($isEnabledPrivateSales);
        $cmpUser->setParent($oParent);

        $cmpUser->login_noredirect();

        $this->assertEquals($redirectUrl, \oxUtilsHelper::$sRedirectUrl);
    }

    public function stateDataProvider()
    {
        return [
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
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveString('sKlarnaActiveMode', $klMode , 'tcklarna');

        $this->setRequestParameter('blshowshipaddress', $showShippingAddress);
        $this->setRequestParameter('oxaddressid', $addressIdResult);

        $cmpUser = $this->getMockBuilder(UserComponent::class)->setMethods(['changeUser_noRedirect'])->getMock();
        $cmpUser->expects($this->once())->method('changeUser_noRedirect')->willReturn(true);

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
