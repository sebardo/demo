<?php

namespace TicketBundle\Controller;

use TicketBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Ticket controller.
 *
 * @Route("admin/ticket")
 */
class TicketController extends Controller
{
    /**
     * Lists all ticket entities.
     *
     * @Route("/", name="ticket_ticket_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all ticket entities.
     *
     * @Route("/list.{_format}", name="ticket_ticket_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('TicketBundle:Ticket'));
        $jsonList->setLocale($request->getLocale());
        $response = $jsonList->get();
        

        return new JsonResponse($response);
    }

    /**
     * Creates a new ticket entity.
     *
     * @Route("/new", name="ticket_ticket_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $ticket = new Ticket();
        $form = $this->createForm('TicketBundle\Form\TicketType', $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush($ticket);

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $ticket->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'ticket.created');
            
            return $this->redirectToRoute('ticket_ticket_show', array('id' => $ticket->getId()));
        }

        return array(
            'ticket' => $ticket,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ticket entity.
     *
     * @Route("/{id}", name="ticket_ticket_show")
     * @Method("GET")
     */
    public function showAction(Ticket $ticket)
    {
        $deleteForm = $this->createDeleteForm($ticket);

        return $this->render('showshassectors/show.html.twig', array(
            'ticket' => $ticket,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ticket entity.
     *
     * @Route("/{id}/edit", name="ticket_ticket_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Ticket $ticket)
    {
        $deleteForm = $this->createDeleteForm($ticket);
        $editForm = $this->createForm('TicketBundle\Form\TicketType', $ticket);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $ticket->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'ticket.edited');
            
            return $this->redirectToRoute('ticket_ticket_edit', array('id' => $ticket->getId()));
        }

        return array(
            'ticket' => $ticket,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ticket entity.
     *
     * @Route("/{id}", name="ticket_ticket_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ticket $ticket)
    {
        $form = $this->createDeleteForm($ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ticket);
            $em->flush($ticket);
            
            $this->get('session')->getFlashBag()->add('success', 'ticket.deleted');
        }

        return $this->redirectToRoute('ticket_ticket_index');
    }

    /**
     * Creates a form to delete a ticket entity.
     *
     * @param Ticket $ticket The ticket entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ticket $ticket)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_ticket_delete', array('id' => $ticket->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
