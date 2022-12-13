<?php

declare(strict_types=1);

namespace TopConcepts\Klarna\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128112827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {

    }

    /**
     * Add Klarna payment options
     * @throws \Exception
     */
    protected function addKlarnaPaymentsMethods(Schema $schema)
    {
        $oPayment = oxNew(BaseModel::class);
        $oPayment->init('oxpayments');

        $oPayment->load('oxidinvoice');
        $de_prefix = $oPayment->getFieldData('oxdesc') === 'Rechnung' ? 0 : 1;
        $en_prefix = $de_prefix === 1 ? 0 : 1;

        $newPayments = array(
            KlarnaPaymentTypes::KLARNA_PAYMENT_CHECKOUT_ID  =>
                array($de_prefix => 'Klarna Checkout', $en_prefix => 'Klarna Checkout'),
            KlarnaPaymentTypes::KLARNA_PAYMENT_PAY_LATER_ID =>
                array($de_prefix => 'Klarna Rechnung', $en_prefix => 'Klarna Pay Later'),
            KlarnaPaymentTypes::KLARNA_PAYMENT_SLICE_IT_ID  =>
                array($de_prefix => 'Klarna Ratenkauf', $en_prefix => 'Klarna Financing'),
            KlarnaPaymentTypes::KLARNA_PAYMENT_PAY_NOW =>
                array($de_prefix => 'Klarna Sofort bezahlen', $en_prefix => 'Klarna Pay Now'),
            KlarnaPaymentTypes::KLARNA_DIRECTDEBIT =>
                array($de_prefix => 'Klarna Lastschrift', $en_prefix => 'Klarna Direct Debit'),
            KlarnaPaymentTypes::KLARNA_CARD =>
                array($de_prefix => 'Klarna Kreditkarte', $en_prefix => 'Klarna Card'),
            KlarnaPaymentTypes::KLARNA_SOFORT =>
                array($de_prefix => 'Klarna Sofortüberweisung', $en_prefix => 'Klarna Online Bank Transfer'),
        );

        $sort   = -350;
        $aLangs = Registry::getLang()->getLanguageArray();

        if ($aLangs) {
            foreach ($newPayments as $oxid => $aTitle) {
                /** @var Payment $oPayment */
                $oPayment = oxNew(BaseModel::class);
                $oPayment->init('oxpayments');

                $oPayment->load($oxid);
                if ($oPayment->isLoaded()) {
                    $oPayment->oxpayments__oxactive = new Field(1, Field::T_RAW);
                    $oPayment->save();

                    continue;
                }
//                $oPayment->setEnableMultilang(false);
                $oPayment->setId($oxid);
                $oPayment->oxpayments__oxactive      = new Field(1, Field::T_RAW);
                $oPayment->oxpayments__oxaddsum      = new Field(0, Field::T_RAW);
                $oPayment->oxpayments__oxaddsumtype  = new Field('abs', Field::T_RAW);
                $oPayment->oxpayments__oxaddsumrules = new Field('31', Field::T_RAW);
                $oPayment->oxpayments__oxfromboni    = new Field('0', Field::T_RAW);
                $oPayment->oxpayments__oxfromamount  = new Field('0', Field::T_RAW);
                $oPayment->oxpayments__oxtoamount    = new Field('1000000', Field::T_RAW);
                $oPayment->oxpayments__oxchecked     = new Field(0, Field::T_RAW);
                $oPayment->oxpayments__oxsort        = new Field(strval($sort), Field::T_RAW);
                $oPayment->oxpayments__oxtspaymentid = new Field('', Field::T_RAW);

                // set multi language fields
                foreach ($aLangs as $oLang) {
                    $sTag                                     = Registry::getLang()->getLanguageTag($oLang->id);
                    $oPayment->{'oxpayments__oxdesc' . $sTag} = new Field($aTitle[$oLang->id], Field::T_RAW);
                }

                $oPayment->save();
                $sort += 1;
            }
        }

        $schema->

        $updateOxPayments =
            array(
                "UPDATE `oxpayments` SET `TCKLARNA_PAYMENTOPTION`='card' WHERE `oxid`='oxidcreditcard';",
                "UPDATE `oxpayments` SET `TCKLARNA_PAYMENTOPTION`='direct banking' WHERE `oxid`='oxiddebitnote';",
            );
        foreach ($updateOxPayments as $sQuery) {
            $this->db->execute($sQuery);
        }
    }

    protected function checkAndUpdate(Schema $schema) {
        // oxconfig.OXMODULE prefix
        $requireUpdate = $this->db->select(
            "SELECT `OXID` FROM `oxconfig` WHERE OXMODULE = ?;",
            array('tcklarna')
        );
        if ($requireUpdate->count()) {
            foreach($requireUpdate->fetchAll() as $row) {
                $this->db->execute("UPDATE `oxconfig` SET OXMODULE = ? WHERE OXID = ?", array('module:tcklarna', $row['OXID']));
            }
        }
    }

    /**
     * Extend klarna tables
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function extendDbTables(Schema $schema)
    {
        $this->extendDb_tcklarna_logs($schema);

        if (!$schema->hasTable('tcklarna_ack')) {
            $klarnalogs = $schema->createTable('tcklarna_ack');
        } else {
            $klarnalogs = $schema->getTable('tcklarna_ack');
        }

        if (!$klarnalogs->hasColumn('OXID')) {
            $klarnalogs->addColumn(
                'OXID',
                Types::STRING,
                ['columnDefinition' => 'char(32) collate latin1_general_ci']
            );
        }

        if (!$klarnalogs->hasColumn('KLRECEIVED')) {
            $klarnalogs->addColumn(
                'KLRECEIVED',
                Types::DATETIME_MUTABLE,
                ['columnDefinition' => 'char(32) collate latin1_general_ci']
            );
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS `tcklarna_ack` (
                  `OXID`       VARCHAR(32)
                               CHARACTER SET latin1 COLLATE latin1_general_ci
                                        NOT NULL,
                  `KLRECEIVED` DATETIME NOT NULL,
                  PRIMARY KEY (`OXID`)
                )
                  ENGINE = InnoDB
                  COMMENT ='List of all Klarna acknowledge requests'
                  DEFAULT CHARSET = utf8;
                  
                
            CREATE TABLE IF NOT EXISTS `tcklarna_anon_lookup` (
                  `TCKLARNA_ARTNUM` VARCHAR(32) NOT NULL,
                  `OXARTID`  VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
                  PRIMARY KEY (`TCKLARNA_ARTNUM`)
                )
                  ENGINE = InnoDB
                  COMMENT ='Mapping of annonymous article numbers to their oxids'
                  DEFAULT CHARSET = utf8;
                  
            CREATE TABLE IF NOT EXISTS `tcklarna_instant_basket` (
                `OXID` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
                `BASKET_INFO` MEDIUMBLOB,
                `STATUS`  VARCHAR(32) NOT NULL DEFAULT 'OPENED',
                `TYPE` VARCHAR(32) NOT NULL DEFAULT '',
                `TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
                PRIMARY KEY (`OXID`)
            )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8;";

        $this->db->execute($sql);

        $this->addAlterTables();

        $this->updateViews();
    }

    public function down(Schema $schema) : void
    {
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    protected function extendDb_tcklarna_logs(Schema $schema): void
    {
        if (!$schema->hasTable('tcklarna_logs')) {
            $klarnalogs = $schema->createTable('tcklarna_logs');
        } else {
            $klarnalogs = $schema->getTable('tcklarna_logs');
        }

        if (!$klarnalogs->hasColumn('OXID')) {
            $klarnalogs->addColumn(
                'OXID',
                Types::STRING,
                ['columnDefinition' => 'char(32) collate latin1_general_ci']
            );
        }

        if (!$klarnalogs->hasColumn('OXSHOPID')) {
            $klarnalogs->addColumn(
                'OXSHOPID',
                Types::STRING,
                ['columnDefinition' => 'char(32) collate latin1_general_ci']
            );
        }

        if (!$klarnalogs->hasColumn('TCKLARNA_METHOD')) {
            $klarnalogs->addColumn(
                'TCKLARNA_METHOD',
                Types::STRING,
                ['columnDefinition' => 'varchar(128) collate latin1_general_ci']
            );
        }

        if (!$klarnalogs->hasColumn('TCKLARNA_REQUESTRAW')) {
            $klarnalogs->addColumn(
                'TCKLARNA_REQUESTRAW',
                Types::STRING,
                ['columnDefinition' => 'text charset utf8']
            );
        }

        if (!$klarnalogs->hasColumn('TCKLARNA_RESPONSERAW')) {
            $klarnalogs->addColumn(
                'TCKLARNA_RESPONSERAW',
                Types::STRING,
                ['columnDefinition' => 'text charset utf8']
            );
        }

        if (!$klarnalogs->hasColumn('TCKLARNA_DATE')) {
            $klarnalogs->addColumn(
                'TCKLARNA_DATE',
                Types::DATETIME_MUTABLE,
                [
                    'default' => '0000-00-00 00:00:00',
                    'columnDefinition' => 'text charset utf8',
                ],
            );
        }
    }
}
