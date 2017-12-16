<?php

namespace TicketBundle\Controller;

use TicketBundle\Entity\Sector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Sector controller.
 *
 * @Route("admin/sector")
 */
class SectorController extends Controller
{
    /**
     * Lists all sector entities.
     *
     * @Route("/", name="ticket_sector_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all sector entities.
     *
     * @Route("/list.{_format}", name="ticket_sector_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('TicketBundle:Sector'));
        $jsonList->setLocale($request->getLocale());
        $response = $jsonList->get();
        

        return new JsonResponse($response);
    }

    /**
     * Creates a new sector entity.
     *
     * @Route("/new", name="ticket_sector_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $sector = new Sector();
        $form = $this->createForm('TicketBundle\Form\SectorType', $sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sector);
            $em->flush($sector);

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $sector->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'sector.created');
            
            return $this->redirectToRoute('ticket_sector_show', array('id' => $sector->getId()));
        }

        return array(
            'sector' => $sector,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a sector entity.
     *
     * @Route("/{id}", name="ticket_sector_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Sector $sector)
    {
        $deleteForm = $this->createDeleteForm($sector);

        return array(
            'sector' => $sector,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing sector entity.
     *
     * @Route("/{id}/edit", name="ticket_sector_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Sector $sector)
    {
        $deleteForm = $this->createDeleteForm($sector);
        $editForm = $this->createForm('TicketBundle\Form\SectorType', $sector);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //if come from popup
            if ($request->isXMLHttpRequest()) {         
                return new JsonResponse(array(
                            'id' => $sector->getId(), 
                        ));
            }
            $this->get('session')->getFlashBag()->add('success', 'sector.edited');
            
            return $this->redirectToRoute('ticket_sector_edit', array('id' => $sector->getId()));
        }

        return array(
            'sector' => $sector,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a sector entity.
     *
     * @Route("/{id}", name="ticket_sector_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Sector $sector)
    {
        $form = $this->createDeleteForm($sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sector);
            $em->flush($sector);
            
            $this->get('session')->getFlashBag()->add('success', 'sector.deleted');
        }

        return $this->redirectToRoute('ticket_sector_index');
    }

    /**
     * Creates a form to delete a sector entity.
     *
     * @param Sector $sector The sector entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sector $sector)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_sector_delete', array('id' => $sector->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
