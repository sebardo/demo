<?php

namespace TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use CoreBundle\Entity\Timestampable;
use CoreBundle\Entity\BaseActor;
use CoreBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use TicketBundle\Entity\Show;
use TicketBundle\Entity\Ticket;

/**
 * Event Entity class
 *
 * @ORM\Table(name="event", indexes={@ORM\Index(columns={"slug"})})
 * @ORM\Entity(repositoryClass="TicketBundle\Entity\Repository\EventRepository")
 */
class Event extends Timestampable
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
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="events")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    private $place;
    
    /**
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="Hall", inversedBy="events")
     * @ORM\JoinColumn(name="hall_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    private $hall;
    
    /**
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="Scheme", inversedBy="events")
     * @ORM\JoinColumn(name="scheme_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    private $scheme;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Show", inversedBy="events")
     * @ORM\JoinTable(name="events_shows",
     *                      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *                      inverseJoinColumns={@ORM\JoinColumn(name="show_id", referencedColumnName="id")})
     */
    private $shows;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="event", cascade={"persist", "remove"})
     */
    protected $tickets;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shows = new ArrayCollection();
        $this->tickets = new ArrayCollection(); 
    }
    
    /**
     * Entity to String
     */
    public function __toString() {
        return $this->name;
    }
    
    public function getPrice($currency='€') 
    {
        
        $prices = array();
        if(count($this->getTickets())>0){
            $prices = array();
            foreach ($this->getTickets() as $ticket) {
                $prices[] = $ticket->getPrice();
            }
            
        }else{
            foreach ($this->getShows() as $show) {
                foreach ($show->getTickets() as $ticket) {
                    $prices[] = $ticket->getPrice();
                }
            }
        }
        $min = min($prices);
        $max = max($prices);
        if($min == $max) return $min. ' '.$currency;
        return ' '. $min.$currency.'/'. $max.$currency .' ';

    }
    
    public function hasSeasonTickets() 
    {
        if(count($this->getTickets())>0){
            return true;
        }else{
            return false;
        }
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
     * set place
     *
     * @param Place $place
     *
     * @return Event
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
     * set hall
     *
     * @param Hall $hall
     *
     * @return Event
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
     * set scheme
     *
     * @param Scheme $scheme
     *
     * @return Event
     */
    public function setScheme(Scheme $scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }
    
    /**
     * set scheme
     *
     * @return Hall
     */
    public function getScheme()
    {
        return $this->scheme;
    }
    
    /**
     * Add show
     *
     * @param Show $show
     *
     * @return Show
     */
    public function addShow(Show $show)
    {
        $this->shows->add($show);

        return $this;
    }

    /**
     * Remove show
     *
     * @param Show $show
     */
    public function removeShow(Show $show)
    {
        $this->shows->removeElement($show);
    }

    /**
     * Get shows
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShows()
    {
        return $this->shows;
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