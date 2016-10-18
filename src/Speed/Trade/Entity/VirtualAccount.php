<?php


namespace Speed\Trade\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;




/**
 * Fund  Pool
 *
 * @Entity
 * @Table(name="m_virtualaccount")
 * 
 */
class VirtualAccount {

  /**
   * @Id
   * @Column(type="integer", name="m_virtualaccount_id")
   */
  protected $id;

   /**
   * @Column(type="integer", name="m_virtualaccount_m_customer_id")
   */
  protected $customer_id;
  /**
   *
   * @Column(type="datetime",name="m_virtualaccount_rdatetime") 
   */
  protected $created;


   /**
     * @OneToOne(targetEntity="Customer", inversedBy="virtualAccount")
     * @JoinColumn(name="m_virtualaccount_m_customer_id", referencedColumnName="m_customer_id")
     **/
  private $customer;
  
 

    /**
     * Set id
     *
     * @param integer $id
     * @return VirtualAccount
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set customer_id
     *
     * @param integer $customerId
     * @return VirtualAccount
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    /**
     * Get customer_id
     *
     * @return integer 
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return VirtualAccount
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set customer
     *
     * @param \Speed\Trade\Entity\Customer $customer
     * @return VirtualAccount
     */
    public function setCustomer(\Speed\Trade\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Speed\Trade\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
