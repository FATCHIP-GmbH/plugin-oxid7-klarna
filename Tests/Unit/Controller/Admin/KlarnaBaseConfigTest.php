<?php

namespace TopConcepts\Klarna\Tests\Unit\Controller\Admin;


use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\ResultSet;
use TopConcepts\Klarna\Controller\Admin\KlarnaBaseConfig;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

/**
 * Class KlarnaBaseConfigTest
 * @package TopConcepts\Klarna\Tests\Unit\Controller\Admin
 */
class KlarnaBaseConfigTest extends ModuleUnitTestCase {
    public function testGetAllActiveOxPaymentIds() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['authorize'])->getMock();
        $stub->expects($this->any())->method('authorize')->willReturn(true);
        $result = $stub->getAllActiveOxPaymentIds();
        $this->assertInstanceOf(ResultSet::class, $result);
    }

    public function testRender() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['authorize', 'getEditObjectId', 'getViewDataElement'])->getMock();
        $stub->expects($this->once())->method('authorize')->willReturn(true);
        $stub->expects($this->any())->method('getEditObjectId')->willReturn('test');
        $stub->expects($this->once())->method('getViewDataElement')->willReturn(['aKlarnaDesign' =>
                                                                                     'color_button =&gt; #D5FF4D
            color_button_text =&gt; #40FF53
            color_checkbox =&gt; #FF40DF
            color_checkbox_checkmark =&gt; #FFC387
            color_header =&gt; #FF7AC6
            color_link =&gt; #FFA200
            radius_border =&gt; 4px',
        ]);
        $expectedResult = [
            'aKlarnaDesign' =>
                [
                    'color_button'             => '#D5FF4D',
                    'color_button_text'        => '#40FF53',
                    'color_checkbox'           => '#FF40DF',
                    'color_checkbox_checkmark' => '#FFC387',
                    'color_header'             => '#FF7AC6',
                    'color_link'               => '#FFA200',
                    'radius_border'            => '4px',
                ],
        ];
        $stub->init();
        $stub->render();
        $result = $this->getProtectedClassProperty($stub, '_aViewData')['confaarrs'];

        $this->assertEquals($expectedResult, $result);
    }

    public function testSave() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['authorize'])->getMock();
        $stub->expects($this->once())->method('authorize')->willReturn(true);
        $stub->init();
        $stub->save();
        $this->assertNull($stub->getParameter('confaarrs'));

        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['authorize', 'getParameter', '_aConfParams'])->getMock();
        $stub->expects($this->once())->method('authorize')->willReturn(true);
        $stub->expects($this->any())->method('getParameter')->willReturn(['test' => 'test']);
        $this->setProtectedClassProperty($stub, '_aConfParams', ['test' => 'test']);
        $stub->init();
        $stub->save();

        $this->assertEquals(['test' => 'test'], $stub->getParameter('confaarrs'));
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['authorize', 'getParameter', '_aConfParams'])->getMock();
        $stub->expects($this->once())->method('authorize')->willReturn(true);
        $stub->expects($this->any())->method('getParameter')->willReturn(['test' => 'test']);
        $this->setProtectedClassProperty($stub, '_aConfParams', ['test' => 'test']);
        $stub->init();
        $stub->save();
    }

    public function testGetFlippedLangArray() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['init'])->getMock();
        $result = $stub->getFlippedLangArray();
        $de = $result['de'];
        $en = $result['en'];

        $deExpected = $this->getLangExpected();
        $deExpected->selected = $de->selected;
        $this->assertEquals($deExpected, $de);

        $enExpected = $this->getLangExpected('en');
        $enExpected->selected = $en->selected;
        $this->assertEquals($enExpected, $en);

    }

    public function testSetParameter() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['init'])->getMock();
        $stub->setParameter('test', 'test');
        $this->assertEquals($stub->getParameter('test'), 'test');
    }

    public function testInit() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['authorize'])->getMock();
        $stub->expects($this->once())->method('authorize')->willReturn(true);
        $this->assertNull($this->getProtectedClassProperty($stub, '_oRequest'));
        $stub->init();
        $this->assertNotEmpty($this->getProtectedClassProperty($stub, '_oRequest'));
    }

    public function testGetManualDownloadLink() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['init'])->getMock();
        $result = $stub->getManualDownloadLink();
        $this->assertStringStartsWith('https://', $result);
    }

    public function testGetLangs() {
        $stub = $this->getMockBuilder(KlarnaBaseConfig::class)->setMethods(['init'])->getMock();
        $result = json_decode(html_entity_decode($stub->getLangs()));
        $de = $result[0];
        $en = $result[1];

        $deExpected = $this->getLangExpected();
        $deExpected->selected = $de->selected;
        $this->assertEquals($de, $deExpected);

        $enExpected = $this->getLangExpected('en');
        $enExpected->selected = $en->selected;
        $this->assertEquals($enExpected, $en);

    }

    protected function getLangExpected($lang = 'de') {
        if ($lang == 'de') {
            return (object)[
                'id'     => 0,
                'oxid'   => "de",
                'abbr'   => "de",
                'name'   => "Deutsch",
                'active' => "1",
                'sort'   => "1",
            ];

        } else {
            return (object)[
                'id'       => 1,
                'oxid'     => "en",
                'abbr'     => "en",
                'name'     => "English",
                'active'   => "1",
                'sort'     => "2",
                'selected' => 0,
            ];
        }
    }
}
