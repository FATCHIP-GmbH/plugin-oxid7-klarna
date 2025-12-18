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

namespace TopConcepts\Klarna\Controller;


use TopConcepts\Klarna\Core\KlarnaConsts;
use TopConcepts\Klarna\Core\KlarnaUtils;
use TopConcepts\Klarna\Model\KlarnaUser;
use OxidEsales\Eshop\Application\Model\CountryList;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;

class KlarnaViewConfig extends KlarnaViewConfig_parent
{
    /**
     * Apex theme ID
     */
    const THEME_ID_APEX = 'apex';

    /**
     * Check if active controller is Klarna Express
     *
     * @return bool
     */

    const TCKLARNA_FOOTER_DISPLAY_NONE = 0;
    const TCKLARNA_FOOTER_DISPLAY_LOGO = 2;

    protected $tcKlarnaButton;

    /**
     * Check if active theme is Apex
     *
     * @return bool
     */
    public function isActiveThemeApex()
    {
        return strcasecmp($this->getActiveTheme(), self::THEME_ID_APEX) === 0;
    }

    /**
     *
     */
    public function getKlarnaFooterContent()
    {
        $sCountryISO = KlarnaUtils::getShopConfVar('sKlarnaDefaultCountry');

        if (!in_array($sCountryISO, KlarnaConsts::getKlarnaCoreCountries())) {
            return false;
        }

        $response = false;
        $klFooter = intval(KlarnaUtils::getShopConfVar('sKlarnaFooterDisplay')->toString());

        if ($klFooter) {
            if ($klFooter === self::TCKLARNA_FOOTER_DISPLAY_LOGO) {
                $sLocale = '';
            } else {
                return false;
            }

            $url = sprintf(KlarnaConsts::getFooterImgUrls(KlarnaUtils::getShopConfVar('sKlarnaFooterValue')->toString()), $sLocale);

            $from = '/' . preg_quote('-', '/') . '/';
            if (KlarnaUtils::getShopConfVar('sKlarnaFooterValue')->toString() != 'logoFooter') {
                $url = preg_replace($from, '_', $url, 1);
            }

            $response = [
                'url' => $url,
                'class' => KlarnaUtils::getShopConfVar('sKlarnaFooterValue')->toString()
            ];
        }

        if (KlarnaUtils::getShopConfVar('sKlarnaMessagingScript')) {
            $response['script'] = KlarnaUtils::getShopConfVar('sKlarnaMessagingScript');
        }

        return $response;
    }

    public function getOnSitePromotionInfo($key, $detailProduct = null)
    {
        if ($this->getActiveClassName() != 'basket' && $key == "sKlarnaCreditPromotionBasket") {
            return '';
        }

        if ($key == "sKlarnaCreditPromotionBasket" || $key == "sKlarnaCreditPromotionProduct") {
            $promotion = KlarnaUtils::getShopConfVar($key);
            $promotion = preg_replace('/data-purchase-amount=\"(\d*)\"/', 'data-purchase-amount="%s"', $promotion);
            $price = 0;
            $productHasPrice = Registry::getConfig()->getConfigParam('bl_perfLoadPrice');
            if ($key == "sKlarnaCreditPromotionProduct" && $detailProduct != null && $productHasPrice) {
                $price = $detailProduct->getPrice()->getBruttoPrice();
                $price = number_format((float)$price * 100., 0, '.', '');
            }

            if ($key == "sKlarnaCreditPromotionBasket") {
                $price = Registry::getSession()->getBasket()->getPrice()->getNettoPrice();
                $price = number_format((float)$price * 100., 0, '.', '');
            }

            return sprintf($promotion, $price);
        }

        return KlarnaUtils::getShopConfVar($key);
    }

    /**
     *
     */
    public function addBuyNow()
    {
        return KlarnaUtils::getShopConfVar('blKlarnaDisplayBuyNow');
    }

    /**
     *
     */
    public function getMode()
    {
        return KlarnaUtils::getShopConfVar('sKlarnaActiveMode');
    }

    /**
     *
     */
    public function isKlarnaPaymentsEnabled()
    {
        return KlarnaUtils::isKlarnaPaymentsEnabled();
    }

    /**
     * @param bool $blShipping
     * @return mixed
     * @throws \OxidEsales\Eshop\Core\Exception\SystemComponentException
     */
    public function getCountryList()
    {
       unset($this->_oCountryList);

       return parent::getCountryList();

    }

    /**
     * @return bool
     */
    public function isUserLoggedIn(): bool
    {
        if ($user = $this->getUser()) {
            return $user->oxuser__oxid->value == Registry::getSession()->getVariable('usr');
        }

        return false;
    }

    /**
     * Confirm present country is Germany
     *
     * @return bool
     */
    public function getIsGermany()
    {
        if ($user = $this->getUser()) {
            $sCountryISO2 = $user->resolveCountry();
        } else {
            $sCountryISO2 = KlarnaUtils::getShopConfVar('sKlarnaDefaultCountry');
        }

        return $sCountryISO2 == 'DE';
    }

    /**
     * Show Checkout terms
     *
     * @return bool true if current country is Austria
     */
    public function getIsAustria()
    {
        /** @var User|KlarnaUser $user */
        if ($user = $this->getUser()) {
            $sCountryISO2 = $user->resolveCountry();
        } else {
            $sCountryISO2 = KlarnaUtils::getShopConfVar('sKlarnaDefaultCountry');
        }

        return $sCountryISO2 == 'AT';
    }

    /**
     *
     */
    public function isShowPrefillNotif()
    {
        return (bool)KlarnaUtils::getShopConfVar('blKlarnaPreFillNotification');
    }

    /**
     *
     */
    public function isPrefillIframe()
    {
        return (bool)KlarnaUtils::getShopConfVar('blKlarnaEnablePreFilling');
    }
}