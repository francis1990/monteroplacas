<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Cliente;
use Sistemadmin\BackendBundle\Form\ClienteType;

use Sistemadmin\BackendBundle\Helper;

/**
 * Cliente controller.
 *
 * @Route("/cliente")
 */
class ClienteController extends Controller
{
    
    /**
     * Creates a new Cliente entity.
     *
     * @Route("/new", name="cliente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cliente = new Cliente();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\ClienteType', $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
             $repository =$em->getRepository('BackendBundle:Cliente');
            $result = $repository->Create($cliente);
           
            if ($result){
//            return $this->redirectToRoute('cliente_index');
                return $this->redirectToRoute('cliente_show', array('id' => $cliente->getId()));                
            }
            else {
                $error = new \Symfony\Component\Form\FormError('El cliente ya existe!!(Cambie el nombre o el Ruc)');
                $form->addError($error);

                return $this->render('cliente/new.html.twig', array(
                            'cliente' => $cliente,
                            'form' => $form->createView(),
                ));
            }
        }

        return $this->render('cliente/new.html.twig', array(
                    'cliente' => $cliente,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Cliente entity.
     *
     * @Route("/show/{id}", name="cliente_show")
     * @Method("GET")
     */
    public function showAction(Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);

        return $this->render('cliente/show.html.twig', array(
            'cliente' => $cliente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Cliente entity.
     *
     * @Route("/{id}/edit", name="cliente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\ClienteType', $cliente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/edit.html.twig', array(
            'cliente' => $cliente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Cliente entity.
     *
     * @Route("/{id}/delete", name="cliente_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, Cliente $cliente)
    {
        $em = $this->getDoctrine()->getManager();
             $repository =$this->getDoctrine()->getRepository('BackendBundle:Cliente');
            $repository->Delete($cliente);

        return $this->redirectToRoute('cliente_index');    
        
        
        
//        $form = $this->createDeleteForm($cliente);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($cliente);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('cliente_index');
    }

    /**
     * Creates a form to delete a Cliente entity.
     *
     * @param Cliente $cliente The Cliente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cliente $cliente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cliente_delete', array('id' => $cliente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
   /**
     * Lists all Cliente entities.
     *
     * @Route("/condeuda/{page}", name="cliente_condeuda")
     * @Method("GET")
     */
    public function clientescondeudaAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();
        
        $order_by = array();
        $clientesCount = $em->getRepository('BackendBundle:Cliente')->GetByConDeudaParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($clientesCount, $page, $results);
        $clientes = $em->getRepository('BackendBundle:Cliente')->GetByConDeudaParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
            
        $pathern='cliente_condeuda';

        return $this->render('cliente/condeuda.html.twig', array(
            'clientes' => $clientes, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern
        ));
    }
    
       /**
     * Lists all Cliente entities.
     *
     * @Route("/buscar/clientes/{page}", name="buscar_clientes")
     * @Method({"GET", "POST"})
     */
    public function buscarAction(Request $request,$page=1)
    {
        $em = $this->getDoctrine()->getManager();
        
        //order of items from database
        $order_by = array();
                
        $categoria= $request->request->get('categoria');  
        $buscar= $request->request->get('buscar'); 
        $deuda= $request->request->get('deuda'); 
          
        $nombres = array($categoria, "deuda");
        $parametros[0] = $buscar;
        $parametros[1] = $deuda;
        
//        print_r( $parametros[1]);
//        die();
        
        if ($buscar == null && $deuda == 1) {
            return $this->redirectToRoute('cliente_condeuda');
        } else if ($buscar == null && $deuda == 0) {
            return $this->redirectToRoute('cliente_index');
        }

        $order_by = array();
        $clientesCount = $em->getRepository('BackendBundle:Cliente')->GetByBuscarParamCount($nombres,$parametros);
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($clientesCount, $page, $results);
        $clientes = $em->getRepository('BackendBundle:Cliente')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
            

        return $this->render('cliente/search.html.twig', array(
            'clientes' => $clientes, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,"deuda" => $deuda
        ));
    }
    
    
           /**
     * Lists all Cliente entities.
     *
     * @Route("/buscar/clientes/{page}/{categoria}/{buscar}/{deuda}", name="buscado_clientes")
     * @Method({"GET", "POST"})
     */
    public function buscadoAction(Request $request,$page=1,  $categoria=1,$buscar=1,$deuda=1)
    {
        $em = $this->getDoctrine()->getManager();
             
        //order of items from database
        $order_by = array();
       
        $nombres = array($categoria, "deuda");
        $parametros = array($buscar, $deuda);
        
        if ($buscar == null && $deuda == 1) {
            return $this->redirectToRoute('cliente_condeuda');
        } else if ($buscar == null && $deuda == 0) {
            return $this->redirectToRoute('cliente_index');
        }

        $order_by = array();
        $clientesCount = $em->getRepository('BackendBundle:Cliente')->GetByBuscarParamCount($nombres,$parametros);
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($clientesCount, $page, $results);
        $clientes = $em->getRepository('BackendBundle:Cliente')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
            

        return $this->render('cliente/search.html.twig', array(
            'clientes' => $clientes, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,"deuda" => $deuda
        ));
    }
    
    /**
     * Lists all Cliente entities.
     *
     * @Route("/{page}", name="cliente_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();
        
        $order_by = array();
        $clientesCount = $em->getRepository('BackendBundle:Cliente')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($clientesCount, $page, $results);
        $clientes = $em->getRepository('BackendBundle:Cliente')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';

        $pathern='cliente_index';

        return $this->render('cliente/index.html.twig', array(
            'clientes' => $clientes, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern
        ));
    }
    
 


}
