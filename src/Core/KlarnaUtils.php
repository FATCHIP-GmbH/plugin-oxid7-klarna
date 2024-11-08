<?php
/**
 * Copyright 2018 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace TopConcepts\Klarna\Core;


use OxidEsales\Eshop\Application\Model\BasketItem;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Price;
use OxidEsales\Eshop\Core\UtilsObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TopConcepts\Klarna\Model\KlarnaCountryList;
use TopConcepts\Klarna\Model\KlarnaPaymentHelper;
use TopConcepts\Klarna\Model\KlarnaUser;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Application\Model\CountryList;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Exception\SystemComponentException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;
use OxidEsales\EshopCommunity\Core\Controller\BaseController;

/**
 * Class KlarnaUtils
 * @package TopConcepts\Klarna\Core
 */
class KlarnaUtils
{
    /**
     * Datatype string to get/set ModuleVars
     */
    public const DATA_TYPE_STRING = 'string';

    /**
     * Datatype boolean to get/set ModuleVars
     */
    public const DATA_TYPE_BOOLEAN = 'boolean';

    /**
     * Datatype integer to get/set ModuleVars
     */
    public const DATA_TYPE_INTEGER = 'integer';

    /**
     * Datatype collection to get/set ModuleVars
     */
    public const DATA_TYPE_COLLECTION = 'collection';


    /**
     * @param null $email
     * @return User
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public static function getFakeUser($email = null)
    {
        /** @var User | KlarnaUser $oUser */
        $oUser = oxNew(User::class);
        $oUser->loadByEmail($email);

        $sCountryISO = Registry::getSession()->getVariable('sCountryISO');
        if ($sCountryISO) {
            $oCountry = oxNew(Country::class);
            $sCountryId = $oCountry->getIdByCode($sCountryISO);
            $oCountry->load($sCountryId);
            $oUser->oxuser__oxcountryid = new Field($sCountryId);
            $oUser->oxuser__oxcountry = new Field($oCountry->oxcountry__oxtitle->value);
        }
        Registry::getConfig()->setUser($oUser);

        return $oUser;
    }

    /**
     * @param string $sName
     * @param string $sDataType
     * @return mixed
     */
    public static function getShopConfVar($sName, $sDataType = ''): mixed
    {
        $oModuleSettingService = self::getModuleSettings();
        $return = null;

        if(strlen($sDataType) == 0) {
            switch ($sName[0]) {
                case 'b':
                    $return = $oModuleSettingService->getBoolean($sName, 'tcklarna');
                    break;
                case 'i':
                    $return = $oModuleSettingService->getInteger($sName, 'tcklarna');
                    break;
                case 's':
                    $return = $oModuleSettingService->getString($sName, 'tcklarna');
                    break;
                case 'a':
                    $return = $oModuleSettingService->getCollection($sName, 'tcklarna');
                    break;
            }
        } else {
            switch ($sDataType) {
                case self::DATA_TYPE_BOOLEAN:
                    $return = $oModuleSettingService->getBoolean($sName, 'tcklarna');
                    break;
                case self::DATA_TYPE_INTEGER:
                    $return = $oModuleSettingService->getInteger($sName, 'tcklarna');
                    break;
                case self::DATA_TYPE_STRING:
                    $return = $oModuleSettingService->getString($sName, 'tcklarna');
                    break;
                case self::DATA_TYPE_COLLECTION:
                    $return = $oModuleSettingService->getCollection($sName, 'tcklarna');
                    break;
            }
        }

        return $return;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string $sDataType
     */
    public static function saveShopConfVar($name, $value, $sDataType = ''): void
    {
        $oModuleSettingService = self::getModuleSettings();

        if(strlen($sDataType) == 0) {
            switch ($name[0]) {
                case 'b':
                    $oModuleSettingService->saveBoolean($name, $value, 'tcklarna');
                    break;
                case 'i':
                    $oModuleSettingService->saveInteger($name, $value, 'tcklarna');
                    break;
                case 's':
                    $oModuleSettingService->saveString($name, $value, 'tcklarna');
                    break;
                case 'a':
                    $oModuleSettingService->saveCollection($name, $value, 'tcklarna');
                    break;
            }
        } else {
            switch ($sDataType) {
                case self::DATA_TYPE_BOOLEAN:
                    $oModuleSettingService->saveBoolean($name, $value, 'tcklarna');
                    break;
                case self::DATA_TYPE_INTEGER:
                    $oModuleSettingService->saveInteger($name, $value, 'tcklarna');
                    break;
                case self::DATA_TYPE_STRING:
                    $oModuleSettingService->saveString($name, $value, 'tcklarna');
                    break;
                case self::DATA_TYPE_COLLECTION:
                    $oModuleSettingService->saveCollection($name, $value, 'tcklarna');
                    break;
            }
        }
    }

    /**
     * @param $sCountryId
     * @return mixed
     */
    public static function getCountryISO($sCountryId)
    {
        /** @var Country $oCountry */
        $oCountry = oxNew(Country::class);
        $oCountry->load($sCountryId);

        return $oCountry->getFieldData('oxisoalpha2');
    }

    /**
     * @return mixed
     */
    public static function getKlarnaModuleMode()
    {
        return self::getShopConfVar('sKlarnaActiveMode')->toString();
    }

    /**
     * @return bool
     */
    public static function isKlarnaPaymentsEnabled()
    {
        return self::getKlarnaModuleMode() === KlarnaConsts::MODULE_MODE_KP;
    }

    /**
     * @param null $iLang
     * @return CountryList
     */
    public static function getActiveShopCountries($iLang = null)
    {
        /** @var CountryList $oCountryList */
        $oCountryList = oxNew(CountryList::class);
        $oCountryList->loadActiveCountries($iLang);

        return $oCountryList;
    }

    /**
     * @param null $sCountryISO
     * @return array|mixed
     */
    public static function getAPICredentials($sCountryISO = null)
    {
        if (!$sCountryISO) {
            $sCountryISO = Registry::getSession()->getVariable('sCountryISO');
        }

        if (!$aCredentials = KlarnaUtils::getShopConfVar('aarrKlarnaCreds')['aKlarnaCreds_'.$sCountryISO]) {
            $aCredentials = [
                'mid' => KlarnaUtils::getShopConfVar('sKlarnaMerchantId'),
                'password' => KlarnaUtils::getShopConfVar('sKlarnaPassword'),
            ];
        }

        return $aCredentials;
    }

    /**
     * @param int|null $iLang
     * @return CountryList|KlarnaCountryList
     */
    public static function getKlarnaGlobalActiveShopCountries($iLang = null)
    {
        $oCountryList = oxNew(CountryList::class);
        $oCountryList->loadActiveKlarnaCheckoutCountries($iLang);

        return $oCountryList;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     *
     */
    public static function getKlarnaGlobalActiveShopCountryISOs($iLang = null)
    {
        $oCountryList = oxNew(CountryList::class);
        $oCountryList->loadActiveKlarnaCheckoutCountries($iLang);

        $result = [];
        foreach ($oCountryList as $country) {
            $result[] = $country->oxcountry__oxisoalpha2->value;
        }

        return $result;
    }

    /**
     * @param BasketItem $oItem
     * @param $isOrderMgmt
     * @return array
     * @throws \oxArticleInputException
     * @throws \oxNoArticleException
     */
    public static function calculateOrderAmountsPricesAndTaxes($oItem, $isOrderMgmt)
    {
        $quantity = self::parseFloatAsInt($oItem->getAmount());
        $regular_unit_price = 0;
        $basket_unit_price = 0;

        if (!$oItem->isBundle()) {
            $regUnitPrice = $oItem->getRegularUnitPrice();
            if ($isOrderMgmt) {
                if ($oItem->getArticle()->isOrderArticle()) {
                    $orderArticlePrice = oxNew(Price::class);
                    $orderArticlePrice->setPrice($oItem->getArticle()->oxorderarticles__oxbprice->value);
                    $regUnitPrice = $orderArticlePrice;
                    $unitPrice = $orderArticlePrice;
                } else {
                    $unitPrice = $oItem->getArticle()->getUnitPrice();
                }
            } else {
                $unitPrice = $oItem->getUnitPrice();
            }

            $regular_unit_price = self::parseFloatAsInt($regUnitPrice->getBruttoPrice() * 100);
            $basket_unit_price = self::parseFloatAsInt($unitPrice->getBruttoPrice() * 100);
        }

        $total_discount_amount = ($regular_unit_price - $basket_unit_price) * $quantity;
        $total_amount = $basket_unit_price * $quantity;

        if ($oItem->isBundle()) {
            $tax_rate = self::parseFloatAsInt($oItem->getUnitPrice()->getVat() * 100);
        } else {
            $tax_rate = self::parseFloatAsInt($oItem->getUnitPrice()->getVat() * 100);
        }
//        $total_tax_amount = self::parseFloatAsInt($oItem->getPrice()->getVatValue() * 100);
        $total_tax_amount = self::parseFloatAsInt(
            $total_amount - round($total_amount / ($tax_rate / 10000 + 1), 0)
        );

        $quantity_unit = 'pcs';

        return [
            $quantity,
            $regular_unit_price,
            $total_amount,
            $total_discount_amount,
            $tax_rate,
            $total_tax_amount,
            $quantity_unit
        ];
    }

    /**
     * @param $number
     *
     * @return int
     */
    public static function parseFloatAsInt($number)
    {
        return (int)(Registry::getUtils()->fRound($number));
    }

    /**
     * @param Category $oCat
     * @param array $aCategories
     * @return array
     */
    public static function getSubCategoriesArray(Category $oCat, $aCategories = [])
    {
        $aCategories[] = $oCat->getTitle();

        if ($oParentCat = $oCat->getParentCategory()) {
            return self::getSubCategoriesArray($oParentCat, $aCategories);
        }

        return $aCategories;
    }

    /**
     * @param $sCountryISO
     * @return string
     */
    public static function resolveLocale($sCountryISO)
    {
        $lang = Registry::getLang()->getLanguageAbbr();
        Registry::getSession()->setVariable('klarna_iframe_lang', $lang);

        return strtolower($lang) . '-' . strtoupper($sCountryISO);
    }

    /**
     * @return bool
     */
    public static function is_ajax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && (strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest'));
    }

    /**
     *
     */
    public static function fullyResetKlarnaSession()
    {
        Registry::getSession()->deleteVariable('paymentid');
        Registry::getSession()->deleteVariable('amazonOrderReferenceId');
        Registry::getSession()->deleteVariable('externalCheckout');
        Registry::getSession()->deleteVariable('sAuthToken');
        Registry::getSession()->deleteVariable('klarna_session_data');
        Registry::getSession()->deleteVariable('finalizeRequired');
        Registry::getSession()->deleteVariable('sCountryISO');
        Registry::getSession()->deleteVariable('sFakeUserId');
    }

    /**
     * @param $text
     * @return string|null
     */
    public static function stripHtmlTags($text)
    {
        $result = preg_replace('/<(\/)?[a-z]+[^<]*>/', '', $text);

        return $result ?: null;
    }

    /**
     * @param $iso3
     * @return false|string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public static function getCountryIso2fromIso3($iso3)
    {
        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        $oQueryBuilderFactory = self::getQueryBuilder();
        $oQueryBuilder = $oQueryBuilderFactory->create();
        $oQueryBuilder
            ->select('oxisoalpha2')
            ->from('oxcountry')
            ->where('oxisoalpha3 = :iso3')
            ->setParameter(':iso3', $iso3)
            ->SetMaxResults(1);
        $sOxisoalpha2 = $oQueryBuilder->execute();
        $sOxisoalpha2 = $sOxisoalpha2->fetchAllAssociative();

        return $sOxisoalpha2[0]['oxisoalpha2'];
    }

    /**
     * @codeCoverageIgnore
     * @param $orderId
     * @return Order
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public static function loadOrderByKlarnaId($orderId)
    {
        $oOrder = oxNew(Order::class);

        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        $oQueryBuilderFactory = self::getQueryBuilder();
        $oQueryBuilder = $oQueryBuilderFactory->create();
        $oQueryBuilder
            ->select('oxid')
            ->from('oxorder')
            ->where('tcklarna_orderid = :orderId')
            ->setParameter(':orderId', $orderId);
        $sOxid = $oQueryBuilder->execute();
        $sOxid = $sOxid->fetchOne();

        $oOrder->load($sOxid);

        return $oOrder;
    }

    public static function getIsOneKlarnaActive()
    {
        $payment = oxNew(Payment::class);
        $payment->load(KlarnaPaymentHelper::KLARNA_PAYMENT_ID);

        return $payment->oxpayments__oxactive->value == 1;
    }

    /**
     * @param $e \Exception
     */
    public static function logException($e)
    {
        if (method_exists(Registry::class, 'getLogger')) {
            Registry::getLogger()->error('KLARNA ' . $e->getMessage(), [$e]);
        } else {
            $e->debugOut();
        }
    }

    public static function log($level, $message, $context = [])
    {
        if (method_exists(Registry::class, 'getLogger')) {
            Registry::getLogger()->log($level, 'KLARNA ' . $message, $context);
        } else {
            $targetLogFile = 'oxideshop.log';
            // eshop 6.0 log wrapper
            $oConfig = Registry::getConfig();
            $iDebug = $oConfig->getConfigParam('iDebug');
            $level = strtoupper($level);
            $context = json_encode($context);
            if ($level !== 'ERROR' && $iDebug === 0) {
                return; // don't log anything besides errors in production mode
            }
            $date = (new \DateTime())->format('Y-m-d H:i:s');
            Registry::getUtils()->writeToLog(
                "[$date] OXID Logger.$level: KLARNA $message $context\n",
                $targetLogFile
            );
        }
    }

    /**
     * @param $aArrToMerge
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getModuleSettingsBools($aArrToMerge) {
        return self::getAllModuleSettings(['bool'], $aArrToMerge);
    }

    /**
     * @param $aArrToMerge
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getModuleSettingsAarrs($aArrToMerge) {
        return self::getAllModuleSettings(['aarr', 'arr'], $aArrToMerge);
    }

    /**
     * @param $aArrToMerge
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getModuleSettingsStrs($aArrToMerge) {
        return self::getAllModuleSettings(['str'], $aArrToMerge);
    }


    /**
     * @param $aSettingTypes
     * @param $aArrToMerge
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private static function getAllModuleSettings($aSettingTypes, $aArrToMerge) {
        /** @var KlarnaUtils $oModuleConfiguration */
        $oModuleConfiguration = self::getModuleConfigs('tcklarna');
        $aModuleSettings = $oModuleConfiguration->getModuleSettings();

        foreach ($aModuleSettings as $oModuleSetting) {
            if(in_array($oModuleSetting->getType(), $aSettingTypes)) {
                $aArrToMerge[$oModuleSetting->getName()] = $oModuleSetting->getValue();
            }
        }

        return $aArrToMerge;
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected static function getQueryBuilder(): mixed
    {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        return $oContainer->get(QueryBuilderFactoryInterface::class);
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected static function getModuleSettings() {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }

    /**
     * @param string $moduleId
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected static function getModuleConfigs(string $moduleId)
    {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        return $oContainer->get(ModuleConfigurationDaoBridgeInterface::class)->get($moduleId);
    }
}
