<?php

namespace Speed\Trade\Component;

use Speed\Trade\Helper\BalanceConstant;

class Balance {

    private $entityManager;
    private $currency;

    public static function FactoryCurrency($currency) {

        $class = Balance::getWrapClass($currency);

        if (class_exists($class)) {
            return new $class();
        } else {
            throw new \Exception("货币结算: $currency 不存在");
        }
    }

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    public static function getWrapClass($currency) {
        $class = '';
        if ($currency == BalanceConstant::CURRENCY_CNY) {
            $class = "Speed\\Trade\\Component\\Money\\CNY";
        } elseif ($currency == BalanceConstant::CURRENCY_JPY) {
            $class = "Speed\\Trade\\Component\\Money\\JPY";
        }

        return $class;
    }

    public static function getEntityClass($currency) {
        $entiy = '';
        if ($currency == BalanceConstant::CURRENCY_CNY) {
            $entiy = "Speed\\Trade\\Entity\\BalanceCny";
        } elseif ($currency == BalanceConstant::CURRENCY_JPY) {
            $entiy = "Speed\\Trade\\Entity\\BalanceJpy";
        }
        return $entiy;
    }

    public static function getDefaultCurrency($user_type) {
        $currency = null;
        if ($user_type == BalanceConstant::USER_BUYER) {
            $currency = BalanceConstant::CURRENCY_JPY;
        } elseif ($user_type == BalanceConstant::USER_MAKER || $user_type == BalanceConstant::USER_PLAT) {
            $currency = BalanceConstant::CURRENCY_CNY;
        }

        if (!$currency) {
            throw new \Exception("未知用户类型:$user_type");
        }
        return $currency;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
        return $this;
    }

   
    public function canBalance($user_id, $user_type, $income) {
        if ($income >= 0) {
            return true;
        }

        $funds = $this->entityManager
                ->getRepository($this->getEntityClass($this->currency))
                ->findBy(array('user_id' => $user_id, 'user_type' => $user_type), array('id' => 'desc'), 1);

        $last_balancy = $funds ? $funds[0]->getBalance() : 0;

        if (abs($income) > $last_balancy) {
            return false;
        }
        return true;
    }

    protected function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

}
