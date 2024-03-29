<?php
require_once __DIR__.'/../src/bootstrap.php';
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;


$loader = new Loader();

$loader->loadFromDirectory(__DIR__.'/../src/Speed/Trade/DataFixtures');
//$loader->addFixture(new \Speed\Trade\DataFixtures\LoadRateData());
$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures());
