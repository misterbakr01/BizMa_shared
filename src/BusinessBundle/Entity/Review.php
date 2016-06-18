<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Review
 *
 * @ORM\Table(name="review")
 * @ORM\Entity(repositoryClass="BusinessBundle\Repository\ReviewRepository")
 */
class Review
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="reviewer", type="integer")
     */
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
     */
    private $reviewer;

    /**
     * @var int
     *
     * @ORM\Column(name="business", type="integer")
     */
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="openings")
     * @ORM\JoinColumn(name="business", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="reviews")
     * @ORM\JoinColumn(name="business", referencedColumnName="id")
     */
    private $business;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;


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
     * Set title
     *
     * @param string $title
     *
     * @return Review
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Review
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set reviewer
     *
     * @param integer $reviewer
     *
     * @return Review
     */
    public function setReviewer($reviewer)
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    /**
     * Get reviewer
     *
     * @return int
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * Set business
     *
     * @param integer $business
     *
     * @return Review
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
     * Set rating
     *
     * @param integer $rating
     *
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }
}

