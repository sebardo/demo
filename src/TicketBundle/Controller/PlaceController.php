<?php

namespace TicketBundle\Controller;

use TicketBundle\Entity\Place;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Place controller.
 *
 * @Route("admin/place")
 */
class PlaceController extends Controller
{
    /**
     * Lists all place entities.
     *
     * @Route("/", name="ticket_place_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all place entities.
     *
     * @Route("/list.{_format}", name="ticket_place_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('TicketBundle:Place'));
        $jsonList->setLocale($request->getLocale());
        $response = $jsonList->get();
        

        return new JsonResponse($response);
    }

    /**
     * Creates a new place entity.
     *
     * @Route("/new", name="ticket_place_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $place = new Place();
        $form = $this->createForm('TicketBundle\Form\PlaceType', $place, array('uploadDir'=> 'uploads/images/event'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush($place);

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $place->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'place.created');
            
            return $this->redirectToRoute('ticket_place_show', array('id' => $place->getId()));
        }

        return array(
            'place' => $place,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a place entity.
     *
     * @Route("/{id}", name="ticket_place_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Place $place)
    {
        $deleteForm = $this->createDeleteForm($place);

        return array(
            'place' => $place,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing place entity.
     *
     * @Route("/{id}/edit", name="ticket_place_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Place $place)
    {
        $deleteForm = $this->createDeleteForm($place);
        $editForm = $this->createForm('TicketBundle\Form\PlaceType', $place, array('uploadDir'=> 'uploads/images/event'));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
              //clean image form
            $this->get('core_manager')->cleanImageForm($request->files->get('ticketbundle_place'), $place);
            
            $this->getDoctrine()->getManager()->flush();

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $place->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'place.edited');
            
            return $this->redirectToRoute('ticket_place_edit', array('id' => $place->getId()));
        }

        return array(
            'entity' => $place,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a place entity.
     *
     * @Route("/{id}", name="ticket_place_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Place $place)
    {
        $form = $this->createDeleteForm($place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($place);
            $em->flush($place);
            
            $this->get('session')->getFlashBag()->add('success', 'place.deleted');
        }

        return $this->redirectToRoute('ticket_place_index');
    }

    /**
     * Creates a form to delete a place entity.
     *
     * @param Place $place The place entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Place $place)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_place_delete', array('id' => $place->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
