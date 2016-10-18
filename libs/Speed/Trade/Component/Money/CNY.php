<?php

namespace Speed\Trade\Component\Money;

use Speed\Trade\Entity\BalanceCny;
use Speed\Trade\Helper\BalanceConstant;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CNY extends CurrencyImpl {

    public function __construct() {
        parent::__construct('Speed\Trade\Entity\BalanceCny');
    }

    private function calcCredit($balance, $balance_cash, $balance_credit, $income, $is_credit) {
        $last_balance = array();
        $last_balance['income'] = $income;
        $last_balance['balance'] = $balance + $income;
        if ($is_credit) {
            $last_balance['balance_cash'] = $balance_cash;
            $last_balance['balance_credit'] = $balance_credit + $income;
        } else {
            $last_balance['balance_credit'] = $balance_credit;
            $last_balance['balance_cash'] = $balance_cash + $income;
        }
        return $last_balance;
    }

    private function calcBalance($user_id, $user_type, $income, $is_credit, $fund_from) {

        $cnys = $this->entityManager->getRepository($this->entity)->findBy(array('user_id' => $user_id, 'user_type' => $user_type), array('id' => 'desc'), 1);

        $last_balancy = 0;
        $last_balancy_for_cash = 0;
        $last_balancy_for_credit = 0;

        if ($cnys) {
            $b = $cnys[0];
            $last_balancy = $b->getBalance();
            $last_balancy_for_cash = $b->getBalanceForCash();
            $last_balancy_for_credit = $b->getBalanceForCredit();
        }

        if ($income < 0 && $last_balancy + $income < 0) {
            throw new \Exception("余额不足: user_Id = $user_id, type=$user_type", 600);
        }

        $cash = $this->calcCredit($last_balancy, $last_balancy_for_cash, $last_balancy_for_credit, $income, $is_credit);
        $balance = array(
            "user_id" => $user_id,
            "user_type" => $user_type,
            "income" => $income,
            "balance" => $cash['balance'],
            "balance_cash" => $cash['balance_cash'],
            "balance_credit" => $cash['balance_credit'],
            "fund_from" => $fund_from,
        );

        return $balance;
    }

    public function univesalCharge($user_id, $user_type, $income, $is_credit) {
        $charges = array();
        $fund_from = $this->pool->getFundFrom();
        $charges['user'] = $this->calcBalance($user_id, $user_type, $income, $is_credit, $fund_from);  //收
        $charges['plat'] = $this->calcBalance(BalanceConstant::PLAT_USER_ID, BalanceConstant::USER_PLAT, -1 * $income, $is_credit, $fund_from);  //支

        return $charges;
    }

    private function paymentCharge($user_id, $user_type, $income, $is_credit) {
        $charges = array();
        $fund_from = $this->pool->getFundFrom();
        $user_c = $this->calcBalance($user_id, $user_type, $income, $is_credit, $fund_from);
        $plat_c = $this->calcBalance(BalanceConstant::PLAT_USER_ID, BalanceConstant::USER_PLAT, -1 * $income, $is_credit, $fund_from);

        $charge['user'] = $user_c;  //收
        $charge['plat'] = $plat_c;  //支
        //计算佣金
        $yj_income = round(abs($income) * 0.06, 2);

        $user_cash_yj = $this->calcCredit($user_c['balance'], $user_c['balance_cash'], $user_c['balance_credit'], -1 * $yj_income, $is_credit);
        $charges['user_ji'] = array(
            "user_id" => $user_id,
            "user_type" => $user_type,
            "income" => -1 * $yj_income,
            "balance" => $user_cash_yj['balance'],
            "balance_cash" => $user_cash_yj['balance_cash'],
            "balance_credit" => $user_cash_yj['balance_credit'],
            "fund_from" => BalanceConstant::FF_ORDER_YONG_JING,
        );  //支

        $plat_cash_yj = $this->calcCredit($plat_c['balance'], $plat_c['balance_cash'], $plat_c['balance_credit'], $yj_income, $is_credit);
        $charges['plat_yj'] = array(
            "user_id" => $plat_c['user_id'],
            "user_type" => $plat_c['user_type'],
            "income" => $yj_income,
            "balance" => $plat_cash_yj['balance'],
            "balance_cash" => $plat_cash_yj['balance_cash'],
            "balance_credit" => $plat_cash_yj['balance_credit'],
            "fund_from" => BalanceConstant::FF_ORDER_YONG_JING,
        ); //收

        return $charges;
    }

    private function withdrawCharge($user_id, $user_type, $income, $is_credit) {
        $charges = array();

        $user_c = $this->calcBalance($user_id, $user_type, $income, $is_credit, $this->pool->getFundFrom());
        $charges['user'] = $user_c; //收
        //计算提现手续费
        $fei = $this->getWithdrawFei(abs($income));

        if ($fei > 0) {
            $user_fei = $this->calcCredit($user_c['balance'], $user_c['balance_cash'], $user_c['balance_credit'], -1 * $fei, 0);
            $charges['user_fei'] = array(
                "user_id" => $user_id,
                "user_type" => $user_type,
                "income" => -1 * $fei,
                "balance" => $user_fei['balance'],
                "balance_cash" => $user_fei['balance_cash'],
                "balance_credit" => $user_fei['balance_credit'],
                "fund_from" => BalanceConstant::FF_FEI_TI_XIAN,
            );  //支

            $plat_fei = $this->calcBalance(BalanceConstant::PLAT_USER_ID, BalanceConstant::USER_PLAT, $fei, 0, BalanceConstant::FF_FEI_TI_XIAN);
            $charges['plat_fei'] = $plat_fei;
        }

        return $charges;
    }

    private function chongzhiCharge($user_id, $user_type, $income, $is_credit) {
        $charges = array();

        $charges['user'] = $this->calcBalance($user_id, $user_type, $income, $is_credit);  //收

        return $charges;
    }

    /*
     * 人民币账户提现收费须知 
     * 1 每笔提现3W人民币以上（含3万）免手续费
     * 2 每笔提现3W人民币以下（不含3万）一律统一收取提现手续费75元人民币
     * 3 人民币账户提现最低标准需要在100元人民币以上（含100元），100元以下不可以提现
     */

    private function getWithdrawFei($amount) {
        $amount = doubleval($amount);
        $fei = 0;

        if ($amount > 30000) {
            $fei = 0;
        } else if ($amount < 30000 && $amount >= 100) {
            $fei = 75;
        } else {
            throw new \Exception("人民币提现金额不能少于100元");
        }

        return $fei;
    }

    public function add($pay) {
        $user_id = intval($pay['user_id']);
        $user_type = $pay['user_type'];
        $income = floatval($pay['income']);
        $is_credit = $this->pool->getCredit();

        $fund_from = $this->pool->getFundFrom();

        $charges = array();
        if ($fund_from == BalanceConstant::FF_POOL_CHONG_ZHI) {
            $charges = $this->chongzhiCharge($user_id, $user_type, $income, $is_credit);
        } elseif ($fund_from == BalanceConstant::FF_POOL_TI_XIAN) {
            $charges = $this->withdrawCharge($user_id, $user_type, $income, $is_credit);
        } elseif ($fund_from - 20 > 0 && $fund_from - 20 < 10) {
            $charges = $this->paymentCharge($user_id, $user_type, $income, $is_credit);
        } else {
            $charges = $this->universalCharge($user_id, $user_type, $income, $is_credit);
        }

        foreach ($charges as $charge) {
            $cny_balance = new BalanceCny();
            $cny_balance->setIncome($charge['income'])
                    ->setBalance($charge['balance'])
                    ->setBalanceForCash($charge['balance_cash'])
                    ->setBalanceForCredit($charge['balance_credit'])
                    ->setFundFrom($charge['fund_from'])
                    ->setCreated(time())
                    ->setUserId($charge['user_id'])
                    ->setUserType($charge['user_type'])
                    ->setFundPool($this->pool);
            $this->entityManager->persist($cny_balance);
        }
    }

    public function remove($id) {
        
    }

    public function search($conditions, $page = 1, $numPerPage = 20) {
        $where = " p.user_id = {$conditions['user_id']} AND p.user_type= {$conditions['user_type']} ";
        $order = " ORDER BY p.id DESC ";

        if (isset($conditions['fund_from']) && $conditions['fund_from'] > 0) {
            $where .= " AND p.fund_from = " . $conditions['fund_from'];
        }

        if (isset($conditions['date_start']) && $conditions['date_start']) {
            $created_cond = " p.created >= " . strtotime($conditions['date_start']);

            if (isset($conditions['date_end']) && $conditions['date_end']) {
                $created_cond .= "  AND p.created <= " . strtotime("+1 day", strtotime($conditions['date_end']));
            }
            $where .= " AND ($created_cond) ";
        }

        if (isset($conditions['type'])) {
            if ($conditions['type'] == 'credit') {
                $where .= " AND f.credit=1 ";
            } elseif ($conditions['type'] == 'cash') {
                $where .= " AND f.credit <> 1 ";
            }
        }
        $query = $this->entityManager->createQuery("SELECT p,f FROM " . $this->entity . " p  LEFT JOIN  p.fund_pool f   WHERE " . $where . $order)
                ->setFirstResult($numPerPage * ($page - 1))
                ->setMaxResults($numPerPage);

        $paginator = new Paginator($query, true);

        $c = count($paginator);
        $cnys = array();
        foreach ($paginator as $k => $cny) {
            $cnys[$k] = array(
                "id" => $cny->getId(),
                "income" => intval($cny->getIncome()),
                "created" => strftime("%Y-%m-%d %H:%M:%S", $cny->getCreated()),
                "title" => BalanceConstant::getZhDescForFundFrom($cny->getFundFrom()),
                "order_id" => $cny->getFundPool()->getOrderId(),
                "order_num" => $cny->getFundPool()->getOrderId(),
                "remark" => $cny->getFundPool()->getRemark(),
                "serial" => $this->getSerial($cny->getFundPool()->getOrderId(), $cny->getId())
            );
            if ($conditions['type'] == 'credit') {
                $cnys[$k]['balance_credit'] = intval($cny->getBalanceForCredit());
            } elseif ($conditions['type'] == 'cash') {
                $cnys[$k]['balance_cash'] = intval($cny->getBalanceForCash());
            } else {
                $cnys[$k]['balance'] = intval($cny->getBalance());
                $cnys[$k]['balance_credit'] = intval($cny->getBalanceForCredit());
                $cnys[$k]['balance_cash'] = intval($cny->getBalanceForCash());
            }
        }

        $result = array("items" => $cnys, "count" => $c, "curpage" => $page, "numPerPage" => $numPerPage);

        return $result;
    }

    public function modify($id, $vals) {
        
    }

    public function getLastBalance($user_id, $user_type) {
        if (!isset($user_id) || !$user_type) {
            throw new Exception("", 100);
        }
        $funds = $this->entityManager
                ->getRepository($this->entity)
                ->findBy(array('user_id' => $user_id, 'user_type' => $user_type), array('id' => 'desc'), 1);

        $balance = array("last_balance" => 0.00, "last_cash_balance" => 0.00, "last_credit_balance" => 0.00);
        if ($funds) {
            $last_cny = $funds[0];
            $balance = array("last_balance" => $last_cny->getBalance(),
                "last_cash_balance" => $last_cny->getBalanceForCash(),
                "last_credit_balance" => $last_cny->getBalanceForCredit());
        }

        return $balance;
    }

}
