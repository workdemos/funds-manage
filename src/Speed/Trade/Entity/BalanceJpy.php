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
 * @Table(name="BalanceJpy")
 * 
 */
class BalanceJpy {
   
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
     * @Column(type="decimal", precision=13,scale=2,nullable=false) 
     */
    protected $balance;
    
    
        /**
     *
     * @Column(type="integer", precision=10,nullable=false)
     */
    protected $created;
    
        /**
     *
     * @Column(type="integer", precision=11, nullable=false)
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
     * @ManyToOne(targetEntity="FundPool",  fetch="EXTRA_LAZY")
     * @JoinColumn(name="fund_pool_id", referencedColumnName="id", nullable=false)
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
     * @return BalanceJpy
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
     * @return BalanceJpy
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
     * Set created
     *
     * @param integer $created
     * @return BalanceJpy
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
     * @return BalanceJpy
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
     * @return BalanceJpy
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
     * @return BalanceJpy
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
     * @return BalanceJpy
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
