<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Gasto;
use Sistemadmin\BackendBundle\Form\GastoType;
use Sistemadmin\BackendBundle\Helper;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * Gasto controller.
 *
 * @Route("/gasto")
 */
class GastoController extends Controller
{
    
      /**
     * Export por vendedor
     *
     * @Route("/cuadrarcaja/", name="cuadre_caja")
     * @Method({"GET", "POST"})
     */
    public function cuadrarcajaAction(Request $request)
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('fechainicio', 'date', array(
                'widget' => 'single_text',
                'label' => 'Fecha inicio:',
                'format' => 'MM/dd/yyyy',
                'attr' => array('class' => 'form-control  dp_modal '),
                'html5' => false,
            ))->add('fechafin', 'date', array(
                'widget' => 'single_text',
                'label' => 'Fecha Fin:',
                'format' => 'MM/dd/yyyy',
                'attr' => array('class' => 'form-control  dp_modal '),
                'html5' => false,
            ))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $datosForm = $form->getData();

            $pagos = $em->getRepository('BackendBundle:Pago')->getPagosPorFecha(array(                
                'fechai' => date_format($datosForm['fechainicio'], 'Y-m-d'),
                'fechaf' => date_format($datosForm['fechafin'], 'Y-m-d')
            ));
            $gastos = $em->getRepository('BackendBundle:Gasto')->getGastosPorFecha(array(                
                'fechai' => date_format($datosForm['fechainicio'], 'Y-m-d'),
                'fechaf' => date_format($datosForm['fechafin'], 'Y-m-d')
            ));
            
            $sumapagos=0;
            foreach ($pagos as $key => $p){
                $sumapagos=$sumapagos+$p->getMontopagado();
            } 
            
            $sumagastos=0;
            foreach ($gastos as $key => $g){
                $sumagastos=$sumagastos+$g->getCantidad();
            } 
            
            $cuadre=$sumapagos-$sumagastos;
            
            $fechainicio=date_format($datosForm['fechainicio'], 'Y-m-d');
            $fechafin=date_format($datosForm['fechafin'], 'Y-m-d');
            
            return $this->render('gasto/reportecuadrecaja.html.twig', array(
                     'pagos' => $pagos,'gastos' => $gastos,'cuadre' => $cuadre,'sumapagos' =>$sumapagos,'sumagastos' =>$sumagastos,
                'fechainicio' =>$fechainicio,'fechafin' =>$fechafin
                ));
        }
        
        return $this->render('gasto/cuadrecaja.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    
    
    
    /**
     * Creates a new Gasto entity.
     *
     * @Route("/new", name="gasto_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $gasto = new Gasto();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\GastoType', $gasto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gasto);
            $em->flush();
            $this->addFlash(
                'success',
                'Se ha insertado el gasto de manera exitosa!');
            return $this->redirectToRoute('gasto_show', array('id' => $gasto->getId()));
        }

        return $this->render('gasto/new.html.twig', array(
            'gasto' => $gasto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Gasto entities.
     *
     * @Route("/{page}", name="gasto_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();

        $order_by = array();
        $gastosCount = $em->getRepository('BackendBundle:Gasto')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($gastosCount, $page, $results);
        $gastos = $em->getRepository('BackendBundle:Gasto')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='gasto_index';
        return $this->render('gasto/index.html.twig', array(
            'gastos' => $gastos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
            "page" =>$page,"pathern" =>$pathern
        ));
        /*$em = $this->getDoctrine()->getManager();
        $order_by = array();
        $gastos = $em->getRepository('BackendBundle:Gasto')->findAll();

        return $this->render('gasto/index.html.twig', array(
            'gastos' => $gastos,
        ));*/
    }

    /**
     * Finds and displays a Gasto entity.
     *
     * @Route("/show/{id}", name="gasto_show")
     * @Method("GET")
     */
    public function showAction(Gasto $gasto)
    {
        $deleteForm = $this->createDeleteForm($gasto);

        return $this->render('gasto/show.html.twig', array(
            'gasto' => $gasto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Gasto entity.
     *
     * @Route("/{id}/edit", name="gasto_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Gasto $gasto)
    {
        $deleteForm = $this->createDeleteForm($gasto);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\GastoType', $gasto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gasto);
            $em->flush();
            $this->addFlash(
                'success',
                'Se ha modificado el gasto de manera exitosa!'
            );
            return $this->redirectToRoute('gasto_edit', array('id' => $gasto->getId()));
        }

        return $this->render('gasto/edit.html.twig', array(
            'gasto' => $gasto,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Gasto entity.
     *
     * @Route("/delete/{id}", name="gasto_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, Gasto $gasto)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($gasto);
        $em->flush();
        $this->addFlash(
            'success',
            'Se ha eliminado el gasto de manera exitosa!');
        return $this->redirectToRoute('gasto_index');
    }


    /**
     * Creates a form to delete a Gasto entity.
     *
     * @param Gasto $gasto The Gasto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Gasto $gasto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gasto_delete', array('id' => $gasto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
