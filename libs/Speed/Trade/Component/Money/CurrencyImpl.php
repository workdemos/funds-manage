<?php

namespace Speed\Trade\Component\Money;

abstract  class CurrencyImpl implements Currency {

    protected $entityManager;
    protected $pool;
    protected $entity;

    public function __construct($entity) {
        $this->entity = $entity;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function setPool(\Speed\Trade\Entity\FundPool $pool) {
        $this->pool = $pool;
        return $this;
    }

    public function getLastBalance($user_id, $user_type) { 
        if (!isset($user_id) || !$user_type) {
            throw new Exception("", 100);
        }
        $funds = $this->entityManager
                ->getRepository($this->entity)
                ->findBy(array('user_id' => $user_id, 'user_type' => $user_type), array('id' => 'desc'), 1);

        $balance = array("last_balance" => $funds ? $funds[0]->getBalance() : 0);
        return $balance;
    }
   
    public function getSerial( $order_id,$fund_id){
        return strlen(strlen($order_id)) . strlen(strlen($fund_id)) .  strlen($order_id) . $order_id . strlen($fund_id) . $fund_id;
    }
    
    public function add($pay) {
        
    }

    public function modify($id, $vals) {
        
    }

    public function remove($id) {
        
    }

    public function search($conditions, $page, $numPerpage) {
        
    }

}
