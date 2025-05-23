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

use OxidEsales\Eshop\Application\Model\DeliveryList;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\EshopCommunity\Core\Exception\SystemComponentException;
use TopConcepts\Klarna\Controller\Admin\KlarnaShipping;
use TopConcepts\Klarna\Model\EmdPayload\KlarnaPassThrough;
use TopConcepts\Klarna\Model\KlarnaEMD;
use TopConcepts\Klarna\Model\KlarnaUser;
use OxidEsales\Eshop\Application\Controller\PaymentController;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Application\Model\CountryList;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Application\Model\PaymentList;

class KlarnaOrder extends BaseModel
{
    const PACK_STATION_PREFIX = 'tcklarna_pack_station_';
    /**
     * @var array data to post to Klarna
     */
    protected $_aOrderData;

    /**
     *
     * @var User|KlarnaUser
     */
    protected $_oUser;

    /**
     * @var PaymentController
     */
    protected $_oPayment;

    /**
     * @var string
     */
    protected $_selectedShippingSetId;

    /** @var array Order error messages to display to the user */
    protected $errors;

    /** @var boolean KCO allowed for b2b clients */
    protected $b2bAllowed;

    /** @var boolean KCO allowed for b2c clients */
    protected $b2cAllowed;

    /** @var string */
    protected $activeB2Option;

    protected $_aUserData;
    protected $_klarnaCountryList;

    /**
     * @return array
     */
    public function getOrderData()
    {
        return $this->_aOrderData;
    }

    /**
     * KlarnaOrder constructor.
     * @param Basket $oBasket
     * @param User $oUser
     * @throws SystemComponentException
     */
    public function __construct(Basket $oBasket, User $oUser)
    {
        parent::__construct();
        $this->_oUser = $oUser;
        $oConfig = Registry::getConfig();
        $urlShopParam = method_exists($oConfig, 'mustAddShopIdToRequest')
        && $oConfig->mustAddShopIdToRequest()
            ? '&shp=' . $oConfig->getShopId()
            : '';
        $sSSLShopURL = $oConfig->getSslShopUrl();
        $sCountryISO = $this->_oUser->resolveCountry();
        $this->resolveB2Options($sCountryISO);
        $currencyName = $oBasket->getBasketCurrency()->name;
        $sLocale = $this->_oUser->resolveLocale($sCountryISO);
        $lang = strtoupper(Registry::getLang()->getLanguageAbbr());
        $this->_aUserData = $this->_oUser->getKlarnaData();
        $cancellationTerms = KlarnaUtils::getShopConfVar('aarrKlarnaCancellationRightsURI')['sKlarnaCancellationRightsURI_' . $lang];
        $terms = KlarnaUtils::getShopConfVar('aarrKlarnaTermsConditionsURI')['sKlarnaTermsConditionsURI_' . $lang];

        if (empty($cancellationTerms) || empty($terms)) {
            Registry::getSession()->setVariable('wrong_merchant_urls', true);

            return false;
        }

        $sGetChallenge = Registry::getSession()->getSessionChallengeToken();
        $sessionId = Registry::getSession()->getId();
        $this->_aOrderData = [
            "purchase_country" => $sCountryISO,
            "purchase_currency" => $currencyName,
            "locale" => $sLocale,
            "merchant_urls" => [
                "terms" =>
                    $terms,
                "confirmation" =>
                    $sSSLShopURL . "?cl=order$urlShopParam&fnc=execute&klarna_order_id={checkout.order.id}&stoken=$sGetChallenge",
                "push" =>
                    $sSSLShopURL . "?cl=KlarnaAcknowledge$urlShopParam&klarna_order_id={checkout.order.id}",

            ],
        ];

        if ($this->isValidationEnabled()) {
            $this->_aOrderData["merchant_urls"]["validation"] =
                $sSSLShopURL . "?cl=KlarnaValidate&s=$sessionId";
        }

        if (!empty($cancellationTerms)) {
            $this->_aOrderData["merchant_urls"]["cancellation_terms"] = $cancellationTerms;
        }

        $this->_aOrderData = array_merge(
            $this->_aOrderData,
            $this->_aUserData
        );

        // skip all other data if there are no items in the basket
        if (!empty($this->_aOrderData['order_lines'])) {
            $this->_aOrderData['billing_countries'] = array_values($this->getKlarnaCountryList());
            $allowSeperateDel = (bool)KlarnaUtils::getShopConfVar('blKlarnaAllowSeparateDeliveryAddress');
            if ($allowSeperateDel === true) {
                $this->_aOrderData['shipping_countries'] = array_values($this->getShippingCountries($oBasket));
            }

            $this->_aOrderData['shipping_options'] = $this->tcklarna_getAllSets($oBasket);

            $externalMethods = $this->getExternalPaymentMethods($oBasket, $this->_oUser);

            $this->_aOrderData['external_payment_methods'] = $externalMethods['payments'];
            $this->_aOrderData['external_checkouts'] = $externalMethods['checkouts'];

            $this->addOptions();

            if (!$this->isAutofocusEnabled()) {
                $this->_aOrderData['gui']['options'] = [
                    'disable_autofocus',
                ];
            }
            $this->setCustomerData();
            $this->setAttachmentsData();
            $this->setPassThroughField();
            $this->validateKlarnaB2B();
        }
    }

    /**
     * Checks if specific fields in billing and shipping address have the same values
     */
    public function validateKlarnaB2B()
    {
        if ($this->_aUserData['billing_address']['organization_name'] && !$this->b2bAllowed) {
            $this->addErrorMessage('KP_NOT_AVAILABLE_FOR_COMPANIES');
        }

        if (empty($this->_aUserData['billing_address']['organization_name']) && !$this->b2cAllowed) {
            $this->addErrorMessage('KP_AVAILABLE_FOR_COMPANIES_ONLY');
        }
    }

    /** Passes internal errors to oxid in order to display theme to the user */
    public function displayErrors()
    {
        foreach ($this->errors as $message) {
            Registry::get(UtilsView::class)->addErrorToDisplay($message);
        }
    }

    /** Adds Error message in current language
     * @param $translationKey string message key
     */
    public function addErrorMessage($translationKey)
    {
        $message = Registry::getLang()->translateString($translationKey);
        $this->errors[$translationKey] = $message;
    }

    /**
     * @param $sCountryISO
     */
    protected function resolveB2Options($sCountryISO)
    {
        $this->b2bAllowed = false;
        $this->b2cAllowed = true;
        $this->activeB2Option = KlarnaUtils::getShopConfVar('sKlarnaB2Option')->toString();

        if ($this->activeB2Option === 'B2B') {
            $this->b2cAllowed = false;
        }
    }

    protected function setCustomerData()
    {
        $append = [];
        $typeList = KlarnaConsts::getCustomerTypes();
        $type = $typeList[$this->activeB2Option];
        if ($this->b2bAllowed && empty($this->_aUserData['billing_address']['organization_name']) === false) {
            $append['customer']['type'] = 'organization';
        } else {
            $append['customer']['type'] = reset($type);
        }
        $this->_aOrderData = array_merge_recursive($this->_aOrderData, $append);
    }

    /**
     * @param $oBasket
     * @return array
     */
    protected function getShippingCountries($oBasket)
    {
        $list = $this->tcklarna_getAllSets($oBasket);
        $aCountries = $this->getKlarnaCountryList();
        $oDelList = Registry::get(DeliveryList::class);
        $shippingCountries = [];
        foreach ($list as $l) {
            $sShipSetId = $l['id'];
            foreach ($aCountries as $sCountryId => $alpha2) {
                if ($oDelList->hasDeliveries($oBasket, $this->_oUser, $sCountryId, $sShipSetId)) {
                    $shippingCountries[$alpha2] = $alpha2;
                }
            }
        }

        return $shippingCountries;
    }

    /**
     * Creates new payment object
     *
     * @return null|object
     */
    protected function getPayment()
    {
        if ($this->_oPayment === null) {
            $this->_oPayment = oxNew(PaymentController::class);
        }

        return $this->_oPayment;
    }

    /**
     *
     */
    public function getKlarnaCountryList()
    {
        if ($this->_klarnaCountryList === null) {
            $this->_klarnaCountryList = [];
            $oCountryList = oxNew(CountryList::class);
            $oCountryList->loadActiveKlarnaCheckoutCountries();
            foreach ($oCountryList as $oCountry) {
                $this->_klarnaCountryList[$oCountry->oxcountry__oxid->value] = $oCountry->oxcountry__oxisoalpha2->value;
            }
        }

        return $this->_klarnaCountryList;
    }

    /**
     * Gets an array of all countries the given payment type can be used in.
     *
     * @param Payment $oPayment
     * @param $aActiveCountries
     * @return array
     */
    public function getKlarnaCountryListByPayment(Payment $oPayment, $aActiveCountries)
    {
        $result = [];
        $aPaymentCountries = $oPayment->getCountries();
        foreach ($aPaymentCountries as $oxid) {
            if (isset($aActiveCountries[$oxid])) {
                $result[] = $aActiveCountries[$oxid];
            }
        }

        return empty($result) ? array_values($aActiveCountries) : $result;
    }

    /**
     *
     */
    public function addOptions()
    {
        $options = [];

        $options['additional_checkbox'] = $this->getAdditionalCheckboxData();
        $options['allow_separate_shipping_address'] = $this->isSeparateDeliveryAddressAllowed();
        $options['phone_mandatory'] = $this->isPhoneMandatory();
        $options['date_of_birth_mandatory'] = $this->isBirthDateMandatory();
        $options['require_validate_callback_success'] = $this->isValidateCallbackSuccessRequired();
        $options['shipping_details'] =
            $this->getShippingDetailsMsg();


        /*** add design settings ***/
        if (!$designSettings = KlarnaUtils::getShopConfVar('aKlarnaDesign')) {
            $designSettings = [];
        }

        $typeList = KlarnaConsts::getCustomerTypes();
        
        $type = $typeList[$this->activeB2Option];
        $options['allowed_customer_types'] = $type;

        $options = array_merge($options, $designSettings);

        $this->_aOrderData['options'] = $options;
    }

    /**
     * @return bool
     */
    public function isAutofocusEnabled()
    {
        return KlarnaUtils::getShopConfVar('blKlarnaEnableAutofocus');
    }

    /**
     * @return string
     */
    public function getShippingDetailsMsg()
    {
        $langTag = strtoupper(Registry::getLang()->getLanguageAbbr());

        return KlarnaUtils::getShopConfVar('aarrKlarnaShippingDetails')['sKlarnaShippingDetails_' . $langTag];
    }

    /**
     * @return int
     * @throws \oxSystemComponentException
     */
    protected function getAdditionalCheckbox()
    {
        $iActiveCheckbox = KlarnaUtils::getShopConfVar('iKlarnaActiveCheckbox');

        $type = $this->_oUser->getType();
        if ($type === KlarnaUser::LOGGED_IN || $type === KlarnaUser::REGISTERED) {
            if ($this->_oUser->getNewsSubscription()->getOptInStatus() == 1) {
                return KlarnaConsts::EXTRA_CHECKBOX_NONE;
            }
            if ($iActiveCheckbox > KlarnaConsts::EXTRA_CHECKBOX_CREATE_USER) {
                return KlarnaConsts::EXTRA_CHECKBOX_SIGN_UP;
            }

            return KlarnaConsts::EXTRA_CHECKBOX_NONE;
        }

        return (int)$iActiveCheckbox;
    }

    protected function setAttachmentsData()
    {
        if (!$this->_oUser->isFake()) {
            $emd = $this->getEmd();

            if (!empty($emd)) {
                $this->_aOrderData['attachment'] = [
                    'content_type' => 'application/vnd.klarna.internal.emd-v2+json',
                    'body' => json_encode($emd),
                ];
            }
        }
    }

    /**
     * @return array
     */
    protected function getEmd()
    {
        /** @var KlarnaEMD $klarnaEmd */
        $klarnaEmd = oxNew(KlarnaEMD::class);
        $emd = $klarnaEmd->getAttachments($this->_oUser);

        return $emd;
    }

    /**
     * @return mixed
     */
    protected function isSeparateDeliveryAddressAllowed()
    {
        return (bool)KlarnaUtils::getShopConfVar('blKlarnaAllowSeparateDeliveryAddress');
    }

    /**
     * Check if user already has an account and if he's subscribed to the newsletter
     * Don't add the extra checkbox if not needed.
     */
    protected function getAdditionalCheckboxData()
    {
        $activeCheckbox = $this->getAdditionalCheckbox();

        switch ($activeCheckbox) {
            case 1:
                return [
                    'text' => Registry::getLang()->translateString('TCKLARNA_CREATE_USER_ACCOUNT'),
                    'checked' => false,
                    'required' => false,
                ];
            case 2:
                return [
                    'text' => Registry::getLang()->translateString('TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'),
                    'checked' => false,
                    'required' => false,
                ];
            case 3:
                return [
                    'text' => Registry::getLang()->translateString('TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'),
                    'checked' => false,
                    'required' => false,
                ];
            default:
                return null;
        }
    }

    /**
     * @return bool
     */
    protected function isPhoneMandatory()
    {
        return KlarnaUtils::getShopConfVar('blKlarnaMandatoryPhone');
    }

    /**
     * @return bool
     */
    protected function isBirthDateMandatory()
    {
        return KlarnaUtils::getShopConfVar('blKlarnaMandatoryBirthDate');
    }

    /**
     * @return bool
     */
    protected function isValidateCallbackSuccessRequired()
    {
        return KlarnaUtils::getShopConfVar('iKlarnaValidation') == 2;
    }

    /**
     * @return bool
     */
    protected function isValidationEnabled()
    {
        return KlarnaUtils::getShopConfVar('iKlarnaValidation') != 0;
    }

    /**
     * @param $oPayment
     * @param bool $checkoutImgUrl
     * @return mixed
     */
    protected function resolveImageUrl($oPayment)
    {
        $url = $oPayment->oxpayments__tcklarna_paymentimageurl->value;

        $result = preg_replace('/http:/', 'https:', $url);

        return $result ?: null;
    }

    /**
     *
     */
    protected function setPassThroughField()
    {
        $oKlarnaPassThrough = new KlarnaPassThrough();
        $data = $oKlarnaPassThrough->getPassThroughField();
        if (!empty($data)) {
            $this->_aOrderData['merchant_data'] = $data;
        }
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return (bool)$this->errors;
    }
}