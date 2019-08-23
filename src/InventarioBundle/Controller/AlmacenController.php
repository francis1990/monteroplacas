<?php

namespace InventarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InventarioBundle\Entity\Almacen;
use InventarioBundle\Form\AlmacenType;

/**
 * Almacen controller.
 *
 * @Route("/almacen")
 */
class AlmacenController extends Controller
{
    /**
     * Lists all Almacen entities.
     *
     * @Route("/", name="almacen_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $almacens = $em->getRepository('InventarioBundle:Almacen')->findAll();

        return $this->render('@Inventario/almacen/index.html.twig', array(
            'almacens' => $almacens,
        ));
    }

    /**
     * Creates a new Almacen entity.
     *
     * @Route("/nuevo", name="almacen_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $almacen = new Almacen();
        $form = $this->createForm('InventarioBundle\Form\AlmacenType', $almacen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($almacen);
            $em->flush();
            return $this->redirectToRoute('almacen_index');
        }

        return $this->render('@Inventario/almacen/new.html.twig', array(
            'almacen' => $almacen,
            'form' => $form->createView(),
            'accion'=>'Nuevo'
        ));
    }

    /**
     * Finds and displays a Almacen entity.
     *
     * @Route("/mostrar/{id}", name="almacen_show")
     * @Method("GET")
     */
    public function showAction(Almacen $almacen)
    {
        return $this->render('@Inventario/almacen/show.html.twig', array(
            'almacen' => $almacen,
        ));
    }

    /**
     * Displays a form to edit an existing Almacen entity.
     *
     * @Route("/{id}/editar", name="almacen_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Almacen $almacen)
    {
        $editForm = $this->createForm('InventarioBundle\Form\AlmacenType', $almacen);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($almacen);
            $em->flush();
            return $this->redirectToRoute('almacen_index');
        }

        return $this->render('@Inventario/almacen/new.html.twig', array(
            'almacen' => $almacen,
            'form' => $editForm->createView(),
            'accion'=>'Editar'
        ));
    }

    /**
     * Deletes a Almacen entity.
     *
     * @Route("/eliminar", name="almacen_delete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $obj = $this->getDoctrine()->getRepository('InventarioBundle:Almacen')->find($id);
        $em->remove($obj);
        $em->flush();
        $this->addFlash('warning', 'Se eliminó satisfactoriamente el elemento');
        return $this->redirectToRoute('almacen_index');
    }


}
