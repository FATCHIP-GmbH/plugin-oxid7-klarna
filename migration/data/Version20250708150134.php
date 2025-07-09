<?php

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250708150134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `oxorder` 
                            ADD column `TCKLARNA_KLARNAPAYMENTMETHOD` 
                            VARCHAR(128) 
                            COMMENT "Klarna authorized payment method type"');
    }

    public function down(Schema $schema): void
    {
    }
}
