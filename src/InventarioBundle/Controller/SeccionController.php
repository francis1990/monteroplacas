<?php

namespace InventarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InventarioBundle\Entity\Seccion;
use InventarioBundle\Form\SeccionType;

/**
 * Seccion controller.
 *
 * @Route("/seccion")
 */
class SeccionController extends Controller
{
    /**
     * Lists all Seccion entities.
     *
     * @Route("/", name="seccion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $seccions = $em->getRepository('InventarioBundle:Seccion')->findAll();

        return $this->render('@Inventario/seccion/index.html.twig', array(
            'seccions' => $seccions,
        ));
    }

    /**
     * Creates a new Seccion entity.
     *
     * @Route("/nuevo", name="seccion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $seccion = new Seccion();
        $form = $this->createForm('InventarioBundle\Form\SeccionType', $seccion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seccion);
            $em->flush();
                return $this->redirectToRoute('seccion_index');
        }

        return $this->render('@Inventario/seccion/new.html.twig', array(
            'seccion' => $seccion,
            'form' => $form->createView(),
            'accion'=>'Nuevo'
        ));
    }

    /**
     * Finds and displays a Seccion entity.
     *
     * @Route("/mostrar/{id}", name="seccion_show")
     * @Method("GET")
     */
    public function showAction(Seccion $seccion)
    {
       

        return $this->render('@Inventario/seccion/show.html.twig', array(
            'seccion' => $seccion
        ));
    }

    /**
     * Displays a form to edit an existing Seccion entity.
     *
     * @Route("/{id}/editar", name="seccion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Seccion $seccion)
    {
        $editForm = $this->createForm('InventarioBundle\Form\SeccionType', $seccion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seccion);
            $em->flush();

            return $this->redirectToRoute('seccion_index');
        }

        return $this->render('@Inventario/seccion/new.html.twig', array(
            'seccion' => $seccion,
            'form' => $editForm->createView(),
            'accion'=>'Editar'
        ));
    }

    /**
     * Deletes a Seccion entity.
     *
     * @Route("/eliminar", name="seccion_delete")
     */
    public function deleteAction(Request $request)
    {
        var_dump('eee');die;
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');

        $obj = $this->getDoctrine()->getRepository('InventarioBundle:Seccion')->find($id);
        $em->remove($obj);
        $em->flush();
        $this->addFlash('warning', 'Se eliminó satisfactoriamente el elemento');
        return $this->redirectToRoute('seccion_index');
    }

    /**
     * listaseccion all Seccion entity.
     *
     * @Route("/listaseccion", name="seccion_listado")
     */
    public function listadoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository('InventarioBundle:Seccion')->findAll();
        return $this->render('@Inventario/seccion/listaseccion.html.twig', array('seccions' => $obj));
    }

   
}
