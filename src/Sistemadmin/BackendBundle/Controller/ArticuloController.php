<?php

namespace Sistemadmin\BackendBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Articulo;
use Sistemadmin\BackendBundle\Form\ArticuloType;
use Symfony\Component\Form\FormError;

use Sistemadmin\BackendBundle\Helper;

/**
 * Articulo controller.
 *
 * @Route("/articulo")
 */
class ArticuloController extends Controller
{
    
    /**
     * Creates a new Articulo entity.
     *
     * @Route("/new", name="articulo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $articulo = new Articulo();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\ArticuloType', $articulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($articulo);
//            $em->flush();
            $repository =$this->getDoctrine()->getRepository('BackendBundle:Articulo');
            $result = $repository->Create($articulo);
            if ($result){
                return $this->redirectToRoute('articulo_show', array('id' => $articulo->getId()));
//            return $this->redirectToRoute('articulo_index');
            }
            else{
               $error = new \Symfony\Component\Form\FormError('El articulo ya existe!!(Nombre ya registrado)');
                    $form->addError($error);
                    return $this->render("articulo/new.html.twig", array(
                                'articulo' => $articulo,"form" => $form->createView()
                    ));
            }
        }

        return $this->render('articulo/new.html.twig', array(
            'articulo' => $articulo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Articulo entity.
     *
     * @Route("/show/{id}", name="articulo_show")
     * @Method("GET")
     */
    public function showAction(Articulo $articulo)
    {
        $deleteForm = $this->createDeleteForm($articulo);

        return $this->render('articulo/show.html.twig', array(
            'articulo' => $articulo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Articulo entity.
     *
     * @Route("/{id}/edit", name="articulo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Articulo $articulo)
    {
        $deleteForm = $this->createDeleteForm($articulo);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\ArticuloType', $articulo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($articulo);
            $em->flush();

            return $this->redirectToRoute('articulo_index');
        }

        return $this->render('articulo/edit.html.twig', array(
            'articulo' => $articulo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Articulo entity.
     *
     * @Route("/delete/{id}", name="articulo_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, Articulo $articulo)
    {
         $em = $this->getDoctrine()->getManager();
             $repository =$this->getDoctrine()->getRepository('BackendBundle:Articulo');
            $repository->Delete($articulo);

        return $this->redirectToRoute('articulo_index');    
    }

    /**
     * Creates a form to delete a Articulo entity.
     *
     * @param Articulo $articulo The Articulo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Articulo $articulo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articulo_delete', array('id' => $articulo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }   
    
    
        
     /**
     * Search  Articulos entities.
     *
     * @Route("/buscar/articulos/{page}", name="buscar_articulos")
     * @Method({"GET", "POST"})
     */
    public function buscarAction(Request $request,$page=1)
    {
        $em = $this->getDoctrine()->getManager();
                        
        $categoria= $request->request->get('categoria');  
        $buscar= $request->request->get('buscar'); 
          
        $nombres = array($categoria);
        $parametros[0] = $buscar;
        
//        print_r( $parametros[1]);
//        die();
        
        if ($buscar == null ) {
            return $this->redirectToRoute('articulo_index');
        } 

        $order_by = array();
        $articulosCount = $em->getRepository('BackendBundle:Articulo')->GetByBuscarParamCount($nombres,$parametros);
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($articulosCount, $page, $results);
        $articulos = $em->getRepository('BackendBundle:Articulo')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
            

        return $this->render('articulo/search.html.twig', array(
            'articulos' => $articulos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar
        ));
    }
    
    
     /**
     * Search  Articulos entities.
     *
     * @Route("/buscar/articulos/{page}/{categoria}/{buscar}", name="buscado_articulos")
     * @Method({"GET", "POST"})
     */
    public function buscadoAction(Request $request,$page=1,  $categoria=1,$buscar=1)
    {
//         print_r( 'aaaa');
//        die();
        $em = $this->getDoctrine()->getManager();
                        
          
        $nombres = array($categoria);
        $parametros[0] = $buscar;
        
       
        
        if ($buscar == null ) {
            return $this->redirectToRoute('articulo_index');
        }

        $order_by = array();
        $articulosCount = $em->getRepository('BackendBundle:Articulo')->GetByBuscarParamCount($nombres,$parametros);
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($articulosCount, $page, $results);
        $articulos = $em->getRepository('BackendBundle:Articulo')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
            

        return $this->render('articulo/search.html.twig', array(
            'articulos' => $articulos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar
        ));
    }
    
    /**
     * Lists all Articulo entities.
     *
     * @Route("/{page}", name="articulo_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();
        
        $order_by = array();
        $articulosCount = $em->getRepository('BackendBundle:Articulo')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($articulosCount, $page, $results);
        $articulos = $em->getRepository('BackendBundle:Articulo')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='articulo_index';
        return $this->render('articulo/index.html.twig', array(
            'articulos' => $articulos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern
        ));
    }

    /**
     * listaseccion all Seccion entity.
     *
     * @Route("/listarticulo", name="articulo_listado")
     */
    public function listadoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository('BackendBundle:Articulo')->findAll();
        return $this->render('articulo/listarticulo.html.twig', array('articulos' => $obj));
    }



}
