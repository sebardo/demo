<?php
namespace TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TicketBundle\Entity\Hall;
use TicketBundle\Entity\Sector;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use TicketBundle\Entity\Show;

/**
 * Scheme Entity class
 *
 * @ORM\Table(name="scheme", indexes={@ORM\Index(columns={"slug"})})
 * @ORM\Entity(repositoryClass="TicketBundle\Entity\Repository\SchemeRepository")
 */
class Scheme 
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
     * @ORM\Column(name="scheme_key", type="string", length=255, nullable=true)
     */
    private $schemeKey;
    
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
     * @var integer
     *
     * @ORM\Column(name="seating", type="integer")
     */
    private $seating;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="default_scheme", type="boolean")
     */
    private $default;
    
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
     * @var Hall
     *
     * @ORM\ManyToOne(targetEntity="Hall", inversedBy="schemes")
     * @ORM\JoinColumn(name="hall_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    private $hall;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sector", mappedBy="scheme", cascade={"persist", "remove"})
     */
    private $sectors;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sectors = new ArrayCollection();
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
     * Set schemeKey
     *
     * @param string $schemeKey
     *
     * @return Scheme
     */
    public function setSchemeKey($schemeKey)
    {
        $this->schemeKey = $schemeKey;

        return $this;
    }

    /**
     * Get schemeKey
     *
     * @return string
     */
    public function getSchemeKey()
    {
        return $this->schemeKey;
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
     * Set seating
     *
     * @param integer $seating
     *
     * @return seating
     */
    public function setSeating($seating)
    {
        $this->seating = $seating;

        return $this;
    }

    /**
     * Get seating
     *
     * @return integer
     */
    public function getSeating()
    {
        return $this->seating;
    }
    
    /**
     * Is default?
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Set default
     *
     * @param boolean $default
     *
     * @return Scheme
     */
    public function setDefault($default)
    {
        $this->default = $default;

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
     * @return Scheme
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

     /**
     * set hall
     *
     * @param Hall $hall
     *
     * @return Scheme
     */
    public function setHall(Hall $hall)
    {
        $this->hall = $hall;

        return $this;
    }
    
    /**
     * set hall
     *
     * @return Hall
     */
    public function getHall()
    {
        return $this->hall;
    }
    
    /**
     * Add sector
     *
     * @param Sector $sector
     *
     * @return Scheme
     */
    public function addSector(Sector $sector)
    {
        $this->sectors->add($sector);

        return $this;
    }

    /**
     * Remove sector
     *
     * @param Sector $sector
     */
    public function removeSector(Sector $sector)
    {
        $this->sectors->removeElement($sector);
    }

    /**
     * Get sectors
     *
     * @return ArrayCollection
     */
    public function getSectors()
    {
        return $this->sectors;
    }
   
}
