<?php

namespace Speed\Trade\DataFixtures;

use Speed\Trade\Helper\BalanceConstant;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Comment fixtures
 */
class LoadPoolData implements FixtureInterface {

  /**
   * 
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager) {
    $data = $this->preparePoolData();

    
    $pool = \Speed\Trade\Component\Pool::getInstance()->setEntityManager($manager);
    foreach ($data as $k => $d) {
      $pool->add($d);
    }
  }

  private function preparePoolData() {
    //买家充值
    $data[] = array(
      "user_id" => 10082,
      "user_type" => BalanceConstant::USER_BUYER,
      "income" => 1800,
      "fund_from" => BalanceConstant::FF_POOL_CHONG_ZHI,
      "fund_class" => BalanceConstant::FC_XIN_YONG_KA,
      "currency" => BalanceConstant::CURRENCY_JPY,
      "status" => "doing",
      "balanced" => 1,
      "is_credit" => 1
    );

    //买家付款
    $data[] = array(
      "user_id" => 10082,
      "user_type" => BalanceConstant::USER_BUYER,
      "income" => -1600,
      "fund_from" => BalanceConstant::FF_ORDER_FU_KUAN,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
      "currency" => BalanceConstant::CURRENCY_JPY,
      "order_id" => 10245,
      "rate" => 17.9,
      "is_credit" => 1
    );

    //订单缺货补差
    $data[] = array(
      "user_id" => 10082,
      "user_type" => BalanceConstant::USER_BUYER,
      "income" => 179,
      "fund_from" => BalanceConstant::FF_ORDER_DD_BUCHA,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
      "order_id" => 10245,
      "rate" => 17.9,
      "is_credit" => 1
    );

    //订单退款前商家收款
    $data[] = array(
      "user_id" => 10948,
      "user_type" => BalanceConstant::USER_MAKER,
      "income" => 50,
      "fund_from" => BalanceConstant::FF_ORDER_HUO_KUAN,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
      "order_id" => 10245,
      "is_credit" => 1
    );

//买家获得部分退款
    $data[] = array(
      "user_id" => 10082,
      "user_type" => BalanceConstant::USER_BUYER,
      "income" => 10,
      "fund_from" => BalanceConstant::FF_ORDER_TUI_KUAN,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
      "order_id" => 10245,
      "rate" => 17.9,
      "is_credit" => 1,
    );
//商家家获得部分退款
    $data[] = array(
      "user_id" => 10948,
      "user_type" => BalanceConstant::USER_MAKER,
      "income" => 20,
      "fund_from" => BalanceConstant::FF_ORDER_TUI_KUAN,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
      "order_id" => 10245,
      "is_credit" => 1,
    );

    $data[] = array(
      "user_id" => 10948,
      "user_type" => BalanceConstant::USER_MAKER,
      "income" => -30,
      "fund_from" => BalanceConstant::FF_POOL_TI_XIAN,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
      "balanced" => 1,
    );
    $data[] = array(
      "user_id" => 10948,
      "user_type" => BalanceConstant::USER_MAKER,
      "income" => 100,
      "fund_from" => BalanceConstant::FF_POOL_CHONG_ZHI,
      "fund_class" => BalanceConstant::FC_ZX_YINGHANG,
      "balanced" => 1,
    );

    $data[] = array(
      "user_id" => 10948,
      "user_type" => BalanceConstant::USER_MAKER,
      "income" => -80,
      "fund_from" => BalanceConstant::FF_QT_BUY_SMB,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
    );

    $data[] = array(
      "user_id" => 10082,
      "user_type" => BalanceConstant::USER_BUYER,
      "income" => -15,
      "fund_from" => BalanceConstant::FF_POOL_TI_XIAN,
      "fund_class" => BalanceConstant::FC_XIANJING_YU_E,
      "balanced" => 1,
    );
    return $data;
  }

  /**
   * 
   * {@inheritDoc}
   */
//public function getDependencies(){
//    return ['Speed\Trade\DataFixtures\LoadPoolData'];
//}
}
