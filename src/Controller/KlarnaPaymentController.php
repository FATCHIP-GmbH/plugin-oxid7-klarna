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


use OxidEsales\Eshop\Core\UtilsView;
use TopConcepts\Klarna\Core\Exception\KlarnaWrongCredentialsException;
use TopConcepts\Klarna\Core\KlarnaPaymentsClient;
use TopConcepts\Klarna\Core\Exception\KlarnaClientException;
use TopConcepts\Klarna\Model\KlarnaPayment as KlarnaPaymentModel;
use TopConcepts\Klarna\Core\KlarnaConsts;
use TopConcepts\Klarna\Core\KlarnaPayment;
use TopConcepts\Klarna\Core\KlarnaUtils;
use TopConcepts\Klarna\Model\KlarnaPaymentHelper;
use TopConcepts\Klarna\Model\KlarnaUser;
use OxidEsales\Eshop\Application\Model\DeliverySetList;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class KlarnaPaymentController extends KlarnaPaymentController_parent
{
    public $loadKlarnaPaymentWidget = true;

    /**
     * @var array of available payment methods
     * Added for performance optimization
     */
    protected $aPaymentList;

    /**
     * @var \TopConcepts\Klarna\Core\KlarnaPayment
     */
    protected $oKlarnaPayment;

    /** @var Request */
    protected $oRequest;

    /**
     * @var string
     * CountryISO assigned to the user
     */
    protected $userCountryISO;


    protected $client;

    /**
     *
     * @throws \OxidEsales\EshopCommunity\Core\Exception\SystemComponentException
     */
    public function init()
    {
        $this->oRequest = Registry::get(Request::class);

        if ($oUser = $this->getUser()) {
            $this->userCountryISO = KlarnaUtils::getCountryISO($oUser->getFieldData('oxcountryid'));
        }
        if (Registry::getSession()->getVariable('amazonOrderReferenceId')) {
            $this->loadKlarnaPaymentWidget = false;
        }

        if (!$this->client) {
            $this->client = KlarnaPaymentsClient::getInstance($this->userCountryISO); // @codeCoverageIgnore
        }

        parent::init();
    }

    /**
     * @return string
     * @throws \ReflectionException
     * @throws \oxSystemComponentException
     * @throws \OxidEsales\EshopCommunity\Core\Exception\SystemComponentException
     */
    public function render()
    {
        $sTplName = parent::render();

        if ($this->countKPMethods() && $this->loadKlarnaPaymentWidget) {
            /** @var User|KlarnaUser $oUser */
            $oUser = $this->getUser();
            $oSession = Registry::getSession();
            $oBasket = $oSession->getBasket();

            if (KlarnaPayment::countryWasChanged($oUser)) {
                KlarnaPayment::cleanUpSession();
            }

            $this->oKlarnaPayment = new KlarnaPayment($oBasket, $oUser);
            if (!$this->oKlarnaPayment->isSessionValid()) {
                KlarnaPayment::cleanUpSession();
            }

            $errors = $this->oKlarnaPayment->getError();
            if (!$errors) {
                try {
                    $this->client->initOrder($this->oKlarnaPayment)
                        ->createOrUpdateSession();

                    $sessionData = $oSession->getVariable('klarna_session_data');
                    $tcKlarnaIsB2B = $this->oKlarnaPayment->isB2B() ? 'true' : 'false';

                    $this->addTplParam("client_token", $sessionData['client_token']);
                    $this->addTplParam("tcKlarnaIsB2B", $tcKlarnaIsB2B);

                    // update KP options, remove unavailable klarna payments
                    $this->removeUnavailableKP($sessionData);
                } catch (KlarnaWrongCredentialsException $oEx) {
                    $this->removeUnavailableKP();
                    KlarnaUtils::fullyResetKlarnaSession();
                    Registry::get(UtilsView::class)->addErrorToDisplay(
                        Registry::getLang()->translateString('KLARNA_UNAUTHORIZED_REQUEST', null, true)
                    );

                    return $sTplName;
                } catch (KlarnaClientException $e) {
                    KlarnaUtils::logException($e);

                    return $sTplName;
                }
            } else {
                // show only first
                $this->addTplParam("kpError", $errors[0]);
                $this->addTplParam("tcKlarnaIsB2B", 'false');
            }

            $from = '/' . preg_quote('-', '/') . '/';
            $locale = preg_replace($from, '_', strtolower(KlarnaConsts::getLocale(true)), 1);

            $this->addTplParam("sLocale", $locale);
        }

        if ($this->countKPMethods() === 0) {
            $this->loadKlarnaPaymentWidget = false;
        }

        return $sTplName;
    }

    /**
     * @return array
     */
    public function getPaymentList()
    {
        /**
         * This method is called in the template
         * We will remove some methods in the render method after getting payment_method_categories
         * This will pass modified list to the template
         */
        if (!$this->aPaymentList) {
            $this->aPaymentList = $this->tckl_getPaymentListParent();
            $allKlarnaPaymentIds = KlarnaPaymentModel::getKlarnaPaymentsIds();
            $toRemove = $allKlarnaPaymentIds;
            if (KlarnaUtils::isKlarnaPaymentsEnabled()) {
                //If One Klarna is Active, no other KP Payments should be displayed
                if (KlarnaUtils::getIsOneKlarnaActive()) {
                    $KPPaymentIds = [KlarnaPaymentHelper::KLARNA_PAYMENT_ID];
                } else {
                    $KPPaymentIds = KlarnaPaymentModel::getKlarnaPaymentsIds('KP');
                }

                $toRemove = array_diff($allKlarnaPaymentIds, $KPPaymentIds);
            }
            foreach ($toRemove as $paymentId) {
                unset($this->aPaymentList[$paymentId]);
            }
        }

        return $this->aPaymentList;
    }

    /**
     * Unit test wrapper
     * @codeCoverageIgnore
     */
    protected function tckl_getPaymentListParent()
    {
        return parent::getPaymentList();
    }

    /** Saves Klarna Payment authorization_token or deleting an existing authorization
     * if payment method was changed to not KP method
     * @return null
     */
    public function validatepayment()
    {
        if (!$sControllerName = parent::validatepayment())
            return null;

        if (KlarnaUtils::isKlarnaPaymentsEnabled()) {
            $oSession = Registry::getSession();
            $oBasket = $oSession->getBasket();
            $sPaymentId = $oBasket->getPaymentId();
            $aKlarnaPaymentMethods = KlarnaPaymentModel::getKlarnaPaymentsIds('KP');

            if (in_array($sPaymentId, $aKlarnaPaymentMethods)) {

                if ($this->oRequest->getRequestEscapedParameter('finalizeRequired')) {
                    Registry::getSession()->setVariable('finalizeRequired', true);
                }

                if ($sAuthToken = $this->oRequest->getRequestEscapedParameter('sAuthToken')) {
                    Registry::getSession()->setVariable('sAuthToken', $sAuthToken);
                    $dt = new \DateTime();
                    Registry::getSession()->setVariable('sTokenTimeStamp', $dt->getTimestamp());
                }

                $this->oKlarnaPayment = new KlarnaPayment($oBasket, $this->getUser());

                if (!$this->oKlarnaPayment->isAuthorized() || $this->oKlarnaPayment->isError()) {
                    return null;
                }
            } else {
                KlarnaPayment::cleanUpSession();
            }
        }

        return $sControllerName;
    }

    /**
     * Removes unavailable Klarna Payments Categories
     * Render only methods recieved in the create session response
     *
     * @param $sessionData array create session response
     */
    protected function removeUnavailableKP($sessionData = false)
    {
        $klarnaIds = [];
        if ($sessionData && $sessionData['payment_method_categories']) {
            $klarnaIds = array_map(function ($element) {
                return $element['identifier'];
            },
                $sessionData['payment_method_categories']
            );
        }

        foreach ($this->aPaymentList as $payid => $oxPayment) {
            $klarnaName = $oxPayment->getPaymentCategoryName();
            if ($klarnaName && !in_array($klarnaName, $klarnaIds)) {
                unset($this->aPaymentList[$payid]);
            }
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function removeKlarnaPrefix($name)
    {
        return str_replace('Klarna ', '', $name);
    }


    /**
     * @return int
     */
    protected function countKPMethods()
    {
        $counter = 0;
        foreach ($this->aPaymentList as $payid => $oxPayment) {
            if ($oxPayment->isKPPayment()) {
                $counter++;
            }
        }

        return $counter;
    }

    /**
     * @return int
     */
    public function includeKPWidget()
    {
        return $this->countKPMethods();
    }
}