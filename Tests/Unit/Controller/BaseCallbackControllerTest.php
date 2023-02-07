<?php

namespace TopConcepts\Klarna\Testes\Unit\Controllers;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Utils;
use oxUtilsHelper;
use TopConcepts\Klarna\Controller\BaseCallbackController;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class BaseCallbackControllerTest extends ModuleUnitTestCase
{
    public function testInit()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")->willReturn('');

        Registry::set(Utils::class,$utilsMock);

        $controller = $this->getMockBuilder(BaseCallbackController::class)
            ->setMethods(['getFncName', 'validateRequestData'])->getMockForAbstractClass();

        $rules = [
            'testfnc' => [
                'log' => true
            ],
        ];
        $this->setProtectedClassProperty($controller, 'actionRules', $rules);
        $controller->expects($this->any())->method('getFncName')->willReturn('testfnc');
        $controller->expects($this->once())->method('validateRequestData')->willReturn(true);

        $this->setConfigParam('blKlarnaLoggingEnabled', true);
        $controller->init();

        $controller = $this->getMockBuilder(BaseCallbackController::class)
            ->setMethods(['validateRequestData'])->getMockForAbstractClass();

        $controller->expects($this->once())->method('validateRequestData')->willReturn(false);
        $controller->init();
        $this->assertSame(oxUtilsHelper::$response, null);
    }

    public function testValidateRequestData()
    {
        $controller = $this->getMockBuilder(BaseCallbackController::class)
            ->setMethods(['getActionRules'])->getMockForAbstractClass();

        $controller->expects($this->once())->method('getActionRules')->willReturn(false);
        $result = $controller->validateRequestData();
        $this->assertFalse($result);

        $controller = $this->getMockBuilder(BaseCallbackController::class)
            ->setMethods(['getFncName'])->getMockForAbstractClass();

        $rules = [
            'testfnc' => [
                'validator' => [
                    'test' => ['required', 'notEmpty', 'extract'],
                ],
            ],
        ];

        $controller->expects($this->any())->method('getFncName')->willReturn('testfnc');
        $this->setProtectedClassProperty($controller, 'actionRules', $rules);

        $result = $controller->validateRequestData();
        $this->assertFalse($result);

        $this->setProtectedClassProperty($controller, 'requestData', ['test' => 'test']);
        $result = $controller->validateRequestData();
        $this->assertTrue($result);
    }

    public function testSendResponse()
    {
        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")
            ->will($this->returnCallback(function($response) {
                $this->assertEquals(json_encode(["test" => "data"]), $response);
            }));

        Registry::set(Utils::class,$utilsMock);

        $controller = $this->getMockBuilder(BaseCallbackController::class)->getMockForAbstractClass();

        $sendResponse = self::getMethod('sendResponse', BaseCallbackController::class);
        $sendResponse->invokeArgs($controller, [["test" => "data"]]);
    }

}