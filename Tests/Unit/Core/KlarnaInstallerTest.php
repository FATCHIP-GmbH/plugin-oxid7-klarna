<?php

namespace TopConcepts\Klarna\Tests\Unit\Core;


use OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\DisplayError;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Facts\Facts;
use TopConcepts\Klarna\Core\KlarnaInstaller;
use TopConcepts\Klarna\Tests\Unit\ModuleUnitTestCase;

class KlarnaInstallerTest extends ModuleUnitTestCase
{
    const KLARNA_PAYMENT_IDS = [
        'klarna_pay_later',
        'klarna_pay_now',
        'klarna_slice_it',
    ];

    /**
     * Completely revert the module set-up done at the beginning of tests
     */
    protected function revertTestSuiteSetup()
    {
        $database = DatabaseProvider::getDB();

        $paymentIds = self::KLARNA_PAYMENT_IDS;
        unset($paymentIds[3]);

        foreach ($paymentIds as $id) {
            $database->execute("DELETE FROM `oxpayments` WHERE oxid = ?", [$id]);
            $database->execute("DELETE FROM `oxconfig` WHERE oxvarname = ?", ['blKlarnaAllowSeparateDeliveryAddress']);
        }

        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);

        $database->execute("DROP TABLE IF EXISTS `tcklarna_ack`");
        $database->execute("DROP TABLE IF EXISTS `tcklarna_logs`");
        $database->execute("DROP TABLE IF EXISTS `tcklarna_anon_lookup`");

        $dbMetaDataHandler->executeSql([
            "ALTER TABLE oxorder DROP `TCKLARNA_MERCHANTID`",
            "ALTER TABLE oxorder DROP `TCKLARNA_SERVERMODE`",
            "ALTER TABLE oxorder DROP `TCKLARNA_ORDERID`",
            "ALTER TABLE oxorder DROP `TCKLARNA_SYNC`",

            "ALTER TABLE oxorderarticles DROP `TCKLARNA_TITLE`",
            "ALTER TABLE oxorderarticles DROP `TCKLARNA_ARTNUM`",

            "ALTER TABLE oxpayments DROP `TCKLARNA_PAYMENTTYPES`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_EXTERNALNAME`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_EXTERNALCHECKOUT`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_PAYMENTIMAGEURL`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_PAYMENTIMAGEURL_1`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_PAYMENTIMAGEURL_2`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_PAYMENTIMAGEURL_3`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_PAYMENTOPTION`",
            "ALTER TABLE oxpayments DROP `TCKLARNA_EMDPURCHASEHISTORYFULL`",

            "ALTER TABLE oxaddress DROP `TCKLARNA_TEMPORARY`",

            "DROP TABLE IF EXISTS `tcklarna_ack`",
            "DROP TABLE IF EXISTS `tcklarna_logs`",
            "DROP TABLE IF EXISTS `tcklarna_anon_lookup`",
        ]);


    }

    /**
     * Trigger onActivate to bring the environment back to the module being fully active
     * @afterClass
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public static function redoTestSuiteSetup()
    {
        KlarnaInstaller::onActivate();
        $oModuleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
        $oModuleSettingService->saveBoolean('blKlarnaAllowSeparateDeliveryAddress', true, 'tcklarna');
    }

    /**
     *
     */
    public function testGetInstance()
    {
        $this->setProtectedClassProperty(KlarnaInstaller::getInstance(), 'instance', null);
        $result = KlarnaInstaller::getInstance();

        $dbName     = $this->getProtectedClassProperty($result, 'dbName');
        $modulePath = $this->getProtectedClassProperty($result, 'modulePath');
        $db         = $this->getProtectedClassProperty($result, 'db');

        $this->assertTrue($result instanceof KlarnaInstaller);
        $this->assertTrue($db instanceof DatabaseInterface);
        $this->assertEquals(Registry::getConfig()->getConfigParam('dbName'), $dbName);
        $oModuleConfiguration = $this->getModuleConfigs('tcklarna');
        $oFacts = oxNew(Facts::class);
        $this->assertEquals($oFacts->getShopRootPath(). '/' . $oModuleConfiguration->getModuleSource(), $modulePath);
    }

    /**
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function testOnActivate()
    {
        $this->revertTestSuiteSetup();

        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        KlarnaInstaller::onActivate();

        //test payment methods
        /* @var QueryBuilderFactoryInterface $queryBuilderFactory */
        $oQueryBuilderFactory = $this->getQueryBuilder();
        $oQueryBuilder = $oQueryBuilderFactory->create();
        $oQueryBuilder
            ->select('oxid','oxactive')
            ->from('oxpayments')
            ->where('oxid IN (:search1, :search2, :search3, :search4)')
            ->setParameters([
                ':search1' => self::KLARNA_PAYMENT_IDS[0],
                ':search2' => self::KLARNA_PAYMENT_IDS[1],
                ':search3' => self::KLARNA_PAYMENT_IDS[2],
                ':search4' => self::KLARNA_PAYMENT_IDS[3]
            ])
            ->SetMaxResults(1); //Limit 1
        $aPayments = $oQueryBuilder->execute();
        $aPayments = $aPayments->fetchAllAssociative();
        $this->assertEquals(4, count($aPayments));
        foreach ($aPayments as $aPayment) {
            $this->assertEquals('1', $aPayment['oxactive']);
        }
        $this->assertTables($dbMetaDataHandler);
        $this->assertColumns($dbMetaDataHandler);

        $oModuleSettingService = $this->getModuleSettings();
        $this->assertEquals(
            true,
            $oModuleSettingService->getBoolean('blKlarnaAllowSeparateDeliveryAddress', 'tcklarna')
        );
    }

    /**
     * @param $dbMetaDataHandler
     */
    public function assertTables($dbMetaDataHandler)
    {
        $this->assertTrue($dbMetaDataHandler->tableExists('tcklarna_ack'));
        $this->assertTrue($dbMetaDataHandler->tableExists('tcklarna_anon_lookup'));
        $this->assertTrue($dbMetaDataHandler->tableExists('tcklarna_logs'));
    }

    /**
     * @param $dbMetaDataHandler
     */
    public function assertColumns($dbMetaDataHandler)
    {
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_MERCHANTID', 'oxorder'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_SERVERMODE', 'oxorder'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_ORDERID', 'oxorder'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_SYNC', 'oxorder'));

        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_TITLE', 'oxorderarticles'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_ARTNUM', 'oxorderarticles'));

        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_PAYMENTTYPES', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_EXTERNALNAME', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_EXTERNALCHECKOUT', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_PAYMENTIMAGEURL', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_PAYMENTIMAGEURL_1', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_PAYMENTIMAGEURL_2', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_PAYMENTIMAGEURL_3', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_PAYMENTOPTION', 'oxpayments'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_EMDPURCHASEHISTORYFULL', 'oxpayments'));

        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_TEMPORARY', 'oxaddress'));

        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_ORDERID', 'tcklarna_logs'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_MID', 'tcklarna_logs'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_STATUSCODE', 'tcklarna_logs'));
        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_URL', 'tcklarna_logs'));

        $this->assertTrue($dbMetaDataHandler->fieldExists('TCKLARNA_ORDERID', 'tcklarna_ack'));
    }
}
