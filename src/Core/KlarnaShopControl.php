<?php


namespace TopConcepts\Klarna\Core;

use OxidEsales\Eshop\Application\Model\User;
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

    protected function executeAction($view, $functionName)
    {
        // Logout and delete fake user if user is KEX fake-user and if customer leaves checkout
        if (
            ($userToDeleteFakeId = Registry::getSession()->getVariable("kexFakeUserId")) &&
            !$this->isCheckoutController()
        ) {
            $userToDelete = oxNew(User::class);
            $userToDelete->load($userToDeleteFakeId);
            $userToDelete->delete();

            KlarnaUtils::fullyResetKlarnaSession();
        }

        parent::executeAction($view, $functionName);
    }

    protected function isCheckoutController(): bool
    {
        $sController = Registry::getConfig()->getTopActiveView()->getClassKey();

        return in_array($sController, [
            'klarnaexpress',
            'order',
            'thankyou',
            'ajaxpay',
            'oscpaypalproxy',
            'makaira_connect_endpoint',
            'tpl',
            'oepaypalstandarddispatcher',
            'content'
        ]);
    }
}