<?php
namespace TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use TicketBundle\Entity\Scheme;
use TicketBundle\Entity\ShowsHasSectors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sector Entity class
 *
 * @ORM\Table(name="sector", indexes={@ORM\Index(columns={"slug"})})
 * @ORM\Entity(repositoryClass="TicketBundle\Entity\Repository\SectorRepository")
 */
class Sector 
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
     * @ORM\Column(name="seatkey", type="string", length=255)
     */
    private $key;
    
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
     * @var Scheme
     *
     * @ORM\ManyToOne(targetEntity="Scheme", inversedBy="sectors")
     * @ORM\JoinColumn(name="scheme_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    private $scheme;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="sector", cascade={"remove"})
     */
    private $tickets;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
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
     * @return Sector
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
     * Set key
     *
     * @param string $key
     *
     * @return Sector
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
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
     * @return Complex
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }
    
     /**
     * set scheme
     *
     * @param Scheme $scheme
     *
     * @return Sector
     */
    public function setScheme(Scheme $scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }
    
    /**
     * set scheme
     *
     * @return Scheme
     */
    public function getScheme()
    {
        return $this->scheme;
    }
    
    
    /**
     * Add ticket
     *
     * @param Tickets $ticket
     *
     * @return Show
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets->add($ticket);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Tickets $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
    
}
