<?php

namespace TopConcepts\Klarna\Controller\Admin;


use OxidEsales\Eshop\Application\Model\CountryList;
use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;

/**
 * Class Klarna_Config for module configuration in OXID backend
 */
class KlarnaStart extends KlarnaBaseConfig
{

    protected $_sThisTemplate = '@tcklarna/admin/tcklarna_start';

    /**
     * Render logic
     *
     * @return string
     * @see admin/oxAdminDetails::render()
     */
    public function render()
    {
        // force shopid as parameter
        // Pass shop OXID so that shop object could be loaded
        $sShopOXID = Registry::getConfig()->getShopId();

        $this->setEditObjectId($sShopOXID);

        parent::render();
        $oCountryList = oxNew(CountryList::class);
        $countries = ['DE', 'GB', 'AT', 'NO', 'NL', 'FI', 'SE', 'DK'];
        $oSupportedCountryList = $oCountryList->getKalarnaCountriesTitles(
            $this->getViewDataElement('adminlang'),
            $countries
        );

        $this->addTplParam('countries', $oSupportedCountryList);


        return $this->_sThisTemplate;
    }

    /**
     * @return string
     */
    public function getKlarnaModuleInfo()
    {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        $oContainer->get(ShopConfigurationDaoBridgeInterface::class)->get();
        $oModule = $oContainer->getModuleConfiguration('tcklarna');
        $version = $oModule->getVersion();

        return " VERSION " . $version;
    }
}