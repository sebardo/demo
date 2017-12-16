<?php

namespace TicketBundle\Tests\Controller;

use CoreBundle\Tests\CoreTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @class  EventControllerTest
 * @brief Test the  Event entity
 *
 * To run the testcase:
 * @code
 * php vendor/bin/phpunit -v src/TicketBundle/Tests/Controller/EventControllerTest.php
 * @endcode
 */
class EventControllerTest extends CoreTest
{
    /**
     * @code
     * php vendor/bin/phpunit -v --filter testEvent src/TicketBundle/Tests/Controller/EventControllerTest.php
     * @endcode
     * 
     */
    public function testEvent()
    {
        $container = $this->client->getContainer();
        $manager = $container->get('doctrine')->getManager();
        
        $events = $manager->getRepository('TicketBundle:Event')->findBy(array());
        
        foreach ($events as $event) {
            print_r($event->getName());echo PHP_EOL;
            print_r('    shows:'. count($event->getShows()));echo PHP_EOL;

            if(count($event->getTickets())>0){
                foreach ($event->getShows() as $show) {
                    print_r('        '. $show->getDate()->format('d/m/y'));echo PHP_EOL;
                }
                foreach ($event->getTickets() as $ticket) {
                    print_r('            tickets types: sector '. $ticket->getSector()->getName().', quantity '.$ticket->getQuantity().', price '.$ticket->getPrice());echo PHP_EOL;
                }
            }else{
                foreach ($event->getShows() as $show) {
                    print_r('        '. $show->getDate()->format('d/m/y'));echo PHP_EOL;
                    foreach ($show->getTickets() as $ticket) {
                        print_r('            tickets types: sector '. $ticket->getSector()->getName().', quantity '.$ticket->getQuantity().', price '.$ticket->getPrice());echo PHP_EOL;
                    }
                }
            }
        }
        die;
        
//        $this->assertTrue($this->client->getResponse()->isSuccessful());
//        $this->assertGreaterThan(0, $crawler->filter('html:contains("post '.$uid.'")')->count());
        
        
    }
}
