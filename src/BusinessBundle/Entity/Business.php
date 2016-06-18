<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Business
 *
 * @ORM\Table(name="business")
 * @ORM\Entity(repositoryClass="BusinessBundle\Repository\BusinessRepository")
 */
class Business
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="BusinessBundle\Entity\Visits", mappedBy="business")
     * @ORM\JoinColumn(name="id", referencedColumnName="business")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="businesses")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
     */
    private $owner;

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="OpeningHours", mappedBy="business", cascade={"persist"}, fetch="EAGER")
     */
    private $openings;

    /**
     * @ORM\OneToMany(targetEntity="Review", mappedBy="business", cascade={"persist"}, fetch="EAGER")
     */
    private $reviews;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="adding_date", type="datetime")
     */
    private $adding_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_edit", type="datetime", nullable=true)
     */
    private $last_edit;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float")
     */
    private $lat;

    /**
     * @var int
     *
     * @ORM\Column(name="city", type="integer")
     */
    /**
     * @ORM\ManyToOne(targetEntity="BusinessBundle\Entity\City", inversedBy="businesses")
     * @ORM\JoinColumn(name="city", referencedColumnName="id")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var int
     *
     * @ORM\Column(name="category", type="integer")
     */
    /**
     * @ORM\ManyToOne(targetEntity="BusinessBundle\Entity\Category", inversedBy="businesses")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */
    private $category;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", nullable=true)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=14, nullable=true)
     */
    private $phone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    private $approved;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var int
     *
     * @ORM\Column(name="responsable", type="integer")
     */
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="id")
     * @ORM\JoinColumn(name="responsable", referencedColumnName="id")
     */
    private $responsable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateofapproval", type="datetime")
     */
    private $dateofapproval;

    /**
     * Business constructor.
     */
    public function __construct()
    {
        $this->openings = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Business
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
     * Set description
     *
     * @param string $description
     *
     * @return Business
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
     * Set address
     *
     * @param string $address
     *
     * @return Business
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return \DateTime
     */
    public function getAddingDate()
    {
        return $this->adding_date;
    }

    /**
     * @param \DateTime $adding_date
     */
    public function setAddingDate($adding_date)
    {
        $this->adding_date = $adding_date;
    }

    /**
     * @return \DateTime
     */
    public function getLastEdit()
    {
        return $this->last_edit;
    }

    /**
     * @param \DateTime $last_edit
     */
    public function setLastEdit($last_edit)
    {
        $this->last_edit = $last_edit;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Business
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Business
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set city
     *
     * @param integer $city
     *
     * @return Business
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return int
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getOpenings()
    {
        return $this->openings;
    }

    /**
     * @param mixed $openings
     */
    public function setOpenings($openings)
    {
        $this->openings = $openings;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param mixed $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }



    /**
     * Set image
     *
     * @param string $image
     *
     * @return Business
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return Business
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return Business
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Business
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * @param mixed $responsable
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    /**
     * @return \DateTime
     */
    public function getDateofapproval()
    {
        return $this->dateofapproval;
    }

    /**
     * @param \DateTime $dateofapproval
     */
    public function setDateofapproval($dateofapproval)
    {
        $this->dateofapproval = $dateofapproval;
    }



    /*
     * path methods
     * */
    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getWebPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir().'/'.$this->image;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/businesses';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param string $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }



    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );
        /*$extension = $this->getFile()->guessExtension();
        if (!$extension) {
            // extension cannot be guessed
            $extension = 'bin';
        }
        $this->getFile()->move($this->getUploadRootDir(), rand(1, 99999).'.'.$extension);*/

        // set the path property to the filename where you've saved the file
        $this->image = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @param OpeningHours $openings
     * @return ArrayCollection
     */
    public function addOpenings(OpeningHours $openings)
    {
        if (!$this->openings->contains($openings)) {
            $this->getOpenings()->add($openings);
            $openings->setBusiness($this);
        }
        return $this->getOpenings();
    }

    public function removeOpening(OpeningHours $opening)
    {
        $this->openings->removeElement($opening);
    }
}
