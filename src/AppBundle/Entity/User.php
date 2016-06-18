<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Avanzu\AdminThemeBundle\Model\UserInterface as ThemeUser;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="users")
 */
class User extends BaseUser implements ThemeUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="BusinessBundle\Entity\Visits", mappedBy="visitor")
     * @ORM\JoinColumn(name="id", referencedColumnName="visitor")
     */
    protected $id;
    /**
     * @Assert\File(maxSize="2048k")
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $profilePictureFile;

    // for temporary storage
    private $tempProfilePicturePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $profilePicturePath;
    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="HomeBundle\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="BusinessBundle\Entity\Business", mappedBy="owner", fetch="EAGER")
     */
    private $businesses;
    /**
     * @ORM\OneToMany(targetEntity="BusinessBundle\Entity\Review", mappedBy="reviewer")
     */
    private $reviews;

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
     * @return mixed
     */
    public function getBusinesses()
    {
        return $this->businesses;
    }

    /**
     * @param mixed $businesses
     */
    public function setBusinesses($businesses)
    {
        $this->businesses = $businesses;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * Add post
     *
     * @param \HomeBundle\Entity\Post $post
     *
     * @return User
     */
    public function addPost(\HomeBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \HomeBundle\Entity\Post $post
     */
    public function removePost(\HomeBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->posts = new ArrayCollection();
        $this->businesses = $this->getBusinesses();
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Sets the file used for profile picture uploads
     *
     * @param UploadedFile $file
     * @return object
     */
    public function setProfilePictureFile(UploadedFile $file = null) {
        // set the value of the holder
        $this->profilePictureFile       =   $file;
        // check if we have an old image path
        if (isset($this->profilePicturePath)) {
            // store the old name to delete after the update
            $this->tempProfilePicturePath = $this->profilePicturePath;
            $this->profilePicturePath = null;
        } else {
            $this->profilePicturePath = 'initial';
        }

        return $this;
    }

    /**
     * Get the file used for profile picture uploads
     *
     * @return UploadedFile
     */
    public function getProfilePictureFile() {

        return $this->profilePictureFile;
    }

    /**
     * Set profilePicturePath
     *
     * @param string $profilePicturePath
     * @return User
     */
    public function setProfilePicturePath($profilePicturePath)
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    /**
     * Get profilePicturePath
     *
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * Get the absolute path of the profilePicturePath
     */
    public function getProfilePictureAbsolutePath() {
        return null === $this->profilePicturePath
            ? null
            : $this->getUploadRootDir().'/'.$this->profilePicturePath;
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    /**
     * Specifies where in the /web directory profile pic uploads are stored
     *
     * @return string
     */
    protected function getUploadDir() {
        // the type param is to change these methods at a later date for more file uploads
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/profilepics';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebProfilePicturePath() {

        return '/'.$this->getUploadDir().'/'.$this->getProfilePicturePath();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadProfilePicture() {
        if (null !== $this->getProfilePictureFile()) {
            // a file was uploaded
            // generate a unique filename
            $filename = $this->generateRandomProfilePictureFilename();
            $this->setProfilePicturePath($filename.'.'.$this->getProfilePictureFile()->guessExtension());
        }
    }

    /**
     * Generates a 32 char long random filename
     *
     * @return string
     */
    public function generateRandomProfilePictureFilename() {
        $randomString = base64_encode(random_bytes(32));
        return $randomString;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadProfilePicture() {
        // check there is a profile pic to upload
        if ($this->getProfilePictureFile() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getProfilePictureFile()->move($this->getUploadRootDir(), $this->getProfilePicturePath());

        // check if we have an old image
        if (isset($this->tempProfilePicturePath) && file_exists($this->getUploadRootDir().'/'.$this->tempProfilePicturePath)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempProfilePicturePath);
            // clear the temp image path
            $this->tempProfilePicturePath = null;
        }
        $this->profilePictureFile = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeProfilePictureFile()
    {
        if ($file = $this->getProfilePictureAbsolutePath() && file_exists($this->getProfilePictureAbsolutePath())) {
            unlink($file);
        }
    }


    public function getAvatar()
    {
        return $this->getProfilePictureAbsolutePath();
    }

    public function getName()
    {
        return $this->getUsername();
    }

    public function getMemberSince()
    {
        return $this->getLastLogin();
    }

    public function isOnline()
    {
        return true;
    }

    public function getIdentifier()
    {
        return $this->getId();
    }

    public function getTitle()
    {
        $this->getUsername();
    }
}