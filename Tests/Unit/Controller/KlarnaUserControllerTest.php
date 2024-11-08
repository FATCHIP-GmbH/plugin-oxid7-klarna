<?php

namespace TopConcepts\Klarna\Tests\Unit\Controller;


use OxidEsales\Eshop\Application\Controller\UserController;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

/**
 * Class KlarnaUserControllerTest
 * @package TopConcepts\Klarna\Tests\Unit\Controller
 * @covers \TopConcepts\Klarna\Controller\KlarnaUserController
 */
class KlarnaUserControllerTest extends ModuleUnitTestCase
{

    /**
     * @dataProvider initDataProvider
     * @param $amazonRef
     * @param $mode
     * @param $countryISO
     * @param $rUrl
     */
    public function testInit($amazonRef, $mode, $countryISO, $rUrl)
    {
        $this->setRequestParameter('amazonOrderReferenceId', $amazonRef);
        $this->setModuleMode($mode);
        $this->setSessionParam('sCountryISO', $countryISO);

        $userController = oxNew(UserController::class);
        $userController->init();

        $this->assertEquals($rUrl, \oxUtilsHelper::$sRedirectUrl);
        $this->assertEquals($amazonRef, $this->getSessionParam('amazonOrderReferenceId'));
    }

    /**
     * @dataProvider getInvoiceAddressDataProvider
     * @param $mode
     * @param $testValue
     * @param $countryISO
     * @param $expectedResult
     */
    public function testGetInvoiceAddress($mode, $testValue, $countryISO, $expectedResult)
    {
        $this->setModuleMode($mode);
        $this->setRequestParameter('invadr', $testValue);
        $this->setSessionParam('sCountryISO', $countryISO);

        $oUserController = oxNew(UserController::class);
        $this->assertEquals($expectedResult, $oUserController->getInvoiceAddress());
    }

    public function getInvoiceAddressDataProvider()
    {
        return[
            ['KP', ['addrField' => 'addrVal'], 'DE', ['addrField' => 'addrVal'] ],
        ];
    }


}
