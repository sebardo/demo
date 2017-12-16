<?php
namespace TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TicketBundle\Entity\Place;
use TicketBundle\Entity\Scheme;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use TicketBundle\Entity\Show;

/**
 * Hall Entity class
 *
 * @ORM\Table(name="hall", indexes={@ORM\Index(columns={"slug"})})
 * @ORM\Entity(repositoryClass="TicketBundle\Entity\Repository\HallRepository")
 */
class Hall 
{
    
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
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="halls")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    private $place;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Scheme", mappedBy="hall", cascade={"persist", "remove"})
     */
    private $schemes;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->schemes = new ArrayCollection();
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
     * set place
     *
     * @param Place $place
     *
     * @return Hall
     */
    public function setPlace(Place $place)
    {
        $this->place = $place;

        return $this;
    }
    
    /**
     * set place
     *
     * @return Place
     */
    public function getPlace()
    {
        return $this->place;
    }
    
     /**
     * Add scheme
     *
     * @param Scheme $scheme
     *
     * @return Hall
     */
    public function addScheme(Scheme $scheme)
    {
        $this->schemes->add($scheme);

        return $this;
    }

    /**
     * Remove scheme
     *
     * @param Scheme $scheme
     */
    public function removeScheme(Scheme $scheme)
    {
        $this->schemes->removeElement($scheme);
    }

    /**
     * Get schemes
     *
     * @return ArrayCollection
     */
    public function getSchemes()
    {
        return $this->schemes;
    }
    
}
