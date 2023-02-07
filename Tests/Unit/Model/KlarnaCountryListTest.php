<?php

namespace TopConcepts\Klarna\Tests\Unit\Model;


use TopConcepts\Klarna\Model\KlarnaCountryList;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class KlarnaCountryListTest extends ModuleUnitTestCase
{

    /**
     * @param $data
     */
    public function testLoadActiveKCOGlobalCountries()
    {
        $expectedCountries = ['8f241f11095363464.89657222', 'a7c40f631fc920687.20179984','a7c40f6320aeb2ec2.72885259'];
        $klarnaCountryList = oxNew(KlarnaCountryList::class);
        $klarnaCountryList->loadActiveKlarnaCheckoutCountries();
        foreach ($klarnaCountryList as $country) {
            $result[] = $country->getId();
        }

        foreach ($expectedCountries as $expectedCountry) {
            $this->assertContains($expectedCountry, $result);
        }
    }

    /**
     * @param $data
     */
    public function testLoadActiveNonKlarnaCheckoutCountries()
    {
        $expectedCountries = [
            '8f241f11095306451.36998225',
        ];
        $klarnaCountryList = oxNew(KlarnaCountryList::class);
        $klarnaCountryList->loadActiveNonKlarnaCheckoutCountries();
        foreach ($klarnaCountryList as $country) {
            $result[] = $country->getId();
        }

        foreach ($expectedCountries as $expectedCountry) {
            $this->assertContains($expectedCountry, $result);
        }
    }

    /**
     * @param $data
     */
    public function testLoadActiveKlarnaCheckoutCountries()
    {
        $expectedCountries = [
            '8f241f11095363464.89657222',
            'a7c40f631fc920687.20179984',
            'a7c40f6320aeb2ec2.72885259',
        ];
        $klarnaCountryList = oxNew(KlarnaCountryList::class);
        $klarnaCountryList->loadActiveKCOGlobalCountries();
        foreach ($klarnaCountryList as $country) {
            $result[] = $country->getId();
        }

        foreach ($expectedCountries as $expectedCountry) {
            $this->assertContains($expectedCountry, $result);
        }
    }
}
