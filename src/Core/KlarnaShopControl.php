<?php


namespace TopConcepts\Klarna\Core;


use OxidEsales\Eshop\Core\Registry;

class KlarnaShopControl extends KlarnaShopControl_parent
{

    protected function initializeViewObject($sClass, $sFunction, $aParams = null, $aViewsChain = null)
    {
        // detect paypal button clicks
        $searchTerm = 'paypalExpressCheckoutButton';
        $found = array_filter(array_keys($_REQUEST), function ($paramName) use ($searchTerm) {
            return strpos($paramName, $searchTerm) !== false;
        });

        return parent::initializeViewObject($sClass, $sFunction, $aParams, $aViewsChain);
    }
}