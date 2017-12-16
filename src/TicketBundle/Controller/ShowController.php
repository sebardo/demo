<?php

namespace TicketBundle\Controller;

use TicketBundle\Entity\Show;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Show controller.
 *
 * @Route("admin/show")
 */
class ShowController extends Controller
{
    /**
     * Lists all show entities.
     *
     * @Route("/", name="ticket_show_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all show entities.
     *
     * @Route("/list.{_format}", name="ticket_show_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('TicketBundle:Show'));
        $jsonList->setLocale($request->getLocale());
        $response = $jsonList->get();
        

        return new JsonResponse($response);
    }

    /**
     * Creates a new show entity.
     *
     * @Route("/new", name="ticket_show_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $show = new Show();
        $form = $this->createForm('TicketBundle\Form\ShowType', $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush($show);

            $this->get('ticket_manager')->createEvent($show);
            
            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $show->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'show.created');
            
            return $this->redirectToRoute('ticket_show_show', array('id' => $show->getId()));
        }

        return array(
            'show' => $show,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a show entity.
     *
     * @Route("/{id}", name="ticket_show_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Show $show)
    {
        $deleteForm = $this->createDeleteForm($show);

        return array(
            'show' => $show,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing show entity.
     *
     * @Route("/{id}/edit", name="ticket_show_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Show $show)
    {
        $deleteForm = $this->createDeleteForm($show);
        $editForm = $this->createForm('TicketBundle\Form\ShowType', $show);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $show->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'show.edited');
            
            return $this->redirectToRoute('ticket_show_edit', array('id' => $show->getId()));
        }

        return array(
            'show' => $show,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a show entity.
     *
     * @Route("/{id}", name="ticket_show_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Show $show)
    {
        $form = $this->createDeleteForm($show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($show);
            $em->flush($show);
            
            $this->get('session')->getFlashBag()->add('success', 'show.deleted');
        }

        return $this->redirectToRoute('ticket_show_index');
    }

    /**
     * Creates a form to delete a show entity.
     *
     * @param Show $show The show entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Show $show)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_show_delete', array('id' => $show->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
