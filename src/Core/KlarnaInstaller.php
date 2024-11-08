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


use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\DoctrineMigrationWrapper\MigrationsBuilder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;
use OxidEsales\Facts\Facts;

class KlarnaInstaller extends ShopConfiguration
{
    const KLARNA_MODULE_ID = 'tcklarna';

    static private $instance = null;

    /**
     * Database name
     * @var string $dbName
     */
    protected $dbName;
    protected $modulePath;

    /**
     * @return KlarnaInstaller|null|object
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new KlarnaInstaller();
            self::$instance->dbName = Registry::getConfig()->getConfigParam('dbName');
            $oModuleConfiguration = self::getModuleConfigs('tcklarna');
            $oFacts = oxNew(Facts::class);
            self::$instance->modulePath = $oFacts->getShopRootPath() . '/' . $oModuleConfiguration->getModuleSource();
        }

        return self::$instance;
    }

    /**
     * Activation sequence
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     * @throws \Exception
     */
    public static function onActivate()
    {
        $instance = self::getInstance();

        $instance->checkAndUpdate();
        $instance->executeModuleMigrations();

        $oMetaData = oxNew(DbMetaDataHandler::class);
        $oMetaData->updateViews();
    }

    /**
     * Execute necessary module migrations on activate event
     */
    private static function executeModuleMigrations(): void
    {
        $migrations = (new MigrationsBuilder())->build();

        $output = new BufferedOutput();
        $migrations->setOutput($output);

        $needsUpdate = $migrations->execute('migrations:up-to-date', 'tcklarna');

        if ($needsUpdate) {
            $migrations->execute('migrations:migrate', 'tcklarna');
        }
    }

    /**
     * @return void
     */
    protected function checkAndUpdate()
    {
        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
        $oQueryBuilderFactory = self::getQueryBuilder();
        $oQueryBuilder = $oQueryBuilderFactory->create();
        $oQueryBuilder
            ->select('OXID')
            ->from('oxconfig')
            ->where('OXMODULE = :oxmodule')
            ->setParameter(':oxmodule', 'tcklarna');
        $aRequireUpdate = $oQueryBuilder->execute();
        $aRequireUpdate = $aRequireUpdate->fetchAllAssociative();

        foreach ($aRequireUpdate as $row) {
            $oQueryBuilder = $oQueryBuilderFactory->create();
            $oQueryBuilder
                ->update('oxconfig')
                ->set('OXMODULE', 'module:tcklarna')
                ->where('OXID = :oxid')
                ->setParameter(':oxid', $row['OXID']);
        }
    }

    /**
     * Delete all files and dirs from given path
     * @param $dirPath
     *
     */
    protected static function deleteRecursive($dirPath) {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath,
                \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
    }

    /**
     * @return void
     */
    public static function onDeactivate()
    {
        $tempDirectory = Registry::getConfig()->getConfigParam("sCompileDir");
        self::deleteRecursive($tempDirectory . 'template_cache');
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected static function getQueryBuilder() {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
        return $oContainer->get(QueryBuilderFactoryInterface::class);
    }

    /**
     * @param string $moduleId
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected static function getModuleConfigs(string $moduleId)
    {
        $oContainer = ContainerFactory::getInstance()->getContainer();
        return $oContainer->get(ModuleConfigurationDaoBridgeInterface::class)->get($moduleId);
    }
}