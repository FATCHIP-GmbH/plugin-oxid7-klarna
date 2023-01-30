<?php
/**
 * Created by PhpStorm.
 * User: arekk
 * Date: 08.08.2018
 * Time: 11:16
 */

namespace TopConcepts\Klarna\Tests\Unit\Component\Widgets;


use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use TopConcepts\Klarna\Component\Widgets\KlarnaServiceMenu;
use TopConcepts\Klarna\Controller\KlarnaExpressController;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class KlarnaServiceMenuTest extends ModuleUnitTestCase {
    public function testInit() {
        $topViewAny = $this->getMockBuilder(KlarnaExpressController::class)->disableOriginalConstructor()->getMock();
        $topViewAny->method('getActionClassName')->willReturn('test');
        $topViewAny->method('isKlarnaFakeUser')->willReturn(true);

        $config = $this->getMockBuilder(Config::class)->disableOriginalConstructor()->getMock();
        $config->method('getTopActiveView')->willReturn($topViewAny);
        Registry::set(Config::class, $config);

        $serviceMenu = new KlarnaServiceMenu();
        $serviceMenu->init();

        self::assertArrayHasKey('oxcmp_user',$serviceMenu->getComponentNames());


        $topViewKlarna = $this->getMockBuilder(KlarnaExpressController::class)->disableOriginalConstructor()->getMock();
        $topViewKlarna->method('getActionClassName')->willReturn('klarnaexpress');
        $topViewKlarna->method('isKlarnaFakeUser')->willReturn(true);

        $config = $this->getMockBuilder(Config::class)->disableOriginalConstructor()->getMock();
        $config->method('getTopActiveView')->willReturn($topViewKlarna);
        Registry::set(Config::class, $config);

        $serviceMenu = new KlarnaServiceMenu();
        $serviceMenu->init();

        self::assertEmpty($serviceMenu->getComponentNames());
    }
}
