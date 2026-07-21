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
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

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

    public function getKlarnaCountriesTitles($iLang)
    {
        $tableViewNameGenerator = oxNew(TableViewNameGenerator::class);
        $sViewName = $tableViewNameGenerator->getViewName(
            'oxcountry',
            $iLang
        );
        $isoList = KlarnaConsts::getKlarnaCoreCountries();

        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        $oQueryBuilderFactory = $this->getQueryBuilder();
        $oQueryBuilder = $oQueryBuilderFactory->create();
        $oQueryBuilder
            ->select('oxisoalpha2, oxtitle')
            ->from($sViewName, 'c')
            ->where('oxisoalpha2 IN ("' . implode('","', $isoList) . '")');
        $aResult = $oQueryBuilder->execute();
        $aResult = $aResult->fetchAllAssociative();

        foreach ($aResult as $aCountry) {
            $aKlarnaCountries[$aCountry['OXISOALPHA2']] = $aCountry['OXTITLE'];
        }

        return $aKlarnaCountries;
    }

    protected function getQueryBuilder() {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
        return $oContainer->get(QueryBuilderFactoryInterface::class);
    }
}