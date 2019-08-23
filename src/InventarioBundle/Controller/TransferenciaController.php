<?php

namespace InventarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InventarioBundle\Entity\Transferencia;
use InventarioBundle\Form\TransferenciaType;

/**
 * Transferencia controller.
 *
 * @Route("/transferencia")
 */
class TransferenciaController extends Controller
{
    /**
     * Lists all Transferencia entities.
     *
     * @Route("/", name="transferencia_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $transferencias = $em->getRepository('InventarioBundle:Transferencia')->findAll();

        return $this->render('@Inventario/transferencia/index.html.twig', array(
            'transferencias' => $transferencias,
        ));
    }

    /**
     * Creates a new Transferencia entity.
     *
     * @Route("/nuevo", name="transferencia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $transferencium = new Transferencia();
        $form = $this->createForm('InventarioBundle\Form\TransferenciaType', $transferencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transferencium);
            $em->flush();

            return $this->redirectToRoute('transferencia_index');
        }

        return $this->render('@Inventario/transferencia/new.html.twig', array(
            'transferencium' => $transferencium,
            'form' => $form->createView(),
            'accion'=>'Nuevo'

        ));
    }

    /**
     * Finds and displays a Transferencia entity.
     *
     * @Route("/mostrar/{id}", name="transferencia_show")
     * @Method("GET")
     */
    public function showAction(Transferencia $transferencium)
    {
        return $this->render('@Inventario/transferencia/show.html.twig', array(
            'transferencium' => $transferencium
        ));
    }

    /**
     * Displays a form to edit an existing Transferencia entity.
     *
     * @Route("/{id}/editar", name="transferencia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Transferencia $transferencium)
    {
        $editForm = $this->createForm('InventarioBundle\Form\TransferenciaType', $transferencium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transferencium);
            $em->flush();

            return $this->redirectToRoute('transferencia_index');
        }

        return $this->render('@Inventario/transferencia/new.html.twig', array(
            'transferencium' => $transferencium,
            'form' => $editForm->createView(),
            'accion'=>'Editar'

        ));
    }

    /**
     * Deletes a Transferencia entity.
     * @Route("/eliminar", name="transferencia_delete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $obj = $this->getDoctrine()->getRepository('InventarioBundle:Transferencia')->find($id);
        $em->remove($obj);
        $em->flush();
        $this->addFlash('warning', 'Se eliminó satisfactoriamente el elemento');
        return $this->redirectToRoute('transferencia_index');
    }

}
