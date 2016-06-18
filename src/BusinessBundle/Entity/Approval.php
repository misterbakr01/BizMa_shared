<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * approval
 *
 * @ORM\Table(name="approval")
 * @ORM\Entity(repositoryClass="BusinessBundle\Repository\ApprovalRepository")
 */
class Approval
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
     * @ORM\Column(name="business_id", type="integer")
     */
     /**
     * @ORM\ManyToOne(targetEntity="BusinessBundle\Entity\Business", inversedBy="id")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    private $businessId;


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
     * Set businessId
     *
     * @param integer $businessId
     *
     * @return approval
     */
    public function setBusinessId($businessId)
    {
        $this->businessId = $businessId;

        return $this;
    }

    /**
     * Get businessId
     *
     * @return int
     */
    public function getBusinessId()
    {
        return $this->businessId;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return approval
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set responsable
     *
     * @param integer $responsable
     *
     * @return approval
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return int
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return approval
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

