<?php

namespace TopConcepts\Klarna\Tests\Unit\Model;


use OxidEsales\Eshop\Application\Model\UserPayment;
use OxidEsales\Eshop\Core\Field;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

/**
 * Class KlarnaUserPaymentTest
 * @package TopConcepts\Klarna\Tests\Unit\Models
 * @covers \TopConcepts\Klarna\Model\KlarnaUserPayment
 */
class KlarnaUserPaymentTest extends ModuleUnitTestCase
{
    /**
     * @dataProvider paymentDataProvider
     * @param $payId
     * @param $expectedResult
     * @param $notUsed
     */
    public function testGetBadgeUrl($payId, $expectedResult, $notUsed)
    {
        $userPaymentModel                               = oxNew(UserPayment::class);
        $userPaymentModel->oxuserpayments__oxpaymentsid = new Field($payId, Field::T_RAW);

        $result = $userPaymentModel->getBadgeUrl();

        $this->doAssertContains($expectedResult, $result);
    }

    public function paymentDataProvider()
    {
        return [
            ['klarna', '//cdn.klarna.com/1.0/shared/image/generic/badge/de_de/klarna/standard/pink.png', true],
            ['other', '//cdn.klarna.com/1.0/shared/image/generic/badge/de_de//standard/pink.png', false],
        ];
    }

    /**
     * @dataProvider paymentDataProvider
     * @param $payId
     * @param $notUsed
     * @param $isKlarna
     */
    public function testIsKlarnaPayment($payId, $notUsed, $isKlarna)
    {
        $userPaymentModel                               = oxNew(UserPayment::class);
        $userPaymentModel->oxuserpayments__oxpaymentsid = new Field($payId, Field::T_RAW);

        $this->assertEquals($isKlarna, $userPaymentModel->isKlarnaPayment());
    }
}
