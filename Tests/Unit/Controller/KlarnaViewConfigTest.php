<?php

namespace TopConcepts\Klarna\Testes\Unit\Controllers;


use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\CountryList;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Price;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ViewConfig;
use TopConcepts\Klarna\Controller\KlarnaViewConfig;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

/**
 * Class KlarnaViewConfigTest
 * @package TopConcepts\Klarna\Testes\Unit\Controllers
 * @covers \TopConcepts\Klarna\Controller\KlarnaViewConfig
 */
class KlarnaViewConfigTest extends ModuleUnitTestCase
{

    public function testAddBuyNow()
    {
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveBoolean('blKlarnaDisplayBuyNow', true, 'tcklarna');

        $oViewConfig = oxNew(ViewConfig::class);
        $result = $oViewConfig->addBuyNow();

        $this->assertEquals($result, $this->getConfigParam('blKlarnaDisplayBuyNow'));
    }

    public function isKPEnabledDataProvider()
    {
        return [
            ['KP', true]
        ];
    }

    /**
     * @dataProvider isKPEnabledDataProvider
     * @param $mode
     * @param $expectedResult
     */
    public function testIsKlarnaPaymentsEnabled($mode, $expectedResult)
    {
        $this->setModuleMode($mode);

        $oViewConfig = oxNew(ViewConfig::class);
        $result = $oViewConfig->isKlarnaPaymentsEnabled();
        $this->assertEquals($expectedResult, $result);
    }

    public function isKarnaExternalPaymentDataProvider()
    {
        return [
            ['oxidcashondel', 'DE', true],
            ['oxidcashondel', 'AF', false],
            ['oxidpayadvance', 'DE',  false]
        ];
    }

    public function isATDataProvider()
    {
        return [
            ['AT', 'a7c40f6320aeb2ec2.72885259', true],
            ['DE', 'a7c40f631fc920687.20179984', false]
        ];
    }

    /**
     * @dataProvider isATDataProvider
     * @param $iso
     * @param $oxCountryId
     * @param $result
     */
    public function testGetIsAustria($iso, $oxCountryId, $result)
    {
        $user = $this->getMockBuilder(User::class)->setMethods(['getFieldData'])->getMock();
        $user->expects($this->any())->method('getFieldData')->with('oxcountryid')->willReturn($oxCountryId);
        $oViewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['getUser'])->getMock();
        $oViewConfig->expects($this->once())->method('getUser')->willReturn($user);
        $this->assertEquals($result, $oViewConfig->getIsAustria());

    }

    public function testGetIsAustria_noUser_defaultCountry()
    {
        $oViewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['getUser'])->getMock();
        $oViewConfig->expects($this->once())->method('getUser')->willReturn(null);
        $this->assertFalse( $oViewConfig->getIsAustria());
    }

    public function isDEDataProvider()
    {
        return [
            ['a7c40f6320aeb2ec2.72885259', false],
            ['a7c40f631fc920687.20179984', true],
        ];
    }

    /**
     * @dataProvider isDEDataProvider
     * @param $mode
     * @param $oxCountryId
     * @param $expectedResult
     */
    public function testGetIsGermany($oxCountryId, $expectedResult)
    {
        $user = $this->getMockBuilder(User::class)->setMethods(['getFieldData'])->getMock();
        $user->expects($this->any())->method('getFieldData')->with('oxcountryid')->willReturn($oxCountryId);
        $oViewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['getUser'])->getMock();
        $oViewConfig->expects($this->once())->method('getUser')->willReturn($user);
        $this->assertEquals($expectedResult, $oViewConfig->getIsGermany());
    }

    public function testGetIsGermany_noUser_defaultCountry()
    {
        $oViewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['getUser'])->getMock();
        $oViewConfig->expects($this->once())->method('getUser')->willReturn(null);
        $this->assertTrue( $oViewConfig->getIsGermany());
    }

    public function isShowPrefillNotifDataProvider()
    {
        return [
            [0, false],
            [null, false],
            [1, true],
            ['1', true],
            [true, true]
        ];
    }

    /**
     * @dataProvider isShowPrefillNotifDataProvider
     * @param $value
     */
    public function testIsShowPrefillNotif($value, $expectedResult)
    {
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveBoolean('blKlarnaPreFillNotification', $value, 'tcklarna');

        $oViewConfig = oxNew(ViewConfig::class);
        $this->assertEquals($expectedResult, $oViewConfig->isShowPrefillNotif());
    }

    public function getModeDataProvider()
    {
        return [
            ['KP']
        ];
    }
    /**
     * @dataProvider getModeDataProvider
     * @param $value
     */
    public function testGetMode($value)
    {
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveString('sKlarnaActiveMode', $value, 'tcklarna');
        $oViewConfig = oxNew(ViewConfig::class);
        $this->assertEquals($value, $oViewConfig->getMode());
    }

    public function getKlarnaFooterContentDataProvider()
    {
        return [
            ['KP', 0, 'longBlack',false, false],
            ['KP', 1, 'logoFooter',false, false],
            ['KP', 2, 'logoFooter',false, [
                'url' => 'https://x.klarnacdn.net/payment-method/assets/badges/generic/klarna.svg',
                'class' => 'logoFooter'
            ]],
        ];
    }

    public function testGetKlarnaFooterContent_nonKlarnaSetAsDefault()
    {
        $this->setModuleConfVar('sKlarnaDefaultCountry', 'AF');
        $oViewConfig = oxNew(ViewConfig::class);
        $result = $oViewConfig->getKlarnaFooterContent();
        $this->assertFalse($result);
        $this->setModuleConfVar('sKlarnaDefaultCountry', 'DE');
    }

    /**
     * @dataProvider getKlarnaFooterContentDataProvider
     * @param $mode
     * @param $klFooterType
     * @param $klFooterValue
     * @param $klScript
     * @param $klPromo
     * @param $expectedResult
     */
    public function testGetKlarnaFooterContent($mode, $klFooterType, $klFooterValue, $klScript, $expectedResult)
    {
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveString('sKlarnaFooterDisplay', $klFooterType, 'tcklarna');
        $moduleSettingService->saveString('sKlarnaFooterValue', $klFooterValue, 'tcklarna');
        $moduleSettingService->saveString('sKlarnaMessagingScript', $klScript, 'tcklarna');
        $this->setModuleMode($mode);

        $oViewConfig = oxNew(ViewConfig::class);
        $result = $oViewConfig->getKlarnaFooterContent();

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @dataProvider getCountryListDataProvider
     * @param $blShipping
     * @param $isCheckoutNonKlarnaCountry
     * @param $activeClassName
     * @param $expectedResult
     */
    public function testGetCountryList($isCheckoutNonKlarnaCountry, $activeClassName, $expectedResult)
    {

        $viewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['isCheckoutNonKlarnaCountry', 'getActiveClassName'])->getMock();
        $viewConfig->expects($this->any())->method('isCheckoutNonKlarnaCountry')->willReturn($isCheckoutNonKlarnaCountry);
        $viewConfig->expects($this->any())->method('getActiveClassName')->willReturn($activeClassName);

        $result = $viewConfig->getCountryList();

        $this->assertEquals($expectedResult, $result->count());
    }

    public function testIsPrefillIframe()
    {
        $this->setModuleConfVar('blKlarnaEnablePreFilling', true, 'bool');
        $oViewConfig = oxNew(ViewConfig::class);
        $result = $oViewConfig->isPrefillIframe();

        $this->assertTrue($result);
    }

    public function testIsPrefillIframe_false()
    {
        $this->setModuleConfVar('blKlarnaEnablePreFilling', false, 'bool');
        $oViewConfig = oxNew(ViewConfig::class);
        $result = $oViewConfig->isPrefillIframe();

        $this->assertFalse($result);
    }

    public function isCheckoutNonKlarnaCountryDataProvider()
    {
        return [
            ['DE', false],
            ['AT', false],
            ['AF', true]
        ];
    }

    /**
     * @dataProvider isCheckoutNonKlarnaCountryDataProvider
     * @param $iso
     * @param $expectedResult
     */
    public function testIsCheckoutNonKlarnaCountry($iso, $expectedResult)
    {
        $this->setSessionParam('sCountryISO', $iso);
        $oViewConfig = oxNew(ViewConfig::class);
        $result = $oViewConfig->isCheckoutNonKlarnaCountry();

        $this->assertEquals($expectedResult, $result);

    }

    public function isUserLoggedInDataProvider()
    {
        $userId = 'fake_id';
        $user = new \stdClass();
        $user->oxuser__oxid = new Field($userId, Field::T_RAW);

        return [
            [$userId, $user, true],
            [null, null, false],
        ];
    }

    /**
     * @dataProvider isUserLoggedInDataProvider
     * @param $userId
     * @param $usrSession
     * @param $user
     * @param $expectedResult
     */
    public function testIsUserLoggedIn($usrSession, $user, $expectedResult)
    {
        $this->setSessionParam('usr', $usrSession);

        $viewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['getUser'])->getMock();
        $viewConfig->expects($this->once())->method('getUser')->willReturn($user);

        $this->assertEquals($expectedResult, $viewConfig->isUserLoggedIn());
    }

    /**
     * @dataProvider isActiveApexThemDataProvider
     * @param $themeName
     * @param $expectedResult
     */
    public function testIsActiveThemeApex($themeName, $expectedResult)
    {
        $oViewConfig = $this->getMockBuilder(ViewConfig::class)->setMethods(['getActiveTheme'])->getMock();
        $oViewConfig->expects($this->once())->method('getActiveTheme')->willReturn($themeName);
        $result = $oViewConfig->isActiveThemeApex();

        $this->assertEquals($expectedResult, $result);
    }


    public function isKlarnaControllerActiveDataProvider()
    {
        return [
            ['otherName', false]
        ];
    }

    public function isActiveApexThemDataProvider()
    {
        return [
            ['flow', false],
            ['Flow', false],
            ['apex', true],
            ['Apex', true]
        ];
    }

    public function testGetOnSitePromotionInfo()
    {
        //Non promotion key
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveBoolean('blKlarnaDisplayBuyNow', true, 'tcklarna');

        $oViewConfig = $this->getMockBuilder(KlarnaViewConfig::class)->setMethods(['getActiveClassName'])->getMock();
        $result = $oViewConfig->getOnSitePromotionInfo('blKlarnaDisplayBuyNow');

        $this->assertEquals($result, $this->getConfigParam('blKlarnaDisplayBuyNow'));

        $price = $this->getMockBuilder(Price::class)
            ->setMethods(['getBruttoPrice', 'getVat'])
            ->getMock();

        $price->expects($this->any())->method('getBruttoPrice')->willReturn(10);
        $price->expects($this->any())->method('getVat')->willReturn(0.23);

        //promotion product key
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveString('sKlarnaCreditPromotionProduct', 'data-purchase-amount="%s"', 'tcklarna');
        $product = $this->getMockBuilder(Article::class)->setMethods(['getPrice'])->getMock();

        $product->expects($this->any())->method('getPrice')->willReturn($price);

        $result = $oViewConfig->getOnSitePromotionInfo('sKlarnaCreditPromotionProduct', $product);

        $this->assertSame('data-purchase-amount="1000"', $result);

        //promotion basket key
        $oViewConfig->expects($this->any())->method('getActiveClassName')->willReturn('basket');
        $moduleSettingService = $this->getModuleSettings();
        $moduleSettingService->saveString('sKlarnaCreditPromotionBasket', 'data-purchase-amount="%s"', 'tcklarna');

        $basket = $this->getMockBuilder(Basket::class)->setMethods(['getPrice'])->getMock();

        $basket->expects($this->any())->method('getPrice')->willReturn($price);

        Registry::getSession()->setBasket($basket);

        $result = $oViewConfig->getOnSitePromotionInfo('sKlarnaCreditPromotionBasket');

        $this->assertSame('data-purchase-amount="998"', $result);

    }

}
