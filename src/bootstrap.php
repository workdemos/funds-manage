<?php
/**************************************************************************************************************
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';

$entitiesPath = array(__DIR__.'/Speed/Trade/Entity');
$config = Setup::createAnnotationMetadataConfiguration($entitiesPath, $isDevMode);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// obtaining the entity manager
$entityManager = EntityManager::create($dbParams, $config);

$conn = $entityManager->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

**************************************************************************************************************/

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';


$entitiesPath = array(__DIR__.'/Speed/Trade/Entity');
$proxiesPath = __DIR__.'/Speed/Trade/Proxies';

if ($applicationMode == "development") {
    $cache = new \Doctrine\Common\Cache\ArrayCache;
} else {
    $cache = new \Doctrine\Common\Cache\ApcCache;
}

$config = new Configuration;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver($entitiesPath);
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
$config->setProxyDir($proxiesPath);
$config->setProxyNamespace('Speed\Trade\Proxies');

if($logging){
    $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
}


if ($applicationMode == "development") {
    $config->setAutoGenerateProxyClasses(true);
} else {
    $config->setAutoGenerateProxyClasses(false);
}

$entityManager = EntityManager::create($dbParams, $config);

$conn = $entityManager->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');