<?php

namespace TopConcepts\Klarna\Controller\Admin;


use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use TopConcepts\Klarna\Core\KlarnaConsts;
use TopConcepts\Klarna\Core\KlarnaUtils;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

/**
 * Class Klarna_Config for module configuration in OXID backend
 */
class KlarnaGeneral extends KlarnaBaseConfig
{

    protected $_sThisTemplate = '@tcklarna/admin/tcklarna_general';

    protected $_aKlarnaCountryCreds = [];

    protected $_aKlarnaCountries = [];

    /** @inheritdoc */
    protected $MLVars = ['sKlarnaAnonymizedProductTitle_'];

    /**
     * Render logic
     *
     * @return string
     * @see admin/oxAdminDetails::render()
     */
    public function render()
    {
        parent::render();
        // force shopid as parameter
        // Pass shop OXID so that shop object could be loaded
        $sShopOXID = Registry::getConfig()->getShopId();

        $this->setEditObjectId($sShopOXID);

        if (KlarnaUtils::is_ajax()) {
            $output = $this->getMultiLangData();
            return Registry::getUtils()->showMessageAndExit(json_encode($output));
        }

        $this->addTplParam('tcklarna_countryCreds', $this->getKlarnaCountryCreds());
        $this->addTplParam('tcklarna_countryList', json_encode($this->getKlarnaCountryAssocList()));
        $this->addTplParam(
            'tcklarna_notSetUpCountries',
            array_diff_key($this->_aKlarnaCountries, $this->_aKlarnaCountryCreds) ?: false
        );
        $this->addTplParam('b2options', ['B2C', 'B2B', 'B2C_B2B', 'B2B_B2C']);

        return $this->_sThisTemplate;
    }

    public function save()
    {
        $params = Registry::get(Request::class)->getRequestEscapedParameter('confstrs');

        // Reset footer display setting if user changes klarna mode
        if ($params['sKlarnaActiveMode'] != KlarnaUtils::getShopConfVar('sKlarnaActiveMode')) {
            $oModuleSettingService = $this->getModuleSettings();
            $oModuleSettingService->saveString('sKlarnaFooterDisplay', 0, $this->getModuleForConfigVars());
        }

        parent::save();
    }

    /**
     * @return array|false
     */
    public function getKlarnaCountryCreds()
    {
        if ($this->_aKlarnaCountryCreds) {
            return $this->_aKlarnaCountryCreds;
        }
        $this->_aKlarnaCountryCreds = [];

        foreach ($this->getViewDataElement('confaarrs')['aarrKlarnaCreds'] as $sKey => $serializedArray) {
            $this->_aKlarnaCountryCreds[substr($sKey, -2)] = $serializedArray;
        }

        return $this->_aKlarnaCountryCreds ?: false;
    }

    protected function convertNestedParams($nestedArray)
    {
        $aCountrySpecificCredsConfigKeys = KlarnaUtils::getShopConfVar('aarrKlarnaCreds');

        if (is_array($nestedArray)) {
            foreach ($nestedArray as $key => $arr) {
                if (strpos($key, 'aKlarnaCreds_') === 0) {
                    /*** remove key from the list if present in POST data ***/
                    if(array_key_exists($key, $aCountrySpecificCredsConfigKeys)) {
                        unset($aCountrySpecificCredsConfigKeys[$key]);
                    }
                }
                /*** serialize all assoc arrays ***/
                $nestedArray[$key] = $this->aarrayToMultiline($arr);
            }
        }

        if ($aCountrySpecificCredsConfigKeys) /*** drop all keys that was not passed with POST data ***/ {
            $this->removeConfigKeys('aarrKlarnaCreds', $aCountrySpecificCredsConfigKeys);
        }

        return $nestedArray;
    }

    /**
     * @return mixed
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function getKlarnaCountryAssocList()
    {
        if ($this->_aKlarnaCountries) {
            return $this->_aKlarnaCountries;
        }
        $tableViewNameGenerator = oxNew(TableViewNameGenerator::class);
        $sViewName = $tableViewNameGenerator->getViewName(
            'oxcountry',
            $this->getViewDataElement('adminlang')
        );
        $isoList = KlarnaConsts::getKlarnaCoreCountries();

        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        $oQueryBuilderFactory = $this->getQueryBuilder();
        $oQueryBuilder = $oQueryBuilderFactory->create();
        $oQueryBuilder
            ->select('oxisoalpha2, oxtitle')
            ->from($sViewName, 'c')
            ->where('oxisoalpha2 IN ("' . implode('","', $isoList) . '")')
            ->andWhere('oxactive = :oxactive')
            ->setParameter(':oxactive', 1);
        $aResult = $oQueryBuilder->execute();
        $aResult = $aResult->fetchAllAssociative();

        foreach ($aResult as $aCountry) {
            $this->_aKlarnaCountries[$aCountry['OXISOALPHA2']] = $aCountry['OXTITLE'];
        }

        return $this->_aKlarnaCountries;
    }

    protected function getQueryBuilder() {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        return $oContainer->get(QueryBuilderFactoryInterface::class);
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getModuleSettings() {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }

}