<?php

namespace TicketBundle\Controller;

use TicketBundle\Entity\Hall;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Hall controller.
 *
 * @Route("admin/hall")
 */
class HallController extends Controller
{
    /**
     * Lists all hall entities.
     *
     * @Route("/", name="ticket_hall_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all hall entities.
     *
     * @Route("/list.{_format}", name="ticket_hall_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('TicketBundle:Hall'));
        $jsonList->setLocale($request->getLocale());
        $response = $jsonList->get();
        

        return new JsonResponse($response);
    }

    /**
     * Creates a new hall entity.
     *
     * @Route("/new", name="ticket_hall_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $hall = new Hall();
        $form = $this->createForm('TicketBundle\Form\HallType', $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hall);
            $em->flush($hall);

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $hall->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'hall.created');
            
            return $this->redirectToRoute('ticket_hall_show', array('id' => $hall->getId()));
        }

        return array(
            'hall' => $hall,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a hall entity.
     *
     * @Route("/{id}", name="ticket_hall_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Hall $hall)
    {
        $deleteForm = $this->createDeleteForm($hall);

        return array(
            'hall' => $hall,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing hall entity.
     *
     * @Route("/{id}/edit", name="ticket_hall_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Hall $hall)
    {
        $deleteForm = $this->createDeleteForm($hall);
        $editForm = $this->createForm('TicketBundle\Form\HallType', $hall);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $hall->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'hall.edited');
            
            return $this->redirectToRoute('ticket_hall_edit', array('id' => $hall->getId()));
        }

        return array(
            'hall' => $hall,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a hall entity.
     *
     * @Route("/{id}", name="ticket_hall_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Hall $hall)
    {
        $form = $this->createDeleteForm($hall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hall);
            $em->flush($hall);
            
            $this->get('session')->getFlashBag()->add('success', 'hall.deleted');
        }

        return $this->redirectToRoute('ticket_hall_index');
    }

    /**
     * Creates a form to delete a hall entity.
     *
     * @param Hall $hall The hall entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hall $hall)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_hall_delete', array('id' => $hall->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
