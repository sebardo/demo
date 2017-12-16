<?php

namespace TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TicketingController extends Controller
{
    /**
     * @Route("/tickets", name="ticket_ticketing_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $events = $em->getRepository('TicketBundle:Event')->findBy(array());
        return array(
            'events' => $events
        );
    }
    
    /**
     * @Route("/events/{slug}", name="ticket_ticketing_event")
     * @Template()
     */
    public function eventAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('TicketBundle:Event')->findOneBySlug($slug);
        return array(
            'event' => $event
        );
    }
    
    /**
     * @Route("/events/{slug}/{sector}", name="ticket_ticketing_eventsector")
     * @Template("TicketBundle:Ticketing:event.html.twig")
     */
    public function eventSectorAction($slug, $sector)
    {
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('TicketBundle:Event')->findOneBySlug($slug);
        $sector = $em->getRepository('TicketBundle:Sector')->findOneBySlug($sector);
        
        return array(
            'event' => $event,
            'sector' => $sector
        );
    }
}
