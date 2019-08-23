<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Proveedor;
use Sistemadmin\BackendBundle\Form\ProveedorType;

use Sistemadmin\BackendBundle\Helper;

/**
 * Proveedor controller.
 *
 * @Route("/proveedor")
 */
class ProveedorController extends Controller
{
    
   /**
     * Creates a new Proveedor entity.
     *
     * @Route("/new", name="proveedor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $proveedor = new Proveedor();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\ProveedorType', $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();

            return $this->redirectToRoute('proveedor_show', array('id' => $proveedor->getId()));
//            return $this->redirectToRoute('proveedor_index');
        }

        return $this->render('proveedor/new.html.twig', array(
            'proveedor' => $proveedor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Proveedor entity.
     *
     * @Route("/show/{id}", name="proveedor_show")
     * @Method("GET")
     */
    public function showAction(Proveedor $proveedor)
    {
        $deleteForm = $this->createDeleteForm($proveedor);

        return $this->render('proveedor/show.html.twig', array(
            'proveedor' => $proveedor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Proveedor entity.
     *
     * @Route("/{id}/edit", name="proveedor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Proveedor $proveedor)
    {
        $deleteForm = $this->createDeleteForm($proveedor);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\ProveedorType', $proveedor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();

            return $this->redirectToRoute('proveedor_index');
        }

        return $this->render('proveedor/edit.html.twig', array(
            'proveedor' => $proveedor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Proveedor entity.
     *
     * @Route("/delete/{id}", name="proveedor_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, Proveedor $proveedor)
    {
        $em = $this->getDoctrine()->getManager();
             $repository =$this->getDoctrine()->getRepository('BackendBundle:Proveedor');
            $repository->Delete($proveedor);

        return $this->redirectToRoute('proveedor_index');        
    }

    /**
     * Creates a form to delete a Proveedor entity.
     *
     * @param Proveedor $proveedor The Proveedor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Proveedor $proveedor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proveedor_delete', array('id' => $proveedor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Proveedor entities.
     *
     * @Route("/{page}", name="proveedor_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
         $em = $this->getDoctrine()->getManager();
        
        $order_by = array();
        $proveedorsCount = $em->getRepository('BackendBundle:Proveedor')->GetByParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($proveedorsCount, $page, $results);
        $proveedors = $em->getRepository('BackendBundle:Proveedor')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';      
        $pathern='proveedor_index';
        return $this->render('proveedor/index.html.twig', array(
            'proveedors' => $proveedors, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern
        ));
    }

 
}
