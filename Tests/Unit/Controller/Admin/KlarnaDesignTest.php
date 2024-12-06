<?php

namespace TopConcepts\Klarna\Tests\Unit\Controller\Admin;


use OxidEsales\Eshop\Application\Model\Actions;
use OxidEsales\Eshop\Core\Registry;
use TopConcepts\Klarna\Controller\Admin\KlarnaDesign;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class KlarnaDesignTest extends ModuleUnitTestCase
{

    public function testRender()
    {
        $obj    = new KlarnaDesign();
        $result = $obj->render();

        $viewData = $obj->getViewData();
        $this->assertEquals('@tcklarna/admin/tcklarna_design', $result);
        $this->assertEquals('de_de', $viewData['locale']);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        putenv("HTTP_X_REQUESTED_WITH=xmlhttprequest");
        $obj    = $this->getMockBuilder(KlarnaDesign::class)->setMethods(['getMultiLangData'])->getMock();
        $obj->expects($this->once())->method('getMultiLangData')->willReturn('test');
        $result = $obj->render();
        $this->assertEquals('"test"', $result);

    }
}
