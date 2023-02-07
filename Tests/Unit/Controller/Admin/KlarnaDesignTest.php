<?php

namespace TopConcepts\Klarna\Tests\Unit\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Utils;
use TopConcepts\Klarna\Controller\Admin\KlarnaDesign;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class KlarnaDesignTest extends ModuleUnitTestCase
{

    public function testRender()
    {
        $obj    = new KlarnaDesign();
        $result = $obj->render();

        $utilsMock = $this->getMockBuilder(Utils::class)->disableOriginalConstructor()->getMock();
        $utilsMock->method("showMessageAndExit")->willReturn('');

        Registry::set(Utils::class,$utilsMock);

        $this->assertEquals('@tcklarna/tcklarna_design', $result);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");
        $obj    = $this->getMockBuilder(KlarnaDesign::class)->setMethods(['getMultiLangData'])->getMock();
        $obj->expects($this->once())->method('getMultiLangData')->willReturn('test');
        $result = $obj->render();
        $this->assertNotEquals('@tcklarna/tcklarna_design', $result);

    }
}
