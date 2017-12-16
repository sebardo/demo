<?php
namespace TicketBundle\DataFixtures\ORM;

use CoreBundle\DataFixtures\SqlScriptFixture;
use TicketBundle\Entity\Event;
use TicketBundle\Entity\Place;
use TicketBundle\Entity\Hall;
use TicketBundle\Entity\Scheme;
use TicketBundle\Entity\Sector;
use TicketBundle\Entity\Show;
use TicketBundle\Entity\Ticket;
use DateTime;
use CoreBundle\Entity\Parameter;


/*
 * php app/console doctrine:fixtures:load --fixtures=src/TicketBundle/DataFixtures/ORM/LoadTicketData.php
 */
class LoadTicketData extends SqlScriptFixture
{
    /**
     * There two kind of fixtures
     * Bundle fixtures: all info bundle needed
     * Dev fixtures: info for testing porpouse
     */
    public function createFixtures()
    {
        /**
         * Bundle fixtures
         */
        if($this->container->getParameter('core.fixtures_bundle_ticket')){
            $this->runSqlScript('Translation.sql');
        }
        
        /**
         * Dev fixtures
         */
        if($this->container->getParameter('core.fixtures_dev_ticket')){
            //get dinamic product class
            $productClass = $this->container->get('core_manager')->getProductClass();
 
            //Create a sale (create all entities needed)
            $actor = $this->getManager()->getRepository('CoreBundle:BaseActor')->findOneByUsername('user');
            $actor2 = $this->getManager()->getRepository('CoreBundle:BaseActor')->findOneByUsername('user2');
            $country = $this->getManager()->getRepository('CoreBundle:Country')->find('es');

            $event = new Event();
            $event->setName('Event test 1');
            $event->setDescription('Description ticket test 1 for testing.');
            $event->setActor($actor);
            $event->setVisible(true);
            $event->setActive(true);
            
            $event2 = new Event();
            $event2->setName('Event test 2');
            $event2->setDescription('Description ticket test 1 for testing.');
            $event2->setActor($actor);
            $event2->setVisible(true);
            $event2->setActive(true);
            
            $event3 = new Event();
            $event3->setName('Event test 3');
            $event3->setDescription('Description ticket test 1 for testing.');
            $event3->setActor($actor);
            $event3->setVisible(true);
            $event3->setActive(true);
            
            $season = new Event();
            $season->setName('Abono test 1');
            $season->setDescription('Description ticket test X for testing.');
            $season->setActor($actor);
            $season->setVisible(true);
            $season->setActive(true);
           
            $place = new Place();
            $place->setName('Place 1');
            $place->setDescription('Description place 1');
            $place->setVisible(true);
            $place->setActive(true);
            $place->setActor($actor);
            $place->setAddress('Address 123, P3 B3');
            $event->setPlace($place);
            $event2->setPlace($place);
            $event3->setPlace($place);
            $season->setPlace($place);
            
            $hall = new Hall();
            $hall->setName('Hall 1 (place 1)');
            $hall->setVisible(true);
            $hall->setActive(true);
            $hall->setPlace($place);
            $event->setHall($hall);
            $event2->setHall($hall);
            $event3->setHall($hall);
            $season->setHall($hall);
            
            $scheme = new Scheme();
            $scheme->setName('Scheme 1 (place 1 / hall 1)');
            $scheme->setSeating(100);
            $scheme->setDefault(true);
            $scheme->setVisible(true);
            $scheme->setActive(true);  
            $scheme->setSchemeKey('898c462b-6351-4b65-b494-70e754f26649');
            $scheme->setHall($hall);
            $event->setScheme($scheme);
            $event2->setScheme($scheme);
            $event3->setScheme($scheme);
            $season->setScheme($scheme);
                    
            $sector = new Sector();
            $sector->setName('Sector 1');
            $sector->setVisible(true);
            $sector->setActive(true);
            $sector->setScheme($scheme);
            $sector->setKey(1);
            
            $sector2 = new Sector();
            $sector2->setName('Sector 2');
            $sector2->setVisible(true);
            $sector2->setActive(true);
            $sector2->setScheme($scheme);
            $sector2->setKey(2);
            
            $date = new DateTime();
            $date = $date->modify('+1 month');
            $show = new Show();
            $show->setDate($date);
            $show->setDescription('Show description');
            $show->addEvent($event);
            $event->addShow($show);
            $season->addShow($show);
            
            $date2 = new DateTime();
            $date2 = $date2->modify('+2 months');
            $show2 = new Show();
            $show2->setDate($date2);
            $show2->setDescription('Show description 2');
            $show2->addEvent($event2);
            $event2->addShow($show2);
            $season->addShow($show2);
            
            $date3 = new DateTime();
            $date3 = $date3->modify('+3 months');
            $show3 = new Show();
            $show3->setDate($date3);
            $show3->setDescription('Show description 3');
            $show3->addEvent($event3);
            $event3->addShow($show3);
            $season->addShow($show3);
            
            //Show 1
            $ticket = new Ticket();
            $ticket->setSector($sector);
            $ticket->setShow($show);
            $ticket->setQuantity(1000);
            $ticket->setNumbered(uniqid());
            $ticket->setPrice(200);
            $ticket->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $ticket->setDiscount(10);
            $ticket->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $ticket->setActive(true);
            
            $ticket2 = new Ticket();
            $ticket2->setSector($sector2);
            $ticket2->setShow($show);
            $ticket2->setQuantity(1000);
            $ticket2->setNumbered(uniqid());
            $ticket2->setPrice(150);
            $ticket2->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $ticket2->setDiscount(10);
            $ticket2->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $ticket2->setActive(true);

            //Show 2
            $ticket3 = new Ticket();
            $ticket3->setSector($sector);
            $ticket3->setShow($show2);
            $ticket3->setQuantity(1000);
            $ticket3->setNumbered(uniqid());
            $ticket3->setPrice(180);
            $ticket3->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $ticket3->setDiscount(10);
            $ticket3->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $ticket3->setActive(true);
            
            $ticket4 = new Ticket();
            $ticket4->setSector($sector2);
            $ticket4->setShow($show2);
            $ticket4->setQuantity(1000);
            $ticket4->setNumbered(uniqid());
            $ticket4->setPrice(150);
            $ticket4->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $ticket4->setDiscount(10);
            $ticket4->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $ticket4->setActive(true);
            
            
            //Show 3
            $ticket5 = new Ticket();
            $ticket5->setSector($sector);
            $ticket5->setShow($show3);
            $ticket5->setQuantity(1000);
            $ticket5->setNumbered(uniqid());
            $ticket5->setPrice(190);
            $ticket5->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $ticket5->setDiscount(10);
            $ticket5->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $ticket5->setActive(true);
            
            $ticket6 = new Ticket();
            $ticket6->setSector($sector2);
            $ticket6->setShow($show3);
            $ticket6->setQuantity(1000);
            $ticket6->setNumbered(uniqid());
            $ticket6->setPrice(160);
            $ticket6->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $ticket6->setDiscount(10);
            $ticket6->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $ticket6->setActive(true);
            
            
            //Abono 1
            $seasonTicket = new Ticket();
            $seasonTicket->setSector($sector);
            $seasonTicket->setEvent($season);
            $seasonTicket->setQuantity(1000);
            $seasonTicket->setNumbered(uniqid());
            $seasonTicket->setPrice(300);
            $seasonTicket->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $seasonTicket->setDiscount(10);
            $seasonTicket->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $seasonTicket->setActive(true);
            $season->addTicket($seasonTicket);
            
            $seasonTicket2 = new Ticket();
            $seasonTicket2->setSector($sector2);
            $seasonTicket2->setEvent($season);
            $seasonTicket2->setQuantity(1000);
            $seasonTicket2->setNumbered(uniqid());
            $seasonTicket2->setPrice(250);
            $seasonTicket2->setPriceType(Ticket::PRICE_TYPE_FIXED);
            $seasonTicket2->setDiscount(10);
            $seasonTicket2->setDiscountType(Ticket::PRICE_TYPE_FIXED);
            $seasonTicket2->setActive(true);
            $season->addTicket($seasonTicket2);
            
            $this->getManager()->persist($event);
            $this->getManager()->persist($event2);
            $this->getManager()->persist($event3);
            $this->getManager()->persist($season);
            $this->getManager()->persist($place);
            $this->getManager()->persist($hall);
            $this->getManager()->persist($scheme);
            $this->getManager()->persist($sector);
            $this->getManager()->persist($sector2);
            $this->getManager()->persist($show);
            $this->getManager()->persist($show2);
            $this->getManager()->persist($show3);
            $this->getManager()->persist($ticket);
            $this->getManager()->persist($ticket2);
            $this->getManager()->persist($ticket3);
            $this->getManager()->persist($ticket4);
            $this->getManager()->persist($ticket5);
            $this->getManager()->persist($ticket6);
            $this->getManager()->persist($seasonTicket);
            $this->getManager()->persist($seasonTicket2);
        }
       
            
        $this->getManager()->flush();
        
        /*
         * Add parameters
         */
        $this->addParameters();
        
    }
    
    
     private function addParameters()
    {
        //Parameter
        $param = new Parameter();
        $param->setParameter('seats.io');
        $param->setValue(json_encode(array(
                'secret_key'=> '225ff6a9-a8b1-43e0-ac36-44b84e5af818',
                'public_key'=> 'e670c4f6-aef3-42f4-abc5-99edd767ad08',
                'designer_key'=> '0a6dc3d8-0d93-4a89-bfec-673801adbd6f'
            )));
        $this->getManager()->persist($param);
 
        
        
        $this->getManager()->flush();
    }
   
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }

}
