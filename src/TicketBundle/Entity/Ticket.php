<?php
namespace TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use CoreBundle\Entity\Timestampable;
use TicketBundle\Entity\Show;
use TicketBundle\Entity\Sector;
use CoreBundle\Entity\BaseActor;

/**
 * @ORM\Entity(repositoryClass="TicketBundle\Entity\Repository\TicketRepository")
 * @ORM\Table(name="ticket")
 */
class Ticket
{
    use \PaymentBundle\Entity\ProductTrait;
    const PRICE_TYPE_FIXED = 0;
    const PRICE_TYPE_PERCENT = 1;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var License
     *
     * @ORM\ManyToOne(targetEntity="Show", inversedBy="tickets" )
     * @ORM\JoinColumn(name="show_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    protected $show;
    
    /**
     * @var License
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="tickets" )
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=true, onDelete="set null")
     */
    protected $event;
    
     /**
     * @var TypographyHasWeight
     *
     * @ORM\ManyToOne(targetEntity="Sector", inversedBy="tickets")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id", nullable=true, onDelete="set null")
     * @Assert\NotBlank
     */
    protected $sector;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    public $quantity;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="numbered", type="string", nullable=true)
     */
    public $numbered;
    
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

//    /**
//     * Dynamic
//     */
//    protected $actor;

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
     * Get show
     *
     * @return Ticket
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Set show
     *
     * @param Show $show
     *
     * @return Ticket
     */
    public function setShow(Show $show)
    {
        $this->show = $show;

        return $this;
    }
    
    /**
     * Get event
     *
     * @return Ticket
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set event
     *
     * @param Event $event
     *
     * @return Ticket
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }
    
    /**
     * Get sector
     *
     * @return Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set sector
     *
     * @param Sector $sector
     *
     * @return Ticket
     */
    public function setSector(Sector $sector)
    {
        $this->sector = $sector;

        return $this;
    }
   
    /**
     * Set quantity
     *
     * @param string $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /**
     * Set numbered
     *
     * @param string $numbered
     * @return $this
     */
    public function setNumbered($numbered)
    {
        $this->numbered = $numbered;
        return $this;
    }

    /**
     * Get numbered
     *
     * @return string
     */
    public function getNumbered()
    {
        return $this->numbered;
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
 
}

