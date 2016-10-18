<?php

namespace Speed\Trade\DataFixtures;


use Speed\Trade\Entity\BalanceRate;
use Speed\Trade\Helper\BalanceConstant;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Comment fixtures
 */
class LoadRateData implements FixtureInterface {


    /**
     * 
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $data = $this->prepareRateData();
      
        foreach($data as $rate){
          $balance_rate = new BalanceRate();
          $balance_rate->setType($rate['type'])->setRate($rate['rate'])->setCreated(time());
          $manager->persist($balance_rate);
        }
        $manager->flush();
       
    }
    
    private function prepareRateData(){
      $data = array();
      
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>17.74);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>17.67);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>17.61);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>17.56);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>17.50);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>17.50);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>16.91);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>16.81);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>16.66);
      $data[] = array("type"=> BalanceConstant::RATE_CNY_TO_JPY,"rate"=>16.20);
      
      shuffle($data);
      return $data;
    }
}