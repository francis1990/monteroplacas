<?php

namespace InventarioBundle\Controller;

use Sistemadmin\BackendBundle\Entity\Articulo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InventarioBundle\Entity\Movimiento;
use InventarioBundle\Form\MovimientoType;

/**
 * Movimiento controller.
 *
 * @Route("/invmovimiento")
 */
class MovimientoController extends Controller
{
      /**
     * Export a Movimiento entity.
     *
     * @Route("/porfecha", name="inv_bydate")
     * @Method({"GET", "POST"})
     */
    public function exportAction(Request $request)
    {
        $params=$request->query->get('form');
        $data=$request->query;
        if($data->get('min')!='')
         $params['fini']= date_format(new \DateTime($data->get('min')),'Y-m-d');
        if($data->get('max')!='')
         $params['ffin']=date_format(new \DateTime($data->get('max')),'Y-m-d');
        if($data->get('articulo')!=0)
         $params['articulo']=$data->get('articulo');
        if($data->get('seccion')!=0)
         $params['seccion']=$data->get('seccion');
        if($data->get('motivo')!=0)
         $params['motivo']=$data->get('motivo');
        return $this->get('inventario.services')->movimientosFecha(isset($params)?$params:null);

    }
    
    /**
     * Lists all Movimiento entities.
     *
     * @Route("/listar/{articulo}/{seccion}", name="movimientoinv_index", defaults={"articulo" = 0,"seccion"=0})
     * @Method("GET")
     */
    public function indexAction($articulo,$seccion)
    {
        $em = $this->getDoctrine()->getManager();
        if($articulo==0)
            $movimientos = $em->getRepository('InventarioBundle:Movimiento')->findAll();
        else
            $movimientos = $em->getRepository('InventarioBundle:Movimiento')->findBy(array('articulo'=>$articulo,'seccion'=>$seccion));

        return $this->render('@Inventario/movimiento/index.html.twig', array(
            'movimientos' => $movimientos,
            'articulo'=>$articulo,
            'seccion'=>$seccion
        ));
    }

    /**
     * Creates a new Movimiento entity.
     *
     * @Route("/nuevo", name="movimientoinv_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $movimiento = new Movimiento();
        $form = $this->createForm('InventarioBundle\Form\MovimientoType', $movimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movimiento);
            $em->flush();
                return $this->redirectToRoute('movimientoinv_index');

        }

        return $this->render('@Inventario/movimiento/new.html.twig', array(
            'movimiento' => $movimiento,
            'form' => $form->createView(),
            'accion'=>'Nuevo'
        ));
    }

    /**
     * Finds and displays a Movimiento entity.
     *
     * @Route("/mostrar/{id}", name="movimientoinv_show")
     * @Method("GET")
     */
    public function showAction(Movimiento $movimiento)
    {

        return $this->render('@Inventario/movimiento/show.html.twig', array(
            'movimiento' => $movimiento,
        ));
    }

    /**
     * Displays a form to edit an existing Movimiento entity.
     *
     * @Route("/{id}/editar", name="movimientoinv_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Movimiento $movimiento)
    {
        $editForm = $this->createForm('InventarioBundle\Form\MovimientoType', $movimiento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movimiento);
            $em->flush();
            return $this->redirectToRoute('movimientoinv_index');
        }

        return $this->render('@Inventario/movimiento/new.html.twig', array(
            'movimiento' => $movimiento,
            'form' => $editForm->createView(),
            'accion'=>'Editar'
        ));
    }

    /**
     * Deletes a Movimiento entity.
     *
     * @Route("/eliminar", name="movimientoinv_delete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $obj = $this->getDoctrine()->getRepository('InventarioBundle:Movimiento')->find($id);
        $em->remove($obj);
        $em->flush();
        $this->addFlash('warning', 'Se eliminó satisfactoriamente el elemento');
        return $this->redirectToRoute('movimientoinv_index');
    }


  
}
