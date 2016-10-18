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
 * @Table(name="FundPool")
 * 
 */
class FundPool {

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
     * @Column(type="decimal", precision=9,scale=2, nullable=false) 
     */
    protected $amount;

    /**
     * @Column(type="integer", columnDefinition="TINYINT(2) NOT NULL")
     */
    protected $fund_from;

    /**
     * @Column(type="integer", columnDefinition="TINYINT(2) NOT NULL")
     */
    protected $fund_class;

    /**
     *
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $order_id;

    /**
     *
     * @Column(type="string", columnDefinition="ENUM('done', 'freeze', 'invalid','doing') NOT NULL")
     */
    protected $status = 'doing';

    /**
     *
     * @Column(type="boolean", nullable=false)
     */
    protected $credit = 0;

    /**
     *
     * @Column(type="integer", precision=10, nullable=false)
     */
    protected $created;

    /**
     *
     * @Column(type="integer", precision=10)
     */
    protected $finished = 0;

    /**
     *
     * @Column(type="integer", precision=10)
     */
    protected $freezed = 0;

    /**
     *
     * @Column(type="string", length=25)
     */
    protected $remark = "";

    /**
   *
   * @OneToMany(targetEntity="FundUser", mappedBy="fund_pool")
   */
    private $fund_users;

    public function __construct() {
        $this->fund_users = new ArrayCollection();
    }

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
     * @return FundPool
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
     * Set amount
     *
     * @param string $amount
     * @return FundPool
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set order_id
     *
     * @param integer $orderId
     * @return FundPool
     */
    public function setOrderId($orderId) {
        $this->order_id = $orderId;

        return $this;
    }

    /**
     * Get order_id
     *
     * @return integer 
     */
    public function getOrderId() {
        return $this->order_id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return FundPool
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return FundPool
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return integer 
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set finished
     *
     * @param integer $finished
     * @return FundPool
     */
    public function setFinished($finished) {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return integer 
     */
    public function getFinished() {
        return $this->finished;
    }

    /**
     * Set freezed
     *
     * @param integer $freezed
     * @return FundPool
     */
    public function setFreezed($freezed) {
        $this->freezed = $freezed;

        return $this;
    }

    /**
     * Get freezed
     *
     * @return integer 
     */
    public function getFreezed() {
        return $this->freezed;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return FundPool
     */
    public function setRemark($remark) {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string 
     */
    public function getRemark() {
        return $this->remark;
    }

    /**
     * Set fund_from
     *
     * @param integer $fundFrom
     * @return FundPool
     */
    public function setFundFrom($fundFrom) {
        $this->fund_from = $fundFrom;

        return $this;
    }

    /**
     * Get fund_from
     *
     * @return integer 
     */
    public function getFundFrom() {
        return $this->fund_from;
    }

    /**
     * Set fund_class
     *
     * @param integer $fundClass
     * @return FundPool
     */
    public function setFundClass($fundClass) {
        $this->fund_class = $fundClass;

        return $this;
    }

    /**
     * Get fund_class
     *
     * @return integer 
     */
    public function getFundClass() {
        return $this->fund_class;
    }

    /**
     * Set is_credit
     *
     * @param boolean $isCredit
     * @return FundPool
     */
    public function setIsCredit($isCredit) {
        $this->is_credit = $isCredit;

        return $this;
    }

    /**
     * Get is_credit
     *
     * @return boolean 
     */
    public function getIsCredit() {
        return $this->is_credit;
    }

   
    /**
     * Add fund_users
     *
     * @param \Speed\Trade\Entity\FundUser $fundUsers
     * @return FundPool
     */
    public function addFundUser(\Speed\Trade\Entity\FundUser $fundUsers)
    {
        $this->fund_users[] = $fundUsers;

        return $this;
    }

    /**
     * Remove fund_users
     *
     * @param \Speed\Trade\Entity\FundUser $fundUsers
     */
    public function removeFundUser(\Speed\Trade\Entity\FundUser $fundUsers)
    {
        $this->fund_users->removeElement($fundUsers);
    }

    /**
     * Get fund_users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFundUsers()
    {
        return $this->fund_users;
    }

    /**
     * Set credit
     *
     * @param boolean $credit
     * @return FundPool
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return boolean 
     */
    public function getCredit()
    {
        return $this->credit;
    }
}
