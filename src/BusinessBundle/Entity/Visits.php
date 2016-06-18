<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visits
 *
 * @ORM\Table(name="visits")
 * @ORM\Entity(repositoryClass="BusinessBundle\Repository\VisitsRepository")
 */
class Visits
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="business", type="integer",nullable=false)
     */
    /**
     * @ORM\ManyToOne(targetEntity="BusinessBundle\Entity\Business", inversedBy="id")
     * @ORM\JoinColumn(name="business", referencedColumnName="id")
     */
    private $business;

    /**
     * @var int
     *
     * @ORM\Column(name="visitor", type="integer", nullable=false)
     */
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="id")
     * @ORM\JoinColumn(name="visitor", referencedColumnName="id")
     */
    private $visitor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitTime", type="datetime")
     */
    private $visitTime;

    /**
     * Visits constructor.
     * @param $business
     * @param int $visitor
     * @param \DateTime $visitTime
     */
    public function __construct($business, $visitor, \DateTime $visitTime)
    {
        $this->business = $business;
        $this->visitor = $visitor;
        $this->visitTime = $visitTime;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set business
     *
     * @param integer $business
     *
     * @return Visits
     */
    public function setBusiness($business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return int
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Set visitor
     *
     * @param integer $visitor
     *
     * @return Visits
     */
    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * Get visitor
     *
     * @return int
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * Set visitTime
     *
     * @param \DateTime $visitTime
     *
     * @return Visits
     */
    public function setVisitTime($visitTime)
    {
        $this->visitTime = $visitTime;

        return $this;
    }

    /**
     * Get visitTime
     *
     * @return \DateTime
     */
    public function getVisitTime()
    {
        return $this->visitTime;
    }
}

