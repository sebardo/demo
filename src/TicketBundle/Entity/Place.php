<?php
namespace TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CoreBundle\Entity\Image;
use CoreBundle\Entity\BaseActor;
use TicketBundle\Entity\Hall;
use PaymentBundle\Entity\Addressable;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Place Entity class
 *
 * @ORM\Table(name="place", indexes={@ORM\Index(columns={"slug"})})
 * @ORM\Entity(repositoryClass="TicketBundle\Entity\Repository\PlaceRepository")
 */
class Place  extends Addressable
{
    use \CoreBundle\Entity\MetasableTrait;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="technical_details", type="text", nullable=true)
     */
    private $technicalDetails;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="CoreBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="set null")
     */
    private $image;
    
    public $removeImage;

    /**
     * Dynamic
     */
    protected $actor;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Hall", mappedBy="place", cascade={"persist", "remove"})
     */
    private $halls;
    
     /**
     * Constructor
     */
    public function __construct()
    {
        $this->halls = new ArrayCollection();
    }
    
    /**
     * Entity to String
     */
    public function __toString() {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Brand
     */
    public function setName($name)
    {
        $this->name = $name;
        if($this->getMetaTitle() == '') $this->setMetaTitle($this->getName());

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
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        if($this->getMetaDescription() == '') $this->setMetaDescription($description);       

        return $this;
    }

    /**
     * Get $technicalDetails
     *
     * @return string
     */
    public function getTechnicalDetails()
    {
        return $this->technicalDetails;
    }

    /**
     * Set technicalDetails
     *
     * @param string $technicalDetails
     *
     * @return Product
     */
    public function setTechnicalDetails($technicalDetails)
    {
        $this->technicalDetails = $technicalDetails;

        return $this;
    }
    
    /**
     * Is visible?
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Scheme
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }
    
    /**
     * Is active?
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Place
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Set image
     *
     * @param Image $image
     *
     * @return Brand
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }
    
    public function setRemoveImage($removeImage)
    {
        $this->removeImage = $removeImage;

        return $this->removeImage;
    }

    public function getRemoveImage()
    {
        return $this->removeImage;
    }

     /**
     * Set actor
     *
     * @param BaseActor $actor
     *
     * @return Product
     */
    public function setActor(BaseActor $actor)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return Actor
     */
    public function getActor()
    {
        return $this->actor;
    }
    
    /**
     * Add hall
     *
     * @param Hall $hall
     *
     * @return Place
     */
    public function addHall(Hall $hall)
    {
        $this->halls->add($hall);

        return $this;
    }

    /**
     * Remove hall
     *
     * @param Product $hall
     */
    public function removeHall(Hall $hall)
    {
        $this->halls->removeElement($hall);
    }

    /**
     * Get halls
     *
     * @return ArrayCollection
     */
    public function getHalls()
    {
        return $this->halls;
    }
}
