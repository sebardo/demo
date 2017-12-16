<?php
namespace TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use TicketBundle\Entity\Event;
use DateTime;

/**
 * Sector Entity class
 *
 * @ORM\Table(name="event_show")
 * @ORM\Entity(repositoryClass="TicketBundle\Entity\Repository\ShowRepository")
 */
class Show 
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
    * @ORM\ManyToMany(targetEntity="Event", mappedBy="shows")
    */
    protected $events;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="show_date", type="datetime")
     */
    private $date;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank
     */
    private $description;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="show", cascade={"remove"})
     */
    private $tickets;
    
    /**
     * Constructor
     */
    public function __construct()
    { 
        $this->events = new ArrayCollection();
        $this->tickets = new ArrayCollection();        
    }
    
    /**
     * Entity to String
     */
    public function __toString() {
        return $this->date->format('d/m/y');
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
    
    /* Add event
     *
     * @param Event $event
     *
     * @return Show
     */
    public function addEvent(Event $event)
    {
        $this->events->add($event);

        return $this;
    }

    /**
     * Remove $event
     *
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvents()
    {
        return $this->events;
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
     * Set date
     *
     * @param DateTime $date
     *
     * @return Show
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }
    
    /**
     * Get date
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
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

        return $this;
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
