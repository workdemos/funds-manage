<?php

namespace Speed\Trade\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Fund  Pool
 *
 * @Entity
 * @Table(name="BalanceCny")
 * 
 */
class BalanceCny {
   
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     */
    protected  $id = null;
    
     /**
     *
     * @Column(type="decimal", precision=9,scale=2,nullable=false) 
     */
    protected $income;
    
     /**
     *
     * @Column(type="decimal", precision=12,scale=2,nullable=false) 
     */
    protected $balance;
    
     /**
     *
     * @Column(type="decimal", precision=12,scale=2,nullable=false) 
     */
    protected $balance_for_cash;
    
     /**
     *
     * @Column(type="decimal", precision=12,scale=2,nullable=false) 
     */
    protected $balance_for_credit;
    
        /**
     *
     * @Column(type="integer", precision=10,nullable=false)
     */
    protected $created;
    
        /**
     *
     * @Column(type="integer", precision=11,nullable=false)
     */
    protected $user_id;
    
       /**
     * @Column(type="integer", columnDefinition="TINYINT(2) NOT NULL")
     */
    protected $user_type;
   
     /**
     *
     * @Column(type="integer", columnDefinition="TINYINT(2) NOT NULL")
     */
    protected $fund_from ="";
    
  /**
   * 
     * @ManyToOne(targetEntity="FundPool")
     * @JoinColumn(name="fund_pool_id", referencedColumnName="id",nullable=false)
     */
   private $fund_pool;
   
   

    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set income
     *
     * @param string $income
     * @return BalanceCny
     */
    public function setIncome($income)
    {
        $this->income = $income;

        return $this;
    }

    /**
     * Get income
     *
     * @return string 
     */
    public function getIncome()
    {
        return $this->income;
    }

    /**
     * Set balance
     *
     * @param string $balance
     * @return BalanceCny
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set balance_for_cash
     *
     * @param string $balanceForCash
     * @return BalanceCny
     */
    public function setBalanceForCash($balanceForCash)
    {
        $this->balance_for_cash = $balanceForCash;

        return $this;
    }

    /**
     * Get balance_for_cash
     *
     * @return string 
     */
    public function getBalanceForCash()
    {
        return $this->balance_for_cash;
    }

    /**
     * Set balance_for_credit
     *
     * @param string $balanceForCredit
     * @return BalanceCny
     */
    public function setBalanceForCredit($balanceForCredit)
    {
        $this->balance_for_credit = $balanceForCredit;

        return $this;
    }

    /**
     * Get balance_for_credit
     *
     * @return string 
     */
    public function getBalanceForCredit()
    {
        return $this->balance_for_credit;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return BalanceCny
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return integer 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return BalanceCny
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * Set fund_pool
     *
     * @param \Speed\Trade\Entity\FundPool $fundPool
     * @return BalanceCny
     */
    public function setFundPool(\Speed\Trade\Entity\FundPool $fundPool = null)
    {
        $this->fund_pool = $fundPool;

        return $this;
    }

    /**
     * Get fund_pool
     *
     * @return \Speed\Trade\Entity\FundPool 
     */
    public function getFundPool()
    {
        return $this->fund_pool;
    }



    /**
     * Set user_type
     *
     * @param integer $userType
     * @return BalanceCny
     */
    public function setUserType($userType)
    {
        $this->user_type = $userType;

        return $this;
    }

    /**
     * Get user_type
     *
     * @return integer 
     */
    public function getUserType()
    {
        return $this->user_type;
    }

    /**
     * Set fund_from
     *
     * @param integer $fundFrom
     * @return BalanceCny
     */
    public function setFundFrom($fundFrom)
    {
        $this->fund_from = $fundFrom;

        return $this;
    }

    /**
     * Get fund_from
     *
     * @return integer 
     */
    public function getFundFrom()
    {
        return $this->fund_from;
    }
}
