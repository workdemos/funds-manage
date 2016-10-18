<?php

namespace Speed\Trade\Component;

use Speed\Trade\Entity\FundPool;
use Speed\Trade\Entity\FundUser;
use Speed\Trade\Helper\BalanceConstant;
use Speed\Trade\Locale\LocaleFormat;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Pool {

    private $entityManager;
    private $mainCurrency = \Speed\Trade\Helper\BalanceConstant::CURRENCY_CNY;

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function add($pay) {
        $fund_pool = $this->handleForPool($pay);
        $this->entityManager->flush();
        $this->entityManager->refresh($fund_pool);

        if (isset($pay['balanced']) && $pay['balanced']) {
            try {
                $this->dispose($fund_pool->getId());
                $this->entityManager->flush();
            } catch (\Exception $exc) {
                $fund_pool->setStatus("invalid");
                $this->entityManager->flush();
                throw new Exception("dispose error for add : " . $exc->getTraceAsString());
            }
        }
    }

    public function addMany($pays) {
        $pools = array();

        foreach ($pays as $k => $pay) {
            $pools[$k] = $this->handleForPool($pay);
        }

        $this->entityManager->flush();
    }

    private function handleForPool($data) {

        $this->validete_params($data);

        $currency = isset($data['currency']) ? $data['currency'] : Balance::getDefaultCurrency($data['user_type']);
        $rate = isset($data['rate']) ? $data['rate'] : $this->getMainCurrencyRate($currency);
        $user_id = $data['user_id'];
        $user_type = $data['user_type'];
        $income = $data['income'];

        $res = $income > 0 ? 1 : Balance::getInstance()->setEntityManager($this->entityManager)->setCurrency($currency)->canBalance($user_id, $user_type, $income);

        if (!$res) {
            throw new \Exception("用户余额不足: user_id - $user_id, user_type - $user_type, income - $income");
        }

        $p = array();
        $p['fund_class'] = $data['fund_class'];
        $p['fund_from'] = $data['fund_from'];
        $p['order_id'] = isset($data['order_id']) ? intval($data['order_id']) : 0;
        $amount_vary_for_pool = $this->getMainCurrencyAmount($income, $currency, $rate);
        $p['amount'] = $amount_vary_for_pool;
        $p['income'] = $this->calcIncomeForPool($data['fund_from'], $amount_vary_for_pool);
        $p['status'] = isset($data['status']) ? $data['status'] : 'doing';
        $p['credit_reg'] = isset($data['is_credit']) ? $data['is_credit'] : 0;
        $p['freezed'] = isset($data['freezed']) && $data['freezed'] ? strtotime($data['freezed']) : 0;
        $p['remark'] = isset($data['remark']) ? $data['remark'] : "";


        $user = array();
        $user['id'] = $user_id;
        $user['type'] = $user_type;
        $user['income'] = $income;
        $user['currency'] = $currency;
        $user['rate'] = $rate;

        $fund_pool = new FundPool();
        $fund_pool->setIncome($p['income'])
                ->setAmount($p['amount'])
                ->setFundClass($p['fund_class'])
                ->setFundFrom($p['fund_from'])
                ->setOrderId($p['order_id'])
                ->setStatus($p['status'])
                ->setCredit($p['credit_reg'])
                ->setFreezed($p['freezed'])
                ->setRemark($p['remark'])
                ->setCreated(time());
        $this->entityManager->persist($fund_pool);

        $fund_user = new FundUser();
        $fund_user->setUerId($user['id'])
                ->setUserType($user['type'])
                ->setRate($user['rate'])
                ->setCurrency($user['currency'])
                ->setIncome($user['income'])
                ->setFundPool($fund_pool);
        $this->entityManager->persist($fund_user);

        return $fund_pool;
    }

    public function getMainCurrencyRate($currency) {
        if ($currency == $this->mainCurrency) {
            return 1;
        }

        $type = BalanceConstant::RATE_CNY_TO_JPY;

        $rates = $this->entityManager->getRepository('Speed\Trade\Entity\BalanceRate')->findBy(array('type' => $type), array('id' => 'desc'), 1);
        if (!$rates) {
            throw new \Exception("type: $type 汇率数据无法提供");
        }
        return $rates[0]->getRate();
    }

    private function getMainCurrencyAmount($amount, $currency, $rate) {
        $amount = abs($amount);
        if ($currency != $this->mainCurrency) {
            $amount = number_format($amount / $rate, 2);
        }

        return $amount;
    }

    public function calcIncomeForPool($fund_from, $amount) {

        if (BalanceConstant::FF_POOL_CHONG_ZHI == $fund_from) {
            return abs($amount);
        } elseif (BalanceConstant::FF_POOL_TI_XIAN == $fund_from) {
            return abs($amount) * -1;
        }
        return 0;
    }

    public function dispose($pool_id) {

        $pool = $this->entityManager->getRepository('Speed\Trade\Entity\FundPool')->find($pool_id);

        if (!$pool) {
            throw new \Exception("$pool_id:  不存在资金池");
        }

        if ($pool->getStatus() == 'done') {
            throw new \Exception("$pool_id 已经结算");
        }

        $fund_users = $pool->getFundUsers();
        if (count($fund_users) == 0) {
            throw new \Exception("$pool_id 没有资金处理");
        }

        foreach ($fund_users as $user) {
            $data = array();
            $data['user_id'] = $user->getUerId();
            $data['user_type'] = $user->getUserType();
            $data['income'] = $user->getIncome();

            $balance = Balance::FactoryCurrency($user->getCurrency())->setEntityManager($this->entityManager)->setPool($pool);
            $balance->add($data);
        }

        $pool->setFinished(time())->setStatus("done");
    }

    private function validete_params($params) {
        if (!($params['user_id'] && $params['user_type'] && $params['income'])) {
            throw new \Exception("参数不足: " . json_encode($params), 500);
        }

        $int_from_code = intval($params['fund_from']);
        if ($int_from_code - 20 > 0 && $int_from_code - 20 < 20) {
            if (!$params['order_id']) {
                throw new \Exception("没有提供订单id或者汇率: " . json_encode($params), 500);
            }

            if ($params['user_type'] == BalanceConstant::USER_BUYER && !$params['rate']) {
                throw new \Exception("没有提供汇率: " . json_encode($params), 500);
            }

            if ((isset($params['freezed']) && $params['freezed'] ) && (isset($params['balanced']) && $params['balanced'])) {
                throw new \Exception("冻结处理不能即刻结算 " . json_encode($params));
            }
        }
        return true;
    }

    public function search($conditions, $page = 1, $numPerPage = 20) {
        $where = " f.id > 0  ";
        $order = " ORDER BY f.id DESC ";

        if (isset($conditions['fund_from']) && $conditions['fund_from'] > 0) {
            $where .= " AND f.fund_from = " . $conditions['fund_from'];
        }

        if (isset($conditions['fund_class']) && $conditions['fund_class'] > 0) {
            $where .= " AND f.fund_class = " . $conditions['fund_class'];
        }

        if (isset($conditions['date_start']) && $conditions['date_start']) {
            $created_cond = " f.created >= " . strtotime($conditions['date_start']);

            if (isset($conditions['date_end']) && $conditions['date_end']) {
                $created_cond .= "  AND f.created <= " . strtotime("+1 day", strtotime($conditions['date_end']));
            }
            $where .= " AND ($created_cond) ";
        }

        $query = $this->entityManager->createQuery("SELECT f FROM  Speed\Trade\Entity\FundPool f  WHERE " . $where . $order)
                ->setFirstResult($numPerPage * ($page - 1))
                ->setMaxResults($numPerPage);
        $paginator = new Paginator($query, true);

        $c = count($paginator);
        $pools = array();
        foreach ($paginator as $pool) {
            $pools[] = array(
                "id" => $pool->getId(),
                "income" => $pool->getIncome(),
                "amount" => $pool->getAmount(),
                "title" => BalanceConstant::getJPDescForFundFrom($pool->getFundFrom()),
                "source" => BalanceConstant::getZhDescForFundClass($pool->getFundClass()),
                "order_id" => $pool->getOrderId(),
                "status" => $pool->getStatus(),
                "created" => LocaleFormat::DateFromUnixTime($pool->getCreated()),
                "finished" => LocaleFormat::DateFromUnixTime($pool->getFinished()),
                "freezed" => LocaleFormat::DateFromUnixTime($pool->getFreezed()),
                //  "order_num" => $jpy->getFundPool()->getOrderId(),
                "remark" => $pool->getRemark(),
                "credit" => $pool->getCredit(),
                "serial" => "1023424234234"
            );
        }

        $result = array("items" => $pools, "count" => $c, "curpage" => $page, "numPerPage" => $numPerPage);

        return $result;
    }

    protected function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

}
