<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Pago;
use Sistemadmin\BackendBundle\Form\PagoType;
use Sistemadmin\BackendBundle\Helper;
use PHPExcel;
use PHPExcel_IOFactory;


/**
 * Pago controller.
 *
 * @Route("/pago")
 */
class PagoController extends Controller
{
    
    /**
     * Creates a new Pago entity.
     *
     * @Route("/new", name="pago_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pago = new Pago();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\PagoType', $pago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository =$em->getRepository('BackendBundle:Pago');
           
            $result = $repository->Create($pago);
           
            if ($result){
            return $this->redirectToRoute('pago_index');}
            else {
                $error = new \Symfony\Component\Form\FormError('La combinación serie-número de la factura no existe');
                $form->addError($error);

                return $this->render('pago/new.html.twig', array(
                            'pago' => $pago,
                            'form' => $form->createView(),
                ));
            }     
        }

        return $this->render('pago/new.html.twig', array(
            'pago' => $pago,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Lists all Pago entities.
     *
     * @Route("/{page}", name="pago_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();

        $order_by = array();
        $pagosCount = $em->getRepository('BackendBundle:Pago')->GetByParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($pagosCount, $page, $results);
        $pagos = $em->getRepository('BackendBundle:Pago')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';        
		$pathern='pago_index';
        return $this->render('pago/index.html.twig', array(
            'pagos' => $pagos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern
        ));
    }

    /**
     * Finds and displays a Pago entity.
     *
     * @Route("/show/{id}", name="pago_show")
     * @Method("GET")
     */
    public function showAction(Pago $pago)
    {
        $deleteForm = $this->createDeleteForm($pago);

        return $this->render('pago/show.html.twig', array(
            'pago' => $pago,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Pago entity.
     *
     * @Route("/{id}/edit", name="pago_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Pago $pago)
    {
        $deleteForm = $this->createDeleteForm($pago);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\PagoType', $pago);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
             $repository =$em->getRepository('BackendBundle:Pago');
            $repository->Update($pago);

            return $this->redirectToRoute('pago_index');
        }

        return $this->render('pago/edit.html.twig', array(
            'pago' => $pago,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Pago entity.
     *
     * @Route("/delete/{id}", name="pago_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Pago $pago)
    {
         $em = $this->getDoctrine()->getManager();
             $repository =$this->getDoctrine()->getRepository('BackendBundle:Pago');
            $repository->Delete($pago);

        return $this->redirectToRoute('pago_index');
        
//        $form = $this->createDeleteForm($pago);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($pago);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('pago_index');
    }

    /**
     * Creates a form to delete a Pago entity.
     *
     * @param Pago $pago The Pago entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pago $pago)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pago_delete', array('id' => $pago->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Export a Pago entity.
     *
     * @Route("/export/", name="pago_export")
     * @Method({"GET", "POST"})
     */
    public function exportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deuda = $em->getRepository('BackendBundle:Pago')->findAll();
        $data=array();
        foreach($deuda as $key=>$c){
            $data[]= array(
                $c->getFechapago()!==null? date_format($c->getFechapago(),'d/m/Y'):'',
                $c-> getFechacancelacion()!==null? date_format($c-> getFechacancelacion(),'d/m/Y'):'',
                $c->getSerie().'-'. $c->getNumerofactura(),
                $c->getCliente()->getNombre(),
                $c->getTotalapagar(),
                $c->getMontopagado(),
            );
        }
        // Crea un nuevo objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
            ->setCreator('MonteroPlacas')
            ->setTitle('pagos')
            ->setLastModifiedBy('Montero')
            ->setDescription('Registro de pagos')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setKeywords('exportar pagos')
            ->setCategory('exportar');
        // Agregar Informacion
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
        $celda = $objPHPExcel->setActiveSheetIndex(0);
        $celda->setCellValue('A2','Empresa: MONTERO PLACAS SAC');
        $celda->setCellValue('A3','R.u.c.: 20543215385');
        $celda->mergeCells('A4:I4');
        $celda->setCellValue('A4', 'Registro de pagos ');
        $celda->setCellValue('A6', 'Fecha de inicio')
            ->setCellValue('B6', 'Fecha de cancelación')
            ->setCellValue('C6', 'Referencia')
            ->setCellValue('D6', 'Cliente')
            ->setCellValue('E6', 'Total a pagar')
            ->setCellValue('F6', 'A cuenta');
        $celda->fromArray($data,'','A7');
        $cabecera = 'a6:f6';
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'font' => array('bold' => true,)
        );
        $celda->getStyle($cabecera)->applyFromArray($style2);
        $objPHPExcel->setActiveSheetIndex(0);
        $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');
        
        //original
//        header('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
//        header('Content-Type: application/vnd.ms-excel;');
//        header("Content-type: application/x-msexcel");
//        header('Pragma', 'public');
//        header('Content-Disposition: attachment;filename="pagos.xls"');
//        header('Cache-Control: max-age=0');
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->setPreCalculateFormulas(true);
//        $objWriter->save('php://output');
        
        //alternatives
        $filename = "pagos.xls";
        ob_end_clean();
        header( "Content-type: application/vnd.ms-excel" );
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header("Pragma: no-cache");
        header("Expires: 0");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->setPreCalculateFormulas(true);
        $objWriter->save('php://output');
        ob_end_clean();

        
        /*Desconecta el objeto PHPExcel para que no se quede en memoria*/
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);

    }
    
    /**
     * Search a Pago entity.
     *
     * @Route("/search/{page}", name="pago_search")
     * @Method({"GET", "POST"})
     */    
     public function searchAction(Request $request, $page=1) {
//         if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
//             $this->addFlash(
//                 'danger',
//                 'No tiene permiso de realizar esta acción!'
//             );
//             return $this->redirect($this->generateUrl('main'));
//         }
         $em = $this->getDoctrine()->getManager();
        //order of items from database
        $order_by = array();
        
        $daily = $request->request->get('daily');        
        if ($daily !=null) {
            $dailydate = $request->request->get('dailydate');
            $pagosCount = $em->getRepository('BackendBundle:Pago')->GetByFechaDiariaParamCount($dailydate);           
            $results = 10; //paginado     
            $paginator = new Helper\Paginator($pagosCount, $page, $results);
            $sort_direction = 'desc';
            $pagos = $em->getRepository('BackendBundle:Pago')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit()); 
            
        }else {
            $dailydate=1;
        }     
        $fechas= $request->request->get('fechas');
        if ($fechas !=null) {
            $iniciodate = $request->request->get('iniciodate');
            $finaldate = $request->request->get('finaldate');
            $pagosCount = $em->getRepository('BackendBundle:Pago')->GetByFechaRangoParamCount($iniciodate,$finaldate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($pagosCount, $page, $results);
            $sort_direction = 'desc';
            $pagos = $em->getRepository('BackendBundle:Pago')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit());  
            
        }  else {
            $iniciodate=1;
            $finaldate=1;
        }   
        
     
        return $this->render('pago/search.html.twig', array(
                    "pagos" => $pagos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                    "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page));
    }
    
     /**
     * Searched a Pago entity.
     *
     * @Route("/searched/{page}/{dailydate}/{iniciodate}/{finaldate}", name="pago_searched")
     * @Method({"GET", "POST"})
     */ 
    public function searchedAction( Request $request,$page,  $dailydate=1,$iniciodate=1,$finaldate=1) {

        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
         $em = $this->getDoctrine()->getManager();
        //order of items from database
        $order_by = array();        
            
        if ($dailydate !=1) {            
            $pagosCount = $em->getRepository('BackendBundle:Pago')->GetByFechaDiariaParamCount($dailydate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($pagosCount, $page, $results);
            $sort_direction = 'desc';
            $pagos = $em->getRepository('BackendBundle:Pago')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit());    
            
        }     
        
        if ($iniciodate !=1 && $finaldate !=1) {            
            $pagosCount = $em->getRepository('BackendBundle:Pago')->GetByFechaRangoParamCount($iniciodate,$finaldate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($pagosCount, $page, $results);
            $sort_direction = 'desc';
            $pagos = $em->getRepository('BackendBundle:Pago')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit()); 
            
        }
        
       
        return $this->render('pago/search.html.twig', array(
                    "pagos" => $pagos, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                    "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page));
    }    
}
