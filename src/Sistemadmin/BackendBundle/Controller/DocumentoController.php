<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Documento;
use Sistemadmin\BackendBundle\Form\DocumentoType;
use Sistemadmin\BackendBundle\Helper;

/**
 * Documento controller.
 *
 * @Route("/documento")
 */
class DocumentoController extends Controller
{

    /**
     * Creates a new Documento entity.
     *
     * @Route("/new", name="documento_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $documento = new Documento();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\DocumentoType', $documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documento);
            $em->flush();

            return $this->redirectToRoute('documento_index');
        }

        return $this->render('documento/new.html.twig', array(
            'documento' => $documento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Documento entity.
     *
     * @Route("/show/{id}", name="documento_show")
     * @Method("GET")
     */
    public function showAction(Documento $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);

        return $this->render('documento/show.html.twig', array(
            'documento' => $documento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Documento entity.
     *
     * @Route("/{id}/edit", name="documento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Documento $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\DocumentoType', $documento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documento);
            $em->flush();

            return $this->redirectToRoute('documento_index');
        }

        return $this->render('documento/edit.html.twig', array(
            'documento' => $documento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Documento entity.
     *
     * @Route("/{id}", name="documento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Documento $documento)
    {
        $form = $this->createDeleteForm($documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documento);
            $em->flush();
        }

        return $this->redirectToRoute('documento_index');
    }

    /**
     * Creates a form to delete a Documento entity.
     *
     * @param Documento $documento The Documento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Documento $documento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('documento_delete', array('id' => $documento->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
    /**
     * Lists all Documento entities.
     *
     * @Route("/{page}", name="documento_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();
		$order_by = array();
        $documentoCount = $em->getRepository('BackendBundle:Documento')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($documentoCount, $page, $results);
        $documentos = $em->getRepository('BackendBundle:Documento')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='documento_index';
        return $this->render('documento/index.html.twig', array(
            'documentos' => $documentos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern
        ));
		
    }


}
