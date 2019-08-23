<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\TipoCambio;
use Sistemadmin\BackendBundle\Form\TipoCambioType;

use Sistemadmin\BackendBundle\Helper;

/**
 * TipoCambio controller.
 *
 * @Route("/tipocambio")
 */
class TipoCambioController extends Controller
{
    /**
     * Creates a new TipoCambio entity.
     *
     * @Route("/new", name="tipocambio_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fecha=date('Y-m-d');

        $em = $this->getDoctrine()->getManager();
        $actual = $em->getRepository('BackendBundle:TipoCambio')->findbyFechaActual($fecha);
        if(count($actual)>0)
            $tipoCambio=$actual[0];
        else
            $tipoCambio = new TipoCambio();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\TipoCambioType', $tipoCambio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tipoCambio);
            $em->flush();
            return $this->redirectToRoute('main');
        }
        return $this->render('tipocambio/new.html.twig', array(
            'tipoCambio' => $tipoCambio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all TipoCambio entities.
     *
     * @Route("/{page}", name="tipocambio_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();

        $order_by = array();
        $articulosCount = $em->getRepository('BackendBundle:TipoCambio')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($articulosCount, $page, $results);
        $articulos = $em->getRepository('BackendBundle:TipoCambio')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='tipocambio_index';
        return $this->render('tipocambio/index.html.twig', array(
            'tipoCambios' => $articulos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
            "page" =>$page,"pathern" =>$pathern
        ));
    }



    /**
     * Finds and displays a TipoCambio entity.
     *
     * @Route("/show/{id}", name="tipocambio_show")
     * @Method("GET")
     */
    public function showAction(TipoCambio $tipoCambio)
    {
        $deleteForm = $this->createDeleteForm($tipoCambio);

        return $this->render('tipocambio/show.html.twig', array(
            'tipoCambio' => $tipoCambio,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TipoCambio entity.
     *
     * @Route("/{id}/edit", name="tipocambio_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TipoCambio $tipoCambio)
    {
        $deleteForm = $this->createDeleteForm($tipoCambio);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\TipoCambioType', $tipoCambio);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipoCambio);
            $em->flush();

            return $this->redirectToRoute('tipocambio_edit', array('id' => $tipoCambio->getId()));
        }

        return $this->render('tipocambio/edit.html.twig', array(
            'tipoCambio' => $tipoCambio,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TipoCambio entity.
     *
     * @Route("/{id}", name="tipocambio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TipoCambio $tipoCambio)
    {
        $form = $this->createDeleteForm($tipoCambio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipoCambio);
            $em->flush();
        }

        return $this->redirectToRoute('tipocambio_index');
    }

    /**
     * Creates a form to delete a TipoCambio entity.
     *
     * @param TipoCambio $tipoCambio The TipoCambio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TipoCambio $tipoCambio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipocambio_delete', array('id' => $tipoCambio->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
