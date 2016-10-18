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
use Speed\Trade\Helper\BalanceConstant;

/**
 * Fund  Pool
 *
 * @Entity
 * @Table(name="FundUser")
 * 
 */
class FundUser {

  /**
   * @Id
   * @Column(type="integer")
   * @generatedValue(strategy="IDENTITY")
   */
  protected $id = null;

  /**
   *
   * @Column(type="decimal", precision=9,scale=2, nullable=false) 
   */
  protected $income;

  /**
   *
   * @Column(type="decimal", precision=5,scale=2, nullable=false) 
   */
  protected $rate;

  /**
   * @Column(type="integer", columnDefinition="TINYINT(2) NOT NULL")
   */
  protected $currency;

  /**
   *
   * @Column(type="integer", length=11)
   */
  protected $uer_id;

  /**
   * @Column(type="integer", columnDefinition="TINYINT(2) NOT NULL")
   */
  protected $user_type;

  /**
   *
   * @ManyToOne(targetEntity="FundPool", inversedBy="fund_users")
   * @JoinColumn(name="fund_pool_id", referencedColumnName="id",nullable=false)
   */
  private $fund_pool;
  
  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set income
   *
   * @param string $income
   * @return FundUser
   */
  public function setIncome($income) {
    $this->income = $income;

    return $this;
  }

  /**
   * Get income
   *
   * @return string 
   */
  public function getIncome() {
    return $this->income;
  }

  /**
   * Set rate
   *
   * @param string $rate
   * @return FundUser
   */
  public function setRate($rate) {
    $this->rate = $rate;

    return $this;
  }

  /**
   * Get rate
   *
   * @return string 
   */
  public function getRate() {
    return $this->rate;
  }

  /**
   * Set uer_id
   *
   * @param integer $uerId
   * @return FundUser
   */
  public function setUerId($uerId) {
    $this->uer_id = $uerId;

    return $this;
  }

  /**
   * Get uer_id
   *
   * @return integer 
   */
  public function getUerId() {
    return $this->uer_id;
  }



  /**
   * Set user_type
   *
   * @param integer $userType
   * @return FundUser
   */
  public function setUserType($userType) {
    $this->user_type = $userType;

    return $this;
  }

  /**
   * Get user_type
   *
   * @return integer 
   */
  public function getUserType() {
    return $this->user_type;
  }

  /**
   * Set currency
   *
   * @param integer $currency
   * @return FundUser
   */
  public function setCurrency($currency) {
    $this->currency = $currency;

    return $this;
  }

  /**
   * Get currency
   *
   * @return integer 
   */
  public function getCurrency() {
    return $this->currency;
  }

  public function reverify($currency, $rate) {
    $user_type = $this->getUserType();
    if ($user_type == BalanceConstant::USER_MAKER || $user_type == BalanceConstant::USER_PLAT) {
      if ($currency != BalanceConstant::CURRENCY_CNY) {
        $this->setIncome(number_format($this->getIncome() / $rate, 2));
      }
    }

    if ($user_type == BalanceConstant::USER_BUYER) {
      if ($currency != BalanceConstant::CURRENCY_JPY) {
        $this->setIncome(number_format($this->getIncome() / $rate, 2));
      }
    }
    return $this;
  }


    /**
     * Set fund_pool
     *
     * @param \Speed\Trade\Entity\FundPool $fundPool
     * @return FundUser
     */
    public function setFundPool(\Speed\Trade\Entity\FundPool $fundPool)
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
}
