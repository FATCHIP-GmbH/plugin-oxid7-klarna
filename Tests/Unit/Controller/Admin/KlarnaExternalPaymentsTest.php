<?php

namespace TopConcepts\Klarna\Tests\Unit\Controller\Admin;


use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Utils;
use TopConcepts\Klarna\Controller\Admin\KlarnaExternalPayments;
use TopConcepts\Klarna\Core\KlarnaConsts;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class KlarnaExternalPaymentsTest extends ModuleUnitTestCase {

    public function testRender() {
        $controller = new KlarnaExternalPayments();
        $this->setModuleConfVar('sKlarnaActiveMode', 'test');
        $result = $controller->render();

        $viewData = $controller->getViewData();

        $this->assertEquals('@tcklarna/tcklarna_external_payments', $result);
        $this->assertEquals('test', $viewData['mode']);
        $this->assertNotEmpty($viewData['activePayments']);
        $this->assertEquals(KlarnaConsts::getKlarnaExternalPaymentNames(), $viewData['paymentNames']);

    }

    public function testGetMultilangUrls() {
        $controller = new KlarnaExternalPayments();

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")
            ->will($this->returnCallback(function($patient) {
                $this->assertNotEmpty($patient);
                $this->assertJson($patient);
            }));

        Registry::set(Utils::class,$utilsMock);
        $controller->getMultilangUrls();
    }

    public function testSave() {
        $payments = ['klarna' => ['oxpayments__tcklarna_paymentoption' => 'other']];

        $oRequest = $this->getMockBuilder(Request::class)->setMethods(['getRequestEscapedParameter'])->getMock();
        $oRequest->expects($this->once())->method('getRequestEscapedParameter')->willReturn($payments);
        $extPayments = oxNew(KlarnaExternalPayments::class);
        $this->setProtectedClassProperty($extPayments, '_oRequest', $oRequest);

        $extPayments->save();

        $payment = oxNew(Payment::class);
        $payment->load('klarna');

        $this->assertEquals($payment->oxpayments__tcklarna_paymentoption->value, 'other');
    }
}
