<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OpeningHours
 *
 * @ORM\Table(name="opening_hours")
 * @ORM\Entity(repositoryClass="BusinessBundle\Repository\OpeningHoursRepository")
 */
class OpeningHours
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
     * @ORM\Column(name="day", type="integer")
     */
    private $day;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="open", type="string")
     */
    private $open;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="close", type="string")
     */
    private $close;

    /**
     * @var int
     *
     * @ORM\Column(name="business_id", type="integer")
     *
     */
    private $business_id;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="openings")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    private $business;
    /**
     * @var string
     */
    private $slider;

    /**
     * @return string
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @param string $slider
     */
    public function setSlider($slider)
    {
        $this->slider = $slider;
    }

    /**
     * @return mixed
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @param mixed $business
     */
    public function setBusiness($business)
    {
        $this->business = $business;
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
     * Set day
     *
     * @param integer $day
     *
     * @return OpeningHours
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set open
     *
     * @param \DateTime $open
     *
     * @return OpeningHours
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return \DateTime
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set close
     *
     * @param \DateTime $close
     *
     * @return OpeningHours
     */
    public function setClose($close)
    {
        $this->close = $close;

        return $this;
    }

    /**
     * Get close
     *
     * @return \DateTime
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * Set business
     *
     * @param integer $business_id
     *
     * @return OpeningHours
     */
    public function setBusinessId($business_id)
    {
        $this->business_id = $business_id;

        return $this;
    }

    /**
     * Get business
     *
     * @return int
     */
    public function getBusinessId()
    {
        return $this->business_id;
    }

    public function addBusiness(Business $business)
    {
        $this->setBusinessId($business->getId());
        $business->addOpenings($this);
    }
}

