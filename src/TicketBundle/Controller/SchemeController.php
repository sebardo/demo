<?php

namespace TicketBundle\Controller;

use TicketBundle\Entity\Scheme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Scheme controller.
 *
 * @Route("admin/scheme")
 */
class SchemeController extends Controller
{
    /**
     * Lists all scheme entities.
     *
     * @Route("/", name="ticket_scheme_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all scheme entities.
     *
     * @Route("/list.{_format}", name="ticket_scheme_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('TicketBundle:Scheme'));
        $jsonList->setLocale($request->getLocale());
        $response = $jsonList->get();
        

        return new JsonResponse($response);
    }

    /**
     * Creates a new scheme entity.
     *
     * @Route("/new", name="ticket_scheme_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $scheme = new Scheme();
        $form = $this->createForm('TicketBundle\Form\SchemeType', $scheme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($scheme);
            $em->flush($scheme);

            $this->get('ticket_manager')->createScheme($scheme);
            
            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $scheme->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'scheme.created');
            
            return $this->redirectToRoute('ticket_scheme_show', array('id' => $scheme->getId()));
        }

        return array(
            'scheme' => $scheme,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a scheme entity.
     *
     * @Route("/{id}", name="ticket_scheme_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Scheme $scheme)
    {
        $deleteForm = $this->createDeleteForm($scheme);

        return array(
            'scheme' => $scheme,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing scheme entity.
     *
     * @Route("/{id}/edit", name="ticket_scheme_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Scheme $scheme)
    {
        $deleteForm = $this->createDeleteForm($scheme);
        $editForm = $this->createForm('TicketBundle\Form\SchemeType', $scheme);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $scheme->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'scheme.edited');
            
            return $this->redirectToRoute('ticket_scheme_edit', array('id' => $scheme->getId()));
        }

        return array(
            'scheme' => $scheme,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a scheme entity.
     *
     * @Route("/{id}", name="ticket_scheme_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Scheme $scheme)
    {
        $form = $this->createDeleteForm($scheme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($scheme);
            $em->flush($scheme);
            
            $this->get('session')->getFlashBag()->add('success', 'scheme.deleted');
        }

        return $this->redirectToRoute('ticket_scheme_index');
    }

    /**
     * Creates a form to delete a scheme entity.
     *
     * @param Scheme $scheme The scheme entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Scheme $scheme)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_scheme_delete', array('id' => $scheme->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
