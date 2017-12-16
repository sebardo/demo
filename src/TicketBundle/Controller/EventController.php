<?php

namespace TicketBundle\Controller;

use TicketBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Event controller.
 *
 * @Route("admin/event")
 */
class EventController extends Controller
{
    /**
     * Lists all event entities.
     *
     * @Route("/", name="ticket_event_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return  array();
    }

    /**
     * Lists all event entities.
     *
     * @Route("/list.{_format}", name="ticket_event_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('TicketBundle:Event'));
        $jsonList->setLocale($request->getLocale());
        $response = $jsonList->get();
        
        return new JsonResponse($response);
    }

    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="ticket_event_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm('TicketBundle\Form\EventType', $event, array('uploadDir'=> 'uploads/images/event'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush($event);

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $event->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'event.created');
            
            return $this->redirectToRoute('ticket_event_show', array('id' => $event->getId()));
        }

        return array(
            'event' => $event,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="ticket_event_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);

        return  array(
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/{id}/edit", name="ticket_event_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('TicketBundle\Form\EventType', $event, array('uploadDir'=> 'uploads/images/event'));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
             //clean image form
            $this->get('core_manager')->cleanImageForm($request->files->get('ticketbundle_event'), $event);
            
            if($event->getRemoveImage()){
                $event->setImage(null);
            }
            
            $this->getDoctrine()->getManager()->flush();

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $event->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'event.edited');
            
            return $this->redirectToRoute('ticket_event_edit', array('id' => $event->getId()));
        }

        return array(
            'entity' => $event,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a event entity.
     *
     * @Route("/{id}", name="ticket_event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Event $event)
    {
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush($event);
            
            $this->get('session')->getFlashBag()->add('success', 'event.deleted');
        }

        return $this->redirectToRoute('ticket_event_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Hide/Show  a event entity.
     *
     * @Route("/{id}/visible")
     * @Method("POST")
     */
    public function visibleAction(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        
        if($event->isVisible()){
            $event->setVisible(false);
        }else{
            $event->setVisible(true);
        }
        
        $em->flush($event);

        $sub = new \stdClass();
        $sub->status = 'success';
        $sub->id = $event->getId();
        $sub->visible = $event->isVisible();
        return new JsonResponse($sub);

    }
    
    /**
     * Enable/Disable  a event entity.
     *
     * @Route("/{id}/enable")
     * @Method("POST")
     */
    public function enableAction(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        
        if($event->isActive()){
            $event->setActive(false);
        }else{
            $event->setActive(true);
        }
        
        $em->flush($event);

        $sub = new \stdClass();
        $sub->status = 'success';
        $sub->id = $event->getId();
        $sub->active = $event->isActive();
        return new JsonResponse($sub);

    }
    
}
