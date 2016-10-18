<?php

namespace Speed\Trade\Component;

use Speed\Trade\Helper\BalanceConstant;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;

class Admin {

    private $entityManager;
    private $currency;

    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
        return $this;
    }

    public function getJPYListFund($user_id, $user_type, $page = 1, $numPerPage = 20) {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('Speed\Trade\Entity\BalanceJpy', 'jpy');
        $rsm->addFieldResult('jpy', 'id', 'id');
        $rsm->addFieldResult('jpy', 'user_id', 'user_id');
        $rsm->addFieldResult('jpy', 'balance', 'balance');

        $where = "";
        if ($user_id > 0) {
            $where = " WHERE user_id = " . $user_id . " AND user_type = " . $user_type;
        } else {
            $where = "WHERE user_id > 1";
        }

        $sql = "SELECT  id,user_id,balance from LastJPYBalance    $where   order by balance desc limit " . $numPerPage . " offset " . ($page - 1) * $numPerPage;

        $query = $this->entityManager->createNativeQuery($sql, $rsm);
        $funds = $query->getResult();

        $data = array();
        foreach ($funds as $fund) {
            $user_id = $fund->getUserId();
            $customer = $this->entityManager->getRepository('Speed\Trade\Entity\Customer')->find($user_id);
            $data[] = array(
                "cid" => $user_id,
                "account" => $customer ? $customer->getAccount() : "",
                "name" => $customer ? $customer->getName() : "",
                "email" => $customer ? $customer->getEmail() : "",
                "telpone" => $customer ? $customer->getTelphone() : "",
                "virtual" => $customer ? $customer->getVirtualAccount()->getId() : "",
                "last_balance" => $fund->getBalance()
            );
        }

        $result = array("items" => $data);
        if ($user_id > 1) {
            $rsm_count = new ResultSetMapping;
            $rsm_count->addScalarResult("amount", "amount");
            $query_count = $this->entityManager->createNativeQuery("SELECT count(id)  amount FROM LastJPYBalance WHERE user_id > 0", $rsm_count);
            $all = $query_count->getResult()[0]['amount'];
            $result['count'] = $all;
            $result['curpage'] = $page;
            $result['numPerPage'] = $numPerPage;
        }

        return $result;
    }

    public function getCNYListFund($user_id = 0, $user_type = 0, $page = 1, $numPerPage = 20) {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('Speed\Trade\Entity\BalanceCny', 'cny');
        $rsm->addFieldResult('cny', 'id', 'id');
        $rsm->addFieldResult('cny', 'user_id', 'user_id');
        $rsm->addFieldResult('cny', 'balance', 'balance');
        $rsm->addFieldResult('cny', 'balance_for_cash', 'balance_for_cash');
        $rsm->addFieldResult('cny', 'balance_for_credit', 'balance_for_credit');

        if ($user_id > 0) {
            $where = " WHERE user_id = " . $user_id . " AND user_type = " . $user_type;
        } else {
            $where = "WHERE user_id > 1";
        }

        $sql = "SELECT  id,user_id,balance,balance_for_cash,balance_for_credit  from LastCNYBalance    $where   order by balance desc limit " .
                $numPerPage . " offset " . ($page - 1) * $numPerPage;

        $query = $this->entityManager->createNativeQuery($sql, $rsm);
        $funds = $query->getResult();

        $data = array();
        foreach ($funds as $fund) {
            $user_id = $fund->getUserId();
            $maker = $this->entityManager->getRepository('Speed\Trade\Entity\Maker')->find($user_id);

            $data[] = array(
                "cid" => $user_id,
                "account" => $maker ? $maker->getAccount() : "",
                "name" => $maker ? $maker->getName() : "",
                "company" => $maker ? $maker->getCompany() : "",
                "legalPerson" => $maker ? $maker->getLegalPerson() : "",
                "last_balance" => $fund->getBalance(),
                "last_cash_balance" => $fund->getBalanceForCash(),
                "last_credit_balance" => $fund->getBalanceForCredit()
            );
        }

        $result = array("items" => $data);
        if ($user_id > 1) {
            $rsm_count = new ResultSetMapping;
            $rsm_count->addScalarResult("amount", "amount");
            $query_count = $this->entityManager->createNativeQuery("SELECT count(id)  amount FROM LastCNYBalance WHERE user_id > 0", $rsm_count);
            $all = $query_count->getResult()[0]['amount'];
            $result['count'] = $all;
            $result['curpage'] = $page;
            $result['numPerPage'] = $numPerPage;
        }

        return $result;
    }

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

}
