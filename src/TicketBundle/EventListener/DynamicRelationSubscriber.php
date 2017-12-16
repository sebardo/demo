<?php
namespace TicketBundle\EventListener;

use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\EventSubscriber;

class DynamicRelationSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    protected $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getReflectionClass();
         
        //dynamic actor
        if ($metadata->getName() == 'TicketBundle\Entity\Place') {
            $metadata->mapManyToOne(array(
                'targetEntity' => $this->mapping['baseactor']['entity'],
                'fieldName' => 'actor',
                'joinColumns' => array(array('name' => 'actor_id')),
                'inversedBy' => 'places',
            ));
        }elseif ($metadata->getName() == $this->mapping['baseactor']['entity']) {
            $metadata->mapOneToMany(array(
                'targetEntity' => $class->getName(),
                'fieldName' => 'places',
                'mappedBy' => 'actor',
            ));
        }
        if ($metadata->getName() == 'TicketBundle\Entity\Event') {
            $metadata->mapManyToOne(array(
                'targetEntity' => $this->mapping['baseactor']['entity'],
                'fieldName' => 'actor',
                'joinColumns' => array(array('name' => 'actor_id')),
                'inversedBy' => 'events',
            ));
        }elseif ($metadata->getName() == $this->mapping['baseactor']['entity']) {
            $metadata->mapOneToMany(array(
                'targetEntity' => $class->getName(),
                'fieldName' => 'events',
                'mappedBy' => 'actor',
            ));
        }
//        if ($metadata->getName() == 'TicketBundle\Entity\Ticket') {
//            $metadata->mapManyToOne(array(
//                'targetEntity' => $this->mapping['baseactor']['entity'],
//                'fieldName' => 'actor',
//                'joinColumns' => array(array('name' => 'actor_id')),
//                'inversedBy' => 'tickets',
//            ));
//        }elseif ($metadata->getName() == $this->mapping['baseactor']['entity']) {
//            $metadata->mapOneToMany(array(
//                'targetEntity' => $class->getName(),
//                'fieldName' => 'tickets',
//                'mappedBy' => 'actor',
//            ));
//        }
//        
//        //dynamic product
//        if ($metadata->getName() == 'TicketBundle\Entity\Ticket') {
//            $metadata->mapOneToOne(array(
//                'targetEntity' => $this->mapping['product']['entity'],
//                'fieldName' => 'product',
//                'joinColumns' => array(array('nullable' => 'true')),
//                'inversedBy' => 'ticket',
//            ));
//        }elseif ($metadata->getName() == $this->mapping['product']['entity']) {
//            $metadata->mapOneToOne(array(
//                'targetEntity' => $class->getName(),
//                'fieldName' => 'ticket',
//                'mappedBy' => 'product',
//            ));
//        }

    }
}
                    