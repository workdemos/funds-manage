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
 * @Table(name="m_customer")
 * 
 */
class Customer {

  /**
   * @Id
   * @Column(type="integer", name="m_customer_id")
   * @generatedValue(strategy="IDENTITY")
   */
  protected $id = null;

  /**
   *
   * @Column(type="string", length=50, name="m_customer_account") 
   */
  protected $account;

  /**
   *
   * @Column(type="string", length=100, name="m_customer_name") 
   */
  protected  $name;

  /**
   * @Column(type="string", length=255, name="m_customer_mail")
   */
  protected $email;

  /**
   *
   * @Column(type="string", length=20, name="m_customer_tel")
   */
  protected $telphone;

    /**
     * @OneToOne(targetEntity="VirtualAccount", mappedBy="customer")
     **/
  private $virtualAccount;


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
     * Set account
     *
     * @param string $account
     * @return Customer
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return string 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telphone
     *
     * @param string $telphone
     * @return Customer
     */
    public function setTelphone($telphone)
    {
        $this->telphone = $telphone;

        return $this;
    }

    /**
     * Get telphone
     *
     * @return string 
     */
    public function getTelphone()
    {
        return $this->telphone;
    }


   

    /**
     * Set virtualAccount
     *
     * @param \Speed\Trade\Entity\VirtualAccount $virtualAccount
     * @return Customer
     */
    public function setVirtualAccount(\Speed\Trade\Entity\VirtualAccount $virtualAccount = null)
    {
        $this->virtualAccount = $virtualAccount;

        return $this;
    }

    /**
     * Get virtualAccount
     *
     * @return \Speed\Trade\Entity\VirtualAccount 
     */
    public function getVirtualAccount()
    {
        return $this->virtualAccount;
    }
}
