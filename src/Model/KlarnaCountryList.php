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

namespace TopConcepts\Klarna\Model;


use OxidEsales\Eshop\Core\TableViewNameGenerator;
use TopConcepts\Klarna\Core\KlarnaConsts;

class KlarnaCountryList extends KlarnaCountryList_parent
{
    /**
     * Selects and loads all active countries that are assigned to klarna_checkout
     * loads all active countries if none are assigned
     *
     * @param integer $iLang language
     */
    public function loadActiveKlarnaCheckoutCountries($iLang = null)
    {
        $oTableViewNameGenerator = oxNew(TableViewNameGenerator::class);
        $sViewName = $oTableViewNameGenerator->getViewName('oxcountry', $iLang);

        if (!count($this)) {
            $sSelect = "SELECT {$sViewName}.oxid, {$sViewName}.oxtitle, {$sViewName}.oxisoalpha2 
                        FROM {$sViewName}
                        WHERE {$sViewName}.oxactive=1";

            $this->selectString($sSelect);
        }
    }

    public function getKalarnaCountriesTitles($iLang, $isoList)
    {
        $oTableViewNameGenerator = oxNew(TableViewNameGenerator::class);
        $sViewName = $oTableViewNameGenerator->getViewName('oxcountry', $iLang);
        $sSelect = "SELECT {$sViewName}.oxisoalpha2, {$sViewName}.oxtitle FROM {$sViewName}
            WHERE {$sViewName}.oxisoalpha2 IN ('" . implode("','", $isoList) . "')";

        $this->selectString($sSelect);
        $result = [];
        foreach ($this as $country) {
            $result[$country->oxcountry__oxisoalpha2->value] = $country->oxcountry__oxtitle->value;
        }

        return $result;
    }

    public function loadActiveKlarnaCountriesByPaymentId($paymentId)
    {
        $paymentId = "'" . $paymentId . "'";
        $oTableViewNameGenerator = oxNew(TableViewNameGenerator::class);
        $sViewName = $oTableViewNameGenerator->getViewName('oxcountry');
        $isoList = KlarnaConsts::getKlarnaGlobalCountries();
        $isoList = implode("','", $isoList);
        $sSelect = "SELECT {$sViewName}.oxid, {$sViewName}.oxtitle, {$sViewName}.oxisoalpha2 FROM {$sViewName}
                      JOIN oxobject2payment 
                      ON oxobject2payment.oxobjectid = {$sViewName}.oxid
                      WHERE oxobject2payment.oxpaymentid = {$paymentId}
                      AND oxobject2payment.oxtype = 'oxcountry'
                      AND {$sViewName}.oxactive=1";

        $sSelect .= " AND {$sViewName}.oxisoalpha2 IN ('{$isoList}')";

        $this->selectString($sSelect);
    }
}