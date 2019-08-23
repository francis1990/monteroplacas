<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\ArticuloCompra;
use Sistemadmin\BackendBundle\Form\ArticuloCompraType;

/**
 * ArticuloCompra controller.
 *
 * @Route("/articulocompra")
 */
class ArticuloCompraController extends Controller
{
    /**
     * Lists all ArticuloCompra entities.
     *
     * @Route("/", name="articulocompra_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articuloCompras = $em->getRepository('BackendBundle:ArticuloCompra')->findAll();

        return $this->render('articulocompra/index.html.twig', array(
            'articuloCompras' => $articuloCompras,
        ));
    }

    /**
     * Creates a new ArticuloCompra entity.
     *
     * @Route("/new", name="articulocompra_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $articuloCompra = new ArticuloCompra();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\ArticuloCompraType', $articuloCompra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($articuloCompra);
            $em->flush();

            return $this->redirectToRoute('articulocompra_show', array('id' => $articuloCompra->getId()));
        }

        return $this->render('articulocompra/new.html.twig', array(
            'articuloCompra' => $articuloCompra,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticuloCompra entity.
     *
     * @Route("/{id}", name="articulocompra_show")
     * @Method("GET")
     */
    public function showAction(ArticuloCompra $articuloCompra)
    {
        $deleteForm = $this->createDeleteForm($articuloCompra);

        return $this->render('articulocompra/show.html.twig', array(
            'articuloCompra' => $articuloCompra,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticuloCompra entity.
     *
     * @Route("/{id}/edit", name="articulocompra_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ArticuloCompra $articuloCompra)
    {
        $deleteForm = $this->createDeleteForm($articuloCompra);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\ArticuloCompraType', $articuloCompra);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($articuloCompra);
            $em->flush();

            return $this->redirectToRoute('articulocompra_edit', array('id' => $articuloCompra->getId()));
        }

        return $this->render('articulocompra/edit.html.twig', array(
            'articuloCompra' => $articuloCompra,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ArticuloCompra entity.
     *
     * @Route("/{id}", name="articulocompra_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ArticuloCompra $articuloCompra)
    {
        $form = $this->createDeleteForm($articuloCompra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($articuloCompra);
            $em->flush();
        }

        return $this->redirectToRoute('articulocompra_index');
    }

    /**
     * Creates a form to delete a ArticuloCompra entity.
     *
     * @param ArticuloCompra $articuloCompra The ArticuloCompra entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ArticuloCompra $articuloCompra)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articulocompra_delete', array('id' => $articuloCompra->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
