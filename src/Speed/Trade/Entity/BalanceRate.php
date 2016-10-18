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
 * @Table(name="BalanceRate")
 * 
 */
class BalanceRate {

  /**
   * @Id
   * @Column(type="integer")
   * @generatedValue(strategy="IDENTITY")
   */
  protected $id = null;

    /**
     * @Column(type="integer", columnDefinition="TINYINT(2) NOT NULL")
     */
  protected $exchange;

  /**
   *
   * @Column(type="decimal", precision=6,scale=2,  nullable=false) 
   */
  protected $rate;

  /**
   *
   * @Column(type="integer", precision=10, nullable=false)
   */
  protected $created;


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
     * Set rate
     *
     * @param string $rate
     * @return BalanceRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return BalanceRate
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
     * Set exchange
     *
     * @param integer $type
     * @return BalanceRate
     */
    public function setExchange($type)
    {
        $this->exchange = $type;

        return $this;
    }

    /**
     * Get exchange
     *
     * @return integer 
     */
    public function getExchange()
    {
        return $this->exchange;
    }
}
