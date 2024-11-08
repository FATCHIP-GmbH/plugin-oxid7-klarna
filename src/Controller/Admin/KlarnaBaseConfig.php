<?php

namespace TopConcepts\Klarna\Controller\Admin;


use TopConcepts\Klarna\Core\KlarnaConsts;
use TopConcepts\Klarna\Core\KlarnaUtils;
use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;

class KlarnaBaseConfig extends ShopConfiguration
{
    /**
     * Request parameter container
     *
     * @var array
     */
    protected $_aParameters = [];

    /**
     * @var Request
     */
    protected $_oRequest;

    /**
     * Keys with these prefixes will be deleted on save operation if not present in the POST array
     * Assign prefixes in final controller class
     * @var array
     */
    protected $_collectionsPrefixes = [];

    /** @var array klarna multilang config vars */
    protected $MLVars = [];

    /**
     * Sets parameter
     *
     * @param $sName
     * @param $sValue
     */
    public function setParameter($sName, $sValue)
    {
        $this->_aParameters[$sName] = $sValue;
    }

    /**
     * Return parameter from container
     *
     * @param $sName
     * @return string
     */
    public function getParameter($sName)
    {
        return $this->_aParameters[$sName];
    }

    public function init()
    {
        parent::init();
        $this->_oRequest = Registry::get(Request::class);
    }

    public function render()
    {
        parent::render();
        $confaarrs = $this->getViewDataElement('confaarrs');

        foreach ($confaarrs as $key => $arr) {
            $confaarrs[$key] = $this->multilineToAarray(html_entity_decode($arr));
        }
        $this->addTplParam('confaarrs', $confaarrs);

        $this->addTplParam(
            'confaarrs',
            KlarnaUtils::getModuleSettingsAarrs($this->getViewDataElement('confaarrs'))
        );
        $this->addTplParam(
            'confbools',
            KlarnaUtils::getModuleSettingsBools($this->getViewDataElement('confbools'))
        );
        $this->addTplParam(
            'confstrs',
            KlarnaUtils::getModuleSettingsStrs($this->getViewDataElement('confstrs'))
        );
    }

    /**
     * Save configuration values
     *
     * @return void
     * @throws \Exception
     */
    public function save()
    {
        // Save parameters to container
        $this->fillContainer();
        $this->doSave();
    }

    /**
     * Fill parameter container with request values
     */
    protected function fillContainer()
    {
        foreach ($this->_aConfParams as $sType => $sParam) {
            if ($sType === 'aarr') {
                $this->setParameter(
                    $sParam,
                    $this->convertNestedParams(
                        Registry::get(Request::class)->getRequestEscapedParameter($sParam)
                    )
                );
            } else {
                $this->setParameter($sParam, Registry::get(Request::class)->getRequestEscapedParameter($sParam));
            }
        }
    }

    /**
     * @param $nestedArray
     * @return array
     */
    protected function convertNestedParams($nestedArray)
    {
        if (is_array($nestedArray)) {
            foreach ($nestedArray as $key => $arr) {
                /*** serialize all assoc arrays ***/
                $nestedArray[$key] = $this->aarrayToMultiline($arr);
            }
        }

        return $nestedArray;
    }

    /**
     * @param string $sModulSettingsName
     * @param array $aKeys
     * @return int
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     * @codeCoverageIgnore
     */
    protected function removeConfigKeys($sModulSettingsName, $aKeys)
    {
        $aTmpModulSettings = KlarnaUtils::getShopConfVar($sModulSettingsName);
        foreach ($aTmpModulSettings as $key => $value) {
            if(array_key_exists($key, $aKeys)) {
                unset($aTmpModulSettings[$key]);
            }
        }

        KlarnaUtils::saveShopConfVar($sModulSettingsName, $aTmpModulSettings);
    }

    /**
     * Save vars as shop config does
     * @throws \Exception
     */
    private function doSave()
    {
        $this->performConfVarsSave();
        $sOxid = $this->getEditObjectId();

        //saving additional fields ("oxshops__oxdefcat") that goes directly to shop (not config)
        /** @var Shop $oShop */
        $oShop = oxNew(Shop::class);
        if ($oShop->load($sOxid)) {
            $oShop->assign(Registry::get(Request::class)->getRequestEscapedParameter("editval"));
            $oShop->save();
        }
    }

    /**
     * Shop config variable saving
     */
    private function performConfVarsSave()
    {
        $this->resetContentCache();
        foreach ($this->_aConfParams as $sType => $sParam) {
            $aConfVars = $this->getParameter($sParam);

            if (!is_array($aConfVars)) {
                continue;
            }

            $this->performConfVarsSave2($sType, $aConfVars);
        }
    }

    /**
     * Save config parameter
     *
     * @param $sConfigType
     * @param $aConfVars
     */
    protected function performConfVarsSave2($sConfigType, $aConfVars)
    {
        foreach ($aConfVars as $sName => $sValue) {
            if (str_contains($sName, '_')) {
                $aName = explode("_", $sName);
                //Change to ModuleSettingType associative array (aarr)
                $aName[0] = 'aarr' . substr($aName[0], 1);
                $aModulSettingVar = KlarnaUtils::getShopConfVar($aName[0]);
                $aModulSettingVar[$sName] = $this->serializeConfVar($sConfigType, $sName, $sValue);
                KlarnaUtils::saveShopConfVar($aName[0], $aModulSettingVar);
            } else {
                KlarnaUtils::saveShopConfVar($sName, $this->serializeConfVar($sConfigType, $sName, $sValue));
            }
        }
    }

    /**
     * @return string
     */
    protected function getModuleForConfigVars()
    {
        return 'tcklarna';
    }

    /**
     * @return \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getAllActiveOxPaymentIds()
    {
        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        $oQueryBuilderFactory = $this->getQueryBuilder();
        $oQueryBuilder = $oQueryBuilderFactory->create();
        $oQueryBuilder
            ->select('oxid')
            ->from('oxpayments')
            ->where('oxactive = 1')
            ->andWhere('oxid != :oxid')
            ->setParameter(':oxid', 'oxempty');
        $aResult = $oQueryBuilder->execute();
        $aResult = $aResult->fetchAllAssociative();

        return $aResult;
    }

    /**
     * @param string $oxid
     * @param bool|int $lang
     * @return mixed
     * @throws oxSystemComponentException
     */
    public function getPaymentData($oxid, $lang = false)
    {
        $lang = $lang !== false ? $lang : $this->getViewDataElement('adminlang');
        $oxpayment = oxnew(Payment::class);
        $oxpayment->loadInLang($lang, $oxid);

        $result['oxid'] = $oxid;
        $result['desc'] = $oxpayment->oxpayments__oxdesc->value;
        $result['tcklarna_externalname'] = $oxpayment->oxpayments__tcklarna_externalname->value;
        $result['tcklarna_externalcheckout'] = $oxpayment->oxpayments__tcklarna_externalcheckout->value;
        $result['tcklarna_paymentimageurl'] = $oxpayment->oxpayments__tcklarna_paymentimageurl->value;
        $result['tcklarna_paymentoption'] = $oxpayment->oxpayments__tcklarna_paymentoption->value;
        $result['tcklarna_emdpurchasehistoryfull'] = $oxpayment->oxpayments__tcklarna_emdpurchasehistoryfull->value;
        $result['isCheckout'] = preg_match('/([pP]ay[pP]al|[Aa]mazon)/', $result['desc']) == 1;

        return $result;
    }

    /**
     * @return string
     */
    protected function getActiveKlarnaMode()
    {
        return KlarnaUtils::getShopConfVar('sKlarnaActiveMode');
    }


    /**
     * @return string
     */
    public function getManualDownloadLink()
    {
        $langTag = Registry::getLang()->getLanguageAbbr($this->getViewDataElement('adminlang'));
        $oModuleConfiguration = $this->getModuleConfigs('tcklarna');
        $version = '4.0.0';
        if ($oModuleConfiguration->getVersion()) {
            $version = $oModuleConfiguration->getVersion();
        }

        return sprintf(KlarnaConsts::KLARNA_MANUAL_DOWNLOAD_LINK, $langTag, $version);
    }

    public function getLangs()
    {
        return htmlentities(
            json_encode(
                Registry::getLang()->getLanguageArray()
            )
        );
    }

    public function getFlippedLangArray()
    {
        $aLangs = Registry::getLang()->getLanguageArray();

        $return = [];
        foreach ($aLangs as $oLang) {
            $return[$oLang->abbr] = $oLang;
        }

        return $return;
    }

    protected function getMultiLangData()
    {
        $output = [];

        foreach ($this->MLVars as $fieldName) {
            foreach ($this->getViewDataElement('confstrs') as $name => $value) {
                if (strpos($name, $fieldName) === 0) {
                    $output['confstrs[' . $name . ']'] = $value;
                }
            }
        }

        return $output;
    }

    /**
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getQueryBuilder() {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        return $oContainer->get(QueryBuilderFactoryInterface::class);
    }

    /**
     * @param string $moduleId
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getModuleConfigs(string $moduleId)
    {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        return $oContainer->get(ModuleConfigurationDaoBridgeInterface::class)->get($moduleId);
    }

}