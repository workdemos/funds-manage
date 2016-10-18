<?php

namespace Speed\Trade\Component\Money;

use Speed\Trade\Entity\BalanceJpy;
use Speed\Trade\Helper\BalanceConstant;
use Doctrine\ORM\Tools\Pagination\Paginator;

class JPY extends CurrencyImpl {

    public function __construct() {
        parent::__construct('Speed\Trade\Entity\BalanceJpy');
    }

    private function calcBalance($user_id, $user_type, $income, $fund_from) {

        $cnys = $this->entityManager->getRepository($this->entity)->findBy(array('user_id' => $user_id, 'user_type' => $user_type), array('id' => 'desc'), 1);

        $last_balancy = 0;

        if ($cnys) {
            $b = $cnys[0];
            $last_balancy = $b->getBalance();
        }

        if ($income < 0 && $last_balancy + $income < 0) {
            throw new \Exception("余额不足: user_Id = $user_id, type=$user_type", 600);
        }

        $balance = array(
            "user_id" => $user_id,
            "user_type" => $user_type,
            "income" => $income,
            "balance" => $last_balancy + $income,
            "fund_from" => $fund_from,
        );

        return $balance;
    }

    public function universalCharge($user_id, $user_type, $income) {
        $charges = array();
        $fund_from = $this->pool->getFundFrom();
        $charges['user'] = $this->calcBalance($user_id, $user_type, $income, $fund_from);  //收
        $charges['plat'] = $this->calcBalance(BalanceConstant::PLAT_USER_ID, BalanceConstant::USER_PLAT, -1 * $income, $fund_from);  //支

        return $charges;
    }

    private function withdrawCharge($user_id, $user_type, $income) {
        $charges = array();

        $user_c = $this->calcBalance($user_id, $user_type, $income, $this->pool->getFundFrom());
        $charges['user'] = $user_c; //收
        //计算提现手续费
        $fei = $this->getWithdrawFei(abs($income));

        if ($fei > 0) {
            $charges['user_fei'] = array(
                "user_id" => $user_id,
                "user_type" => $user_type,
                "income" => -1 * $fei,
                "balance" => $user_c['balance'] - $fei,
                "fund_from" => BalanceConstant::FF_FEI_TI_XIAN,
            );  //支

            $plat_fei = $this->calcBalance(BalanceConstant::PLAT_USER_ID, BalanceConstant::USER_PLAT, $fei, BalanceConstant::FF_FEI_TI_XIAN);
            $charges['plat_fei'] = $plat_fei;
        }

        return $charges;
    }

    private function chongzhiCharge($user_id, $user_type, $income) {
        $charges = array();

        $charges['user'] = $this->calcBalance($user_id, $user_type, $income);  //收

        return $charges;
    }

    /*
     * 日币账户提现须知
     * 1 每笔提现金额不计 一律收取手续费 700元日币+56元消费税（共手续费756元）
     * 2 每笔提现金额最低标准需要在1000元日币以上（含1000元），1000元以下不可以提现。
     */

    private function getWithdrawFei($amount) {
        $amount = intval($amount);
        $fei = 0;

        if ($amount > 1000) {
            $fei = 756;
        } else {
            throw new Exception("日元提现金额不能少于756元"); //1000円以下の金額で出金された場合には対応できません。
        }

        return $fei;
    }

    public function add($pay) {
        $user_id = intval($pay['user_id']);
        $user_type = $pay['user_type'];
        $income = floatval($pay['income']);

        $fund_from = $this->pool->getFundFrom();

        $charges = array();
        if ($fund_from == BalanceConstant::FF_POOL_CHONG_ZHI) {
            $charges = $this->chongzhiCharge($user_id, $user_type, $income);
        } elseif ($fund_from == BalanceConstant::FF_POOL_TI_XIAN) {
            $charges = $this->withdrawCharge($user_id, $user_type, $income);
        } else {
            $charges = $this->universalCharge($user_id, $user_type, $income);
        }

        foreach ($charges as $charge) {
            $cny_balance = new BalanceJpy();
            $cny_balance->setIncome($charge['income'])
                    ->setBalance($charge['balance'])
                    ->setFundFrom($charge['fund_from'])
                    ->setCreated(time())
                    ->setUserId($charge['user_id'])
                    ->setUserType($charge['user_type'])
                    ->setFundPool($this->pool);
            $this->entityManager->persist($cny_balance);
        }
    }

    public function modify($id, $vals) {
        
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

        $query = $this->entityManager->createQuery("SELECT p,f FROM " . $this->entity . " p  LEFT JOIN  p.fund_pool f   WHERE " . $where . $order)
                ->setFirstResult($numPerPage * ($page - 1))
                ->setMaxResults($numPerPage);

        $paginator = new Paginator($query, true);

        $c = count($paginator);
        $jpys = array();
        foreach ($paginator as $jpy) {
            $jpys[] = array(
                "id" => $jpy->getId(),
                "income" => intval($jpy->getIncome()),
                "balance" => intval($jpy->getBalance()),
                "created" => strftime("%Y-%m-%d %H:%M:%S", $jpy->getCreated()),
                "title" => BalanceConstant::getJPDescForFundFrom($jpy->getFundFrom()),
                "order_id" => $jpy->getFundPool()->getOrderId(),
                "order_num" => $jpy->getFundPool()->getOrderId(),
                "remark" => $jpy->getFundPool()->getRemark(),
                "serial" => $this->getSerial($jpy->getFundPool()->getOrderId(), $jpy->getId())
            );
        }

        $result = array("items" => $jpys, "count" => $c, "curpage" => $page, "numPerPage" => $numPerPage);

        return $result;
    }

}
