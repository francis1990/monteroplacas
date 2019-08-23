<?php

namespace InventarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InventarioBundle\Entity\Motivo;
use InventarioBundle\Form\MotivoType;

/**
 * Motivo controller.
 *
 * @Route("/motivo")
 */
class MotivoController extends Controller
{
    /**
     * Lists all Motivo entities.
     *
     * @Route("/", name="motivo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $motivos = $em->getRepository('InventarioBundle:Motivo')->findAll();

        return $this->render('@Inventario/motivo/index.html.twig', array(
            'motivos' => $motivos,
        ));
    }

    /**
     * Creates a new Motivo entity.
     *
     * @Route("/nuevo", name="motivo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $motivo = new Motivo();
        $form = $this->createForm('InventarioBundle\Form\MotivoType', $motivo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $motivo->setConceptodefault(0);
            $em->persist($motivo);
            $em->flush();
            return $this->redirectToRoute('motivo_index');
        }

        return $this->render('@Inventario/motivo/new.html.twig', array(
            'motivo' => $motivo,
            'form' => $form->createView(),
            'accion'=>'Nuevo'
        ));
    }

    /**
     * Finds and displays a Motivo entity.
     *
     * @Route("/mostrar/{id}", name="motivo_show")
     * @Method("GET")
     */
    public function showAction(Motivo $motivo)
    {

        return $this->render('@Inventario/motivo/show.html.twig', array(
            'motivo' => $motivo,
        ));
    }

    /**
     * Displays a form to edit an existing Motivo entity.
     *
     * @Route("/{id}/editar", name="motivo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Motivo $motivo)
    {
        $editForm = $this->createForm('InventarioBundle\Form\MotivoType', $motivo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $motivo->setConceptodefault(0);
            $em->persist($motivo);
            $em->flush();
                return $this->redirectToRoute('motivo_index');
        }

        return $this->render('@Inventario/motivo/new.html.twig', array(
            'motivo' => $motivo,
            'form' => $editForm->createView(),
            'accion'=>'Editar'
        ));
    }

    /**
     * Deletes a Motivo entity.
     *
     * @Route("/eliminar", name="motivo_delete")
     */
    public function deleteAction(Request $request)
    {
           
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $obj = $this->getDoctrine()->getRepository('InventarioBundle:Motivo')->find($id);
        $em->remove($obj);
        $em->flush();
        $this->addFlash('warning', 'Se eliminó satisfactoriamente el elemento');
        return $this->redirectToRoute('motivo_index');
    }

    /**
     * listamotivo all Motivo entity.
     *
     * @Route("/listamotivo", name="motivo_listado")
     */
    public function listadoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository('InventarioBundle:Motivo')->findAll();
        return $this->render('@Inventario/motivo/listamotivo.html.twig', array('motivos' => $obj));
    }

}
