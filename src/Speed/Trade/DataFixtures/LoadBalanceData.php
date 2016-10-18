<?php

namespace Speed\Trade\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Comment fixtures
 */
class LoadBalanceData implements FixtureInterface, DependentFixtureInterface {

  /**
   * 
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager) {
    $q = $manager->createQuery("select p from Speed\Trade\Entity\FundPool p where p.status='doing' order by p.id");

    $pools = $q->getResult();

    foreach ($pools as $k => $pool) {
      \Speed\Trade\Component\Pool::getInstance()->setEntityManager($manager)->dispose($pool->getId());
      $manager->flush();
    }
    

  }

  /**
   * 
   * {@inheritDoc}
   */
  public function getDependencies() {
    return ['Speed\Trade\DataFixtures\LoadRateData', 'Speed\Trade\DataFixtures\LoadPoolData'];
  }

}
