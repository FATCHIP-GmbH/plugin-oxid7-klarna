<?php

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Facts\Facts;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

$serviceCaller = new \OxidEsales\TestingLibrary\ServiceCaller();
$testConfig = new \OxidEsales\TestingLibrary\TestConfig();
$oFacts = oxNew(Facts::class);
$oModuleConfiguration = getModuleConfigs('tcklarna');
$testDirectory = $testConfig->getEditionTestsPath($testConfig->getShopEdition());
$klarnaTestDirectory = $oFacts->getShopRootPath()  . '/' . $oModuleConfiguration->getModuleSource() . '/Tests/';
$serviceCaller->setParameter('importSql', '@' . $testDirectory . '/Fixtures/testdata.sql');
$serviceCaller->setParameter('importSql', '@' . $klarnaTestDirectory . 'Unit/Testdata/klarna-settings.sql');


/** Add object to shop mapping for EE */
if ($testConfig->getShopEdition() === 'EE') {
    $db = DatabaseProvider::getDb();
    $shopId = 1;
    $mapIds = [
        'oxarticles' => [1, 2, 3],
        'oxcategories' => [1],
        'oxdiscount' => [1]
    ];

    foreach ($mapIds as $tableName => $mapIds) {
        $sql = "REPLACE INTO `{$tableName}2shop` SET `oxmapobjectid` = ?, `oxshopid` = ?";
        foreach ($mapIds as $mapId) {
            $db->execute($sql, [$mapId, $shopId]);
        }
    }
}

$serviceCaller->callService('ShopPreparation', 1);

/* @var QueryBuilderFactoryInterface $queryBuilderFactory */
$oQueryBuilderFactory = $this->getQueryBuilder();
$oQueryBuilder = $oQueryBuilderFactory->create();
$oQueryBuilder
    ->select('OXUSERNAME')
    ->from('oxuser')
    ->where('OXID = :oxid')
    ->setParameter(':oxid', 'oxdefaultadmin');
$sOxUsername = $oQueryBuilder->execute();
$sOxUsername = $sOxUsername->fetchOne();

define('oxADMIN_LOGIN', $sOxUsername);
define('oxADMIN_PASSWD', getenv('oxADMIN_PASSWD') ? getenv('oxADMIN_PASSWD') : 'admin');

/**
 * @param string $moduleId
 * @return mixed
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function getModuleConfigs(string $moduleId)
{
    $oContainer = ContainerFactory::getInstance()->getContainer();
    return $oContainer->get(ModuleConfigurationDaoBridgeInterface::class)->get($moduleId);
}

/**
 * @return mixed
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function getQueryBuilder() {
    $oContainer = ContainerFactory::getInstance()->getContainer();
    /** @var QueryBuilderFactoryInterface $oQueryBuilderFactory */
    return $oContainer->get(QueryBuilderFactoryInterface::class);
}
