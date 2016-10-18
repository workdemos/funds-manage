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
 * @Table(name="m_maker")
 * 
 */
class Maker {

  /**
   * @Id
   * @Column(type="integer", name="m_maker_id")
   * @generatedValue(strategy="IDENTITY")
   */
  protected $id = null;

  /**
   *
   * @Column(type="string", length=50, name="m_maker_account") 
   */
  protected $account;

  /**
   *
   * @Column(type="string", length=100, name="m_maker_name") 
   */
  protected  $name;

   /**
   *
   * @Column(type="string", length=200, name="m_maker_company_name_ch") 
   */
  protected $company;
  
    /**
   *
   * @Column(type="string", length=200, name="m_maker_sekininsha_ch") 
   */
  protected $legalPerson;
  
  /**
   * @Column(type="string", length=255, name="m_maker_sekininsha_email")
   */
  protected $email;

  /**
   *
   * @Column(type="string", length=200, name="m_maker_sekininsha_tel")
   */
  protected $telphone;

   

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
     * @return Maker
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
     * @return Maker
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
     * Set company
     *
     * @param string $company
     * @return Maker
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set legalPerson
     *
     * @param string $legalPerson
     * @return Maker
     */
    public function setLegalPerson($legalPerson)
    {
        $this->legalPerson = $legalPerson;

        return $this;
    }

    /**
     * Get legalPerson
     *
     * @return string 
     */
    public function getLegalPerson()
    {
        return $this->legalPerson;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Maker
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
     * @return Maker
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
}
