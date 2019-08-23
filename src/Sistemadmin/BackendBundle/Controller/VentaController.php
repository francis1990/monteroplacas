<?php

namespace Sistemadmin\BackendBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Venta;
use Sistemadmin\BackendBundle\Entity\Deuda;
use Sistemadmin\BackendBundle\Form\VentaType;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use PHPExcel;
use PHPExcel_IOFactory;


use Sistemadmin\BackendBundle\Helper;
use Symfony\Component\HttpFoundation\Response;


/**
 * Venta controller.
 *
 * @Route("/venta")
 */
class VentaController extends Controller
{
    
        /**
     * Creates a new Venta entity.
     *
     * @Route("/{id}/notacredito/asign/", name="venta_asignventa")
     * @Method({"GET", "POST"})
     */
    public function asignVentaAction(Request $request, Venta $ventum)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/        
        
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\NotaCreditoType', $ventum);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $venta = $editForm->get('venta')->getData();
            
            $ventum->setNewnumerofactura($venta[0]->getSerie() . '-' . $venta[0]->getNumerodedocumento());
            $em = $this->getDoctrine()->getManager();
            $em->persist($ventum);
            $em->flush();
            return $this->redirectToRoute('venta_anuladas');
//            return $this->redirectToRoute('venta_index');
        }

        return $this->render('venta/asignventa.html.twig', array(
            'ventum' => $ventum,
            'form' => $editForm->createView(),
        ));
    }

    
   /**
     * Export por vendedor
     *
     * @Route("/porvendedor/", name="ventas_vendedor")
     * @Method({"GET", "POST"})
     */
    public function porVendedorAction(Request $request)
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
            ->add('vendedor', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
                'label' => 'Vendedor:',
                'class' => 'BackendBundle:Vendedor',
                'placeholder' => 'Seleccione...',
                'attr' => array('class' => 'form-control')
            ))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $datosForm = $form->getData();

            $ventas = $em->getRepository('BackendBundle:Venta')->getVentasPorVendedorNueva(array(
                'vendedor' => $datosForm['vendedor']->getId(),
                'fechai' => date_format($datosForm['fechainicio'], 'Y-m-d'),
                'fechaf' => date_format($datosForm['fechafin'], 'Y-m-d')
            ));
            
            $data = array();
             
            foreach ($ventas as $key => $v) {
                
//                if ($v->getVenta()->getAnulada()==true) {
//                    print_r('aaaaaaa');
//                    die();
//                }
                
                
                $deuda = $v->getVenta()->getMontototalapagar() -  $v->getVenta()->getTotalrecibido(); 
                if($deuda<0)
                    $deuda=0;
                $pago = $v->getVenta()->getTotalrecibido();
                
                
                 $saldo = $deuda;  
//                if (count($deuda) == 0) {                   
//                    $saldo = 0;
//                } else{
//                    $saldo = $deuda;                
//                }
                $data[] = array(
                    $v->getArticulo()->getAbreviatura(),
                    $v->getArticulo()->getNombre(),
                    $v->getArticulo()->getMedida(),
                    $v->getCantidad(),
                    $v->getImporte(),
                    date_format($v->getVenta()->getFecha(), 'd/m/Y'),
                    $v->getVenta()->getDocumento()->getTipodocumento() . ' : ' . $v->getVenta()->getNumeroVenta(),
                    $v->getVenta()->getNumerodedocumento(),
                    $v->getVenta()->getVendedor()->getNombre(),
                    $v->getVenta()->getVendedor()->getDni(),
                    $v->getVenta()->getCliente()->getNombre(),
                    $pago==0?'0':$pago,
                    $saldo==0?'0':$saldo
                );
            }

            // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                ->setCreator('MonteroPlacas')
                ->setTitle('ventasporvendedor')
                ->setLastModifiedBy('Montero')
                ->setDescription('Ventas por vendedor')
                ->setSubject('Office 2007 XLSX Test Document')
                ->setKeywords('exportar ventas por vendedor')
                ->setCategory('exportar');
            // Agregar Informacion
            $fei = date_format($datosForm['fechainicio'], 'd/m/Y');
            $fef = date_format($datosForm['fechafin'], 'd/m/Y');
            $range = "Venta de Productos por Vendedor del " . $fei . " al " . $fef . "";
            //dump($range);die;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
            $celda = $objPHPExcel->setActiveSheetIndex(0);
            $celda->setCellValue('A2', 'Empresa: MacroNetSystem S.A.C. ');
            $celda->setCellValue('A3', 'R.u.c.: 10203040506');
            $celda->mergeCells('A4:I4');
            $celda->setCellValue('A4', $range);
            $celda->setCellValue('A6', 'CODART')
                ->setCellValue('B6', 'NOMART')
                ->setCellValue('C6', 'ABRMED')
                ->setCellValue('D6', 'CANTI')
                ->setCellValue('E6', 'IMPORTE')
                ->setCellValue('F6', 'FECHA')
                ->setCellValue('G6', 'REFERENCIA')
                ->setCellValue('H6', 'CODVEN')
                ->setCellValue('I6', 'NOMVEN')
                ->setCellValue('J6', 'CODINT')
                ->setCellValue('K6', 'CLIENTE')
                ->setCellValue('L6', 'ACUENTA')
                ->setCellValue('M6', 'SALDO (DEUDA)');
            $celda->fromArray($data, '', 'A7');
            $cabecera = 'a6:m6';
            $style2 = array(
                'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
                'font' => array('bold' => true,)
            );
            $celda->getStyle($cabecera)->applyFromArray($style2);;
            $objPHPExcel->setActiveSheetIndex(0);
            $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');

            //original
//            header('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
//            header('Content-Type: application/vnd.ms-excel;');
//            header("Content-type: application/x-msexcel");
//            header('Pragma', 'public');
//            header('Content-Disposition: attachment;filename="ventasporvendedor.xlsx"');
//            header('Cache-Control: max-age=0');
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->setPreCalculateFormulas(true);
//            $objWriter->save('php://output');
            
            
             //alternatives
            $filename = "ventasporvendedor.xls";
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
        return $this->render('venta/porvendedor.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    
/**
     * Export a Venta entity.
     *
     * @Route("/export/pdf/{id}", name="venta_export_pdf")
     * @Method({"GET", "POST"})
     */
    public function exportPdfAction(Request $request,Venta $venta)
    {        
        
         $articuloventas = $venta->getArticuloventas();
		  $tipodedocumento = $venta->getDocumento()->getTipodocumento();
        foreach ($articuloventas as $articuloventa) {
            if($articuloventa->getImporte()==null || $articuloventa->getImporte()==0){
                $articuloventa->setImporte($articuloventa->getPrecio()*$articuloventa->getCantidad());
            }
        }
        
        $totalstring=convertir_a_letras($venta->getMontototalapagar());
       
        $fecha=date_format($venta->getFecha(),'d-m-Y');
        $arrformat=explode('-',$fecha);
		
		if ($tipodedocumento =="PROFORMA") {
   $html = $this->renderView(
            'venta/reporteventa.html.twig',
            array(
                'venta' => $venta,
                'dia'=>$arrformat[0],
                'mes'=>$arrformat[1],
                'anno'=>$arrformat[2],
                'cantidadapagar' => $totalstring,
            )
        );
}   elseif ($tipodedocumento =="BOLETA") {
    $html = $this->renderView(
            'venta/reporteventaboleta.html.twig',
            array(
                'venta' => $venta,
                'dia'=>$arrformat[0],
                'mes'=>$arrformat[1],
                'anno'=>$arrformat[2],
                'cantidadapagar' => $totalstring,
            )
        );
}   else {
    $html = $this->renderView(
            'venta/reporteventadatos.html.twig',
            array(
                'venta' => $venta,
                'dia'=>$arrformat[0],
                'mes'=>$arrformat[1],
                'anno'=>$arrformat[2],
                'cantidadapagar' => $totalstring,
            )
        );
}

        $this->returnPDFResponseFromHTML($html);

    }

    /**
     * Export a pdf
     *
     * @Route("/ticket/pdf/{id}", name="ticket_export_pdf")
     * @Method({"GET", "POST"})
     */
    public function exportPdfTicketAction(Request $request,Venta $venta)
    {
        $articuloventas = $venta->getArticuloventas();
        foreach ($articuloventas as $articuloventa) {
            if($articuloventa->getImporte()==null || $articuloventa->getImporte()==0){
                $articuloventa->setImporte($articuloventa->getPrecio()*$articuloventa->getCantidad());
            }
        }
                
//        print_r($venta->getMontototalapagar());die();
        $totalstring=convertir_a_letras($venta->getMontototalapagar());

        $fecha=date_format($venta->getFecha(),'d-m-Y');
        $arrformat=explode('-',$fecha);
        $html = $this->renderView(
            'venta/reporteventa.html.twig',
            array(
                'venta' => $venta,
                'dia'=>$arrformat[0],
                'mes'=>$arrformat[1],
                'anno'=>$arrformat[2],
                'cantidadapagar' => $totalstring,
            )
        );
        $this->returnPDFResponseFromHTML($html);
    }
    

    public function returnPDFResponseFromHTML($html){
        //set_time_limit(30); uncomment this line according to your needs
        // If you are not in a controller, retrieve of some way the service container and then retrieve it
        //$pdf = $this->container->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //if you are in a controlller use :
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->SetAuthor('Montero Placas');
        $pdf->SetTitle(('Venta Montero Placas'));
        $pdf->SetSubject('venta');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 9, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();
        $filename = 'venta_pdf';
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly
        
    }    
  /**
     * Creates a new Venta entity.
     *
     * @Route("/ticket/new", name="venta_partial_new")
     * @Method({"GET", "POST"})
     */
    public function newticketAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $ventum = new Venta();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\TicketType', $ventum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            
            
           $repository =$em->getRepository('BackendBundle:Venta');
           
           $result = $repository->Create($ventum);
           
            if ($result){

                return $this->redirectToRoute('ticket_show', array('id' => $ventum->getId()));
//            return $this->redirectToRoute('ticket_index');            
            }
            else {
                $error = new \Symfony\Component\Form\FormError('El número de esa venta ya existe!!(Incremente su valor)');
                $form->addError($error);
                return $this->render('venta/ticketnew.html.twig', array(
                            'ventum' => $ventum,
                            'form' => $form->createView(),
                ));
            }            
            
        }
        
          return $this->render('venta/ticketnew.html.twig', array(
            'ventum' => $ventum,
            'form' => $form->createView(),
        ));
    }    
    
     /**
     * Displays a form to edit an existing Ticket.
     *
     * @Route("/ticket/edit/{id}", name="ticket_edit")
     * @Method({"GET", "POST"})
     */
    public function ticketeditAction(Request $request, Venta $ventum)
    {   
        
          $articuloventas = $ventum->getArticuloventas();
        foreach ($articuloventas as $articuloventa) {
            if($articuloventa->getImporte()==null || $articuloventa->getImporte()==0){
                $articuloventa->setImporte($articuloventa->getPrecio()*$articuloventa->getCantidad());
            }
        }
        
        $deleteForm = $this->createDeleteForm($ventum);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\TicketType', $ventum);
        $editForm->remove('documento');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
           $repository =$em->getRepository('BackendBundle:Venta');
/*            dump('lelelele');die;*/
           $repository->Update($ventum);

            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('venta/ticketedit.html.twig', array(
            'ventum' => $ventum,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
     /**
     * Finds and displays a Venta entity.
     *
     * @Route("/ticket/show/{id}", name="ticket_show")
     * @Method("GET")
     */
    public function ticketshowAction(Venta $ventum)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        
          $articuloventas = $ventum->getArticuloventas();
        foreach ($articuloventas as $articuloventa) {
            if($articuloventa->getImporte()==null || $articuloventa->getImporte()==0){
                $articuloventa->setImporte($articuloventa->getPrecio()*$articuloventa->getCantidad());
            }
        }
        $deleteForm = $this->createDeleteForm($ventum);

        return $this->render('venta/ticketshow.html.twig', array(
            'ventum' => $ventum,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    
    /**
     * Lists all Venta entities.
     *
     * @Route("/ticket/{page}", name="ticket_index")
     * @Method("GET")
     */
    public function ticketAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();

         $order_by = array();
        $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByNotFinalizadaParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($ventasCount, $page, $results);
        $ventas = $em->getRepository('BackendBundle:Venta')->GetByNotFinalizadaParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
         $pathern='ticket_index';
        return $this->render('venta/ticketindex.html.twig', array(
            'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern
        ));
    } 
    
    /**
     * Deletes a Venta entity.
     *
     * @Route("/ticket/{id}/delete", name="ticket_delete")
     * @Method({"GET"})
     */
    public function ticketdeleteAction(Request $request, Venta $ventum) {
        $repository = $this->getDoctrine()->getRepository('BackendBundle:Venta');
        $repository->DeletePreventa($ventum);

        return $this->redirectToRoute('ticket_index');
    }

    /**
     * Creates a new Venta entity.
     *
     * @Route("/new", name="venta_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $ventum = new Venta();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\VentaType', $ventum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $recibido=$form->get('totalrecibido')->getData();
            $pagar=$form->get('montototalapagar')->getData();
            $forma=$form->get('formadepago')->getData();
             if ($forma == 'credito' && $recibido>$pagar) {
                  $error = new \Symfony\Component\Form\FormError('Si la forma de pago es Crédito, el monto pagado no puede exceder la cantidad a pagar');
                $form->addError($error);

                return $this->render('venta/new.html.twig', array(
                            'ventum' => $ventum,
                            'form' => $form->createView(),
                ));
             }
           $repository =$em->getRepository('BackendBundle:Venta');
           $result = $repository->Create($ventum);
            if ($result){
                $this->mandarPdfAction($ventum);
                return $this->redirectToRoute('venta_show', array('id' => $ventum->getId()));
//            return $this->redirectToRoute('venta_index');
            }
            else {
               $error = new \Symfony\Component\Form\FormError('El número de esa venta ya existe!!(Incremente su valor)');
                $form->addError($error);

                return $this->render('venta/new.html.twig', array(
                            'ventum' => $ventum,
                            'form' => $form->createView(),
                ));
            }
        }
        return $this->render('venta/new.html.twig', array(
            'ventum' => $ventum,
            'form' => $form->createView(),
        ));
    }
    
 
    /**
     * Finds and displays a Venta entity.
     *
     * @Route("/show/{id}", name="venta_show")
     * @Method("GET")
     */
    public function showAction(Venta $ventum)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
         $articuloventas = $ventum->getArticuloventas();
        foreach ($articuloventas as $articuloventa) {
            if($articuloventa->getImporte()==null || $articuloventa->getImporte()==0){
                $articuloventa->setImporte($articuloventa->getPrecio()*$articuloventa->getCantidad());
            }
        }
        
        $deleteForm = $this->createDeleteForm($ventum);

        return $this->render('venta/show.html.twig', array(
            'ventum' => $ventum,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing Venta entity.
     *
     * @Route("/{id}/edit", name="venta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Venta $ventum)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $articuloventas = $ventum->getArticuloventas();
        foreach ($articuloventas as $articuloventa) {
            if($articuloventa->getImporte()==null || $articuloventa->getImporte()==0){
                $articuloventa->setImporte($articuloventa->getPrecio()*$articuloventa->getCantidad());
            }
        }
        $deleteForm = $this->createDeleteForm($ventum);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\VentaType', $ventum);
        $editForm->remove('documento');
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $recibido = $editForm->get('totalrecibido')->getData();
            $pagar = $editForm->get('montototalapagar')->getData();
            $forma = $editForm->get('formadepago')->getData();
            if ($forma == 'credito' && $recibido >= $pagar) {
                 $error = new \Symfony\Component\Form\FormError('Si la forma de pago es Crédito, el monto pagado no puede exceder o igualar la cantidad a pagar');
                $form->addError($error);
                return $this->render('venta/new.html.twig', array(
                            'ventum' => $ventum,
                            'form' => $editForm->createView(),
                            'delete_form' => $deleteForm->createView(),
                ));
            }

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('BackendBundle:Venta');
            $repository->Update($ventum);
           // $repository->Updateasignacion($ventum);

            return $this->redirectToRoute('venta_show', array('id' => $ventum->getId()));
//            return $this->redirectToRoute('venta_index');
        }

        return $this->render('venta/edit.html.twig', array(
            'ventum' => $ventum,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    
    /**
     * Lists all Venta entities.
     *
     * @Route("/todas/ver/{page}", name="venta_todas")
     * @Method("GET")
     */
    public function todasAction($page=1)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $em = $this->getDoctrine()->getManager();
         $order_by = array();
        $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByFinalizadaTodasParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($ventasCount, $page, $results);
        $ventas = $em->getRepository('BackendBundle:Venta')->GetByFinalizadaTodasParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='venta_index';
        return $this->render('venta/todas.html.twig', array(
            'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,'pathern'=>  $pathern
        ));
    }    
    
    /**
     * Lists all Venta entities.
     *
     * @Route("/anuladas/ver/{page}", name="venta_anuladas")
     * @Method("GET")
     */
    public function anuladasAction($page=1)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $em = $this->getDoctrine()->getManager();
         $order_by = array();
        $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByAnuladasParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($ventasCount, $page, $results);
        $ventas = $em->getRepository('BackendBundle:Venta')->GetByAnuladasParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='venta_anuladas';
        return $this->render('venta/anuladas.html.twig', array(
            'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,'pathern'=>  $pathern
        ));
    }
    
   /**
     * Lists all Venta entities.
     *
     * @Route("/ventastickets/ver/{page}", name="venta_tickets")
     * @Method("GET")
     */
    public function ventasticketsAction($page=1)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $em = $this->getDoctrine()->getManager();
         $order_by = array();
        $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByTicketsParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($ventasCount, $page, $results);
        $ventas = $em->getRepository('BackendBundle:Venta')->GetByTicketsParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='venta_tickets';
        return $this->render('venta/ventastickets.html.twig', array(
            'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,'pathern'=>  $pathern
        ));
    }    
    
   /**
     * Lists all Venta entities.
     *
     * @Route("/ventastickets/convertir/factura/{id}", name="ticket_convertir_factura")
     * @Method("GET")
     */
    public function ticketConvertirFacturaAction(Venta $ventum)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('BackendBundle:Venta')->CastToFactura($ventum);
        
        return $this->redirectToRoute('venta_tickets');
    }        
    
        /**
     * Lists all Venta entities.
     *
     * @Route("/index/{page}", name="venta_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $em = $this->getDoctrine()->getManager();
         $order_by = array('fecha');
        $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByFinalizadaParamCount();
        $results = 10; //paginado     
        $paginator = new Helper\Paginator($ventasCount, $page, $results);
        $ventas = $em->getRepository('BackendBundle:Venta')->GetByFinalizadaParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'asc';
        $pathern='venta_index';
        
        $vendido = $em->getRepository('BackendBundle:Venta')->GetTotalVendido();   
        
        return $this->render('venta/index.html.twig', array(
            'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,"pathern" =>$pathern, "vendido" =>$vendido
        ));
    }
    
    
    /**
     * Export a Venta entity.
     *
     * @Route("/export/{dailydate}/{iniciodate}/{finaldate}", name="venta_export")
     * @Method({"GET", "POST"})
     */
    public function exportAction(Request $request,$dailydate=1,$iniciodate=1,$finaldate=1)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $em = $this->getDoctrine()->getManager();
        
         if ($dailydate !=1) {            
            $ventas = $em->getRepository('BackendBundle:Venta')->GetByFechaDiariaParam($dailydate);        
        }     
        
        if ($iniciodate !=1 && $finaldate !=1) {            
            $ventas = $em->getRepository('BackendBundle:Venta')->GetByFechaRangoParam($iniciodate,$finaldate);  
        }
        $data=array();

        $numeroventas=count($ventas);
        $locationtotal=7+$numeroventas;
        $celdatotal='J' . $locationtotal;
        $celdapagado='K' . $locationtotal;
        $celdasubtotal='G' . $locationtotal;
        
        $supertotal=0;
        $supersubtotal=0;
        $superecibido=0;
         foreach($ventas as $key=>$v){
             $grab=0;
             $nograb=0;
             
//             if ($v->getAnulada()==true){
//              print_r('aaaa');
//              die();
//              }
             
             
            if($v->getDocumento()->getIgv()==true){
//                $grab=$v->getMontototalapagar()*0.82;
                $grab=$v->getMontototalapagar()/1.18;
                $total=$v->getMontototalapagar();
                
                $supertotal=$supertotal+$total;
                $supersubtotal=$supersubtotal+$grab;
            }else
                $nograb=$v->getMontototalapagar();
            
            if( $v->getTotalrecibido()> $v->getMontototalapagar()){
                $recib=$v->getMontototalapagar();
            }else{
                 $recib=$v->getTotalrecibido();
            }
            
            $deuda= $v->getMontototalapagar() -  $v->getTotalrecibido(); 
            if ($deuda<0) {
                $deuda=0;
            }
            
            
            $data[]= array(
                $v->getDocumento()->getTipodocumento(),
                $v->getNumeroVenta(),
                $v->getCliente()->getRuc().'  -  '.$v->getCliente()->getNombre(),
                $v->getVendedor()->getNombre(),
                date_format($v->getfecha(),'d/m/Y'),
                $v->getCliente()->getRazonsoc(),
                $grab,
                $nograb,
                "=".$grab.'*18%',
                $total, //  "=Sum(F".($key+7).')'
                    $recib,
                $deuda==0?'0':$deuda,
            );
            $superecibido=$superecibido+$recib;
            $supertotal=$supertotal+$nograb;
         }
         
         // Crea un nuevo objeto PHPExcel
         $objPHPExcel = new PHPExcel();
         $objPHPExcel->getProperties()
             ->setCreator('MonteroPlacas')
             ->setTitle('ventas')
             ->setLastModifiedBy('Montero')
             ->setDescription('Registro de ventas')
             ->setSubject('Office 2007 XLSX Test Document')
             ->setKeywords('exportar ventas')
             ->setCategory('exportar');
         // Agregar Informacion
         $objPHPExcel ->getActiveSheet()->getColumnDimension('A')->setWidth(20);
         $objPHPExcel ->getActiveSheet()->getColumnDimension('B')->setWidth(20);
         $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
         $celda = $objPHPExcel->setActiveSheetIndex(0);
         $celda->setCellValue('A2','Empresa: MONTERO PLACAS SAC');
         $celda->setCellValue('A3','R.u.c.: 20543215385');
         $celda->mergeCells('A4:I4');
         $celda->setCellValue('A4', 'Registro de Ventas del 12/02/2018 al 13/02/2018');
         $celda->setCellValue('A6', 'NOMDOC')
             ->setCellValue('B6', 'NUMDOC')
             ->setCellValue('C6', 'RUC - NOMBRE')
             ->setCellValue('D6', 'VENDEDOR')
             ->setCellValue('E6', 'Fecha')
             ->setCellValue('F6', 'RAZCLI')
             ->setCellValue('G6', 'GRABADO')
             ->setCellValue('H6', 'NO GRABADO')
             ->setCellValue('I6', 'IGV')
             ->setCellValue('J6', 'TOTAL')
              ->setCellValue('K6', 'PAGADO')
                 ->setCellValue('L6', 'DEUDA');
         $celda->fromArray($data,'','A7');
         $celda->setCellValue($celdasubtotal, $supersubtotal);
          $celda->setCellValue($celdatotal, $supertotal);
          $celda->setCellValue($celdapagado, $superecibido);
         $cabecera = 'a6:l6';
         $style2 = array(
             'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
             'font' => array('bold' => true,)
         );
         $celda->getStyle($cabecera)->applyFromArray($style2);
         $objPHPExcel->setActiveSheetIndex(0);
         $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');
         
         //original
//         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//         header('Content-Disposition: attachment;filename="ventas.xlsx"');
//         header('Cache-Control: max-age=0');
//         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//         $objWriter->save('php://output');
         
         
         //alternatives
        $filename = "ventas.xls";
        ob_end_clean();
        header( "Content-type: application/vnd.ms-excel" );
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header("Pragma: no-cache");
        header("Expires: 0");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean();
    
         /*Desconecta el objeto PHPExcel para que no se quede en memoria*/
         $objPHPExcel->disconnectWorksheets();
         unset($objPHPExcel);
         
    }   
    
        /**
     * Export a Venta entity.
     *
     * @Route("/export/sindeuda/{dailydate}/{iniciodate}/{finaldate}", name="venta_export_sindeuda")
     * @Method({"GET", "POST"})
     */
    public function exportSinDeudaAction(Request $request,$dailydate=1,$iniciodate=1,$finaldate=1)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
        $em = $this->getDoctrine()->getManager();
        
         if ($dailydate !=1) {            
            $ventast = $em->getRepository('BackendBundle:Venta')->GetByFechaDiariaParam($dailydate);        
        }     
        
        if ($iniciodate !=1 && $finaldate !=1) {            
            $ventast = $em->getRepository('BackendBundle:Venta')->GetByFechaRangoParam($iniciodate,$finaldate);  
        }
        
        $ventas=array();
        foreach($ventast as $key=>$ven){
            if ($deuda= $ven->getMontototalapagar() <=  $ven->getTotalrecibido()){
                $ventas[]=$ven;
            }
        }
        
        
        $data=array();

        $numeroventas=count($ventas);
        $locationtotal=7+$numeroventas;
        $celdatotal='J' . $locationtotal;
        $celdapagado='K' . $locationtotal;
        
        $supertotal=0;
        $superecibido=0;
         foreach($ventas as $key=>$v){
             $grab=0;
             $nograb=0;
             
             if ($v->getAnulada()==true){
              print_r('aaaa');
              die();
              }
             
             
            if($v->getDocumento()->getIgv()==true){
                $grab=$v->getMontototalapagar()*0.82;
                $total=$v->getMontototalapagar();
                
                $supertotal=$supertotal+$total;
            }else
                $nograb=$v->getMontototalapagar();
            
            if( $v->getTotalrecibido()> $v->getMontototalapagar()){
                $recib=$v->getMontototalapagar();
            }else{
                 $recib=$v->getTotalrecibido();
            }
           
            
            $data[]= array(
                $v->getDocumento()->getTipodocumento(),
                $v->getNumeroVenta(),
                $v->getCliente()->getRuc().'  -  '.$v->getCliente()->getNombre(),
                $v->getVendedor()->getNombre(),
                date_format($v->getfecha(),'d/m/Y'),
                $v->getCliente()->getRazonsoc(),
                $grab,
                $nograb,
                "=".$grab.'*18%',
                $total, //  "=Sum(F".($key+7).')'
                    $recib                
            );
            $superecibido=$superecibido+$recib;
            $supertotal=$supertotal+$nograb;
         }
         
         // Crea un nuevo objeto PHPExcel
         $objPHPExcel = new PHPExcel();
         $objPHPExcel->getProperties()
             ->setCreator('MonteroPlacas')
             ->setTitle('ventas')
             ->setLastModifiedBy('Montero')
             ->setDescription('Registro de ventas')
             ->setSubject('Office 2007 XLSX Test Document')
             ->setKeywords('exportar ventas')
             ->setCategory('exportar');
         // Agregar Informacion
         $objPHPExcel ->getActiveSheet()->getColumnDimension('A')->setWidth(20);
         $objPHPExcel ->getActiveSheet()->getColumnDimension('B')->setWidth(20);
         $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
         $celda = $objPHPExcel->setActiveSheetIndex(0);
         $celda->setCellValue('A2','Empresa: MONTERO PLACAS SAC');
         $celda->setCellValue('A3','R.u.c.: 20543215385');
         $celda->mergeCells('A4:I4');
         $celda->setCellValue('A4', 'Registro de Ventas del 12/02/2018 al 13/02/2018');
         $celda->setCellValue('A6', 'NOMDOC')
             ->setCellValue('B6', 'NUMDOC')
             ->setCellValue('C6', 'RUC - NOMBRE')
             ->setCellValue('D6', 'VENDEDOR')
             ->setCellValue('E6', 'Fecha')
             ->setCellValue('F6', 'RAZCLI')
             ->setCellValue('G6', 'GRABADO')
             ->setCellValue('H6', 'NO GRABADO')
             ->setCellValue('I6', 'IGV')
             ->setCellValue('J6', 'TOTAL')
              ->setCellValue('K6', 'PAGADO');
         $celda->fromArray($data,'','A7');
          $celda->setCellValue($celdatotal, $supertotal);
          $celda->setCellValue($celdapagado, $superecibido);
         $cabecera = 'a6:k6';
         $style2 = array(
             'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
             'font' => array('bold' => true,)
         );
         $celda->getStyle($cabecera)->applyFromArray($style2);;
         $objPHPExcel->setActiveSheetIndex(0);
         $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');

         //original
//         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//         header('Content-Disposition: attachment;filename="ventas.xlsx"');
//         header('Cache-Control: max-age=0');
//         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//         $objWriter->save('php://output');
         
        //alternatives
        $filename = "ventas.xls";
        ob_end_clean();
        header( "Content-type: application/vnd.ms-excel" );
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header("Pragma: no-cache");
        header("Expires: 0");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean();
         
         /*Desconecta el objeto PHPExcel para que no se quede en memoria*/
         $objPHPExcel->disconnectWorksheets();
         unset($objPHPExcel);
         
    }   
    
    
    /**
     * Search a Venta entity.
     *
     * @Route("/search/{page}", name="venta_search")
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
            $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByFechaDiariaParamCount($dailydate);           
            $results = 10; //paginado     
            $paginator = new Helper\Paginator($ventasCount, $page, $results);
            $sort_direction = 'desc';
            $ventas = $em->getRepository('BackendBundle:Venta')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit()); 
            
            $vendido = $em->getRepository('BackendBundle:Venta')->GetTotalVendidoByFecha($dailydate);   
        }else {
            $dailydate=1;
        }     
        $fechas= $request->request->get('fechas');
        if ($fechas !=null) {
            $iniciodate = $request->request->get('iniciodate');
            $finaldate = $request->request->get('finaldate');
            $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByFechaRangoParamCount($iniciodate,$finaldate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($ventasCount, $page, $results);
            $sort_direction = 'desc';
            $ventas = $em->getRepository('BackendBundle:Venta')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit());  
            
            $vendido = $em->getRepository('BackendBundle:Venta')->GetTotalVendidoByRangoFecha($iniciodate,$finaldate);   
        }  else {
            $iniciodate=1;
            $finaldate=1;
        }   
        
     
        return $this->render('venta/search.html.twig', array(
                    "ventas" => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                    "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page, "vendido" =>$vendido));
    }
    
    /**
     * Search a Venta entity.
     *
     * @Route("/searched/{page}/{dailydate}/{iniciodate}/{finaldate}", name="venta_searched")
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
            $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByFechaDiariaParamCount($dailydate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($ventasCount, $page, $results);
            $sort_direction = 'desc';
            $ventas = $em->getRepository('BackendBundle:Venta')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit());    
            
             $vendido = $em->getRepository('BackendBundle:Venta')->GetTotalVendidoByFecha($dailydate);  
        }     
        
        if ($iniciodate !=1 && $finaldate !=1) {            
            $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByFechaRangoParamCount($iniciodate,$finaldate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($ventasCount, $page, $results);
            $sort_direction = 'desc';
            $ventas = $em->getRepository('BackendBundle:Venta')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit()); 
            
            $vendido = $em->getRepository('BackendBundle:Venta')->GetTotalVendidoByRangoFecha($iniciodate,$finaldate);   
        }
        
       
        return $this->render('venta/search.html.twig', array(
                    "ventas" => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                    "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page, "vendido" =>$vendido));
    }


    /**
     * Deletes a Venta entity.
     *
     * @Route("/{id}/delete", name="venta_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, Venta $ventum)
    {
        /*if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
            $this->addFlash(
                'danger',
                'No tiene permiso de realizar esta acción!'
            );
            return $this->redirect($this->generateUrl('main'));
        }*/
            $em = $this->getDoctrine()->getManager();
             $repository =$this->getDoctrine()->getRepository('BackendBundle:Venta');
            $repository->Delete($ventum);

        return $this->redirectToRoute('venta_index');
    }
    
    /**
     * Creates a form to delete a Venta entity.
     *
     * @param Venta $ventum The Venta entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Venta $ventum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('venta_delete', array('id' => $ventum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
        /**
     * Search  Compras entities.
     *
     * @Route("/buscar/ventas/{page}", name="buscar_ventas")
     * @Method({"GET", "POST"})
     */
    public function buscarAction(Request $request,$page=1)
    {
        $em = $this->getDoctrine()->getManager();
                        
        $categoria= $request->request->get('categoria');  
        $buscar= $request->request->get('buscar'); 
          
        $nombres = array($categoria);
        $parametros[0] = $buscar;
        
//        print_r( $parametros);
//        die();
        
        if ($buscar == null ) {
            return $this->redirectToRoute('venta_index');
        } 

        $order_by = array();
        $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByBuscarParamCount($nombres,$parametros);
        $results = 2; //paginado     
        $paginator = new Helper\Paginator($ventasCount, $page, $results);
        $ventas = $em->getRepository('BackendBundle:Venta')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
                    
        return $this->render('venta/buscar.html.twig', array(
            'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,'title' =>'Resultados de la búsqueda',
            "dailydate" => '',"iniciodate" => '',"finaldate" => ''
        ));
    }

     /**
     * Search  Compras entities.
     *
     * @Route("/buscado/ventas/{page}/{categoria}/{buscar}", name="buscado_ventas")
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
            return $this->redirectToRoute('venta_index');
        }

        $order_by = array();
        $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByBuscarParamCount($nombres,$parametros);
        $results = 2; //paginado     
        $paginator = new Helper\Paginator($ventasCount, $page, $results);
        $ventas = $em->getRepository('BackendBundle:Venta')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
                    
        return $this->render('compra/buscar.html.twig', array(
             'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,'title' =>'Resultados de la búsqueda',
            "dailydate" => '',"iniciodate" => '',"finaldate" => ''
        ));
    }
    public function mandarPdfAction(Venta $venta)
    {
        $num = str_pad($venta->getSerie(), 3, "0", STR_PAD_LEFT);
        $bol = str_pad($venta->getNumerodedocumento(), 8, "0", STR_PAD_LEFT);
        $letra = $venta->getDocumento()->getTipodocumento();
        $a1_InvoiceID = $letra[0] . $num . '-' . $bol;
        $ruc = '20543215385';
        $a4_DocumentType = $letra[0] == 'F' ? '01' : '03';
        $nombrearchivo = $ruc . '-' . $a4_DocumentType . '-' .$letra[0]. $num . '-' . $bol;
        $subtotal = 0;
        $d1_CustomerID = '88888888';
        $total = $venta->getMontototalapagar();
        if($a4_DocumentType=='03'){
            $d2_CustomerDocumentType = 1;
            if(is_null($venta->getCliente()->getDni())){
                $d2_CustomerDocumentType = 0;
            }else{
                $d1_CustomerID = $venta->getCliente()->getDni();
            }
        }
        else if($a4_DocumentType=='01'){
            $d2_CustomerDocumentType = 6;
            if (!is_null($venta->getCliente()->getRuc())) {
                $d1_CustomerID = $venta->getCliente()->getRuc();
            }
            else{
                $d2_CustomerDocumentType = 0;
            }
        }
        if ($venta->getDocumento()->getIgv()) {
            $subtotal = round($venta->getMontototalapagar() / 1.18, 10);
            $igv = round($venta->getMontototalapagar() / 1.18 * 0.18, 10);
        } else {
            $igv = 0;
        }
        $d5_CustomerStreetName = !is_null($venta->getCliente()->getDireccion()) ? $venta->getCliente()->getDireccion() : '';
        $txt = "FTHeader|" . $a1_InvoiceID . "|" . date_format($venta->getFecha(), 'Y-m-d') . "|" . date_format($venta->getFecha(), 'h:i:s') . "|" . $a4_DocumentType . "|PEN|||||VENTA GRAVADA" . "\r\n";
        $txt .= "FTSupplier|" . $ruc . "|6|Montero|MONTERO PLACAS S.A.C|36104|AV. NICOLAS DE PIEROLA NRO. 261A A.H VILLA MARIA|LIMA|LIMA|LIMA|Distrito Villa María del triunfo|PE|0001" . "\r\n";
        $txt .= "FTCustomer|" . $d1_CustomerID . "|" . $d2_CustomerDocumentType . "|" . $venta->getCliente()->getNombre() . "||" . $d5_CustomerStreetName . "|||||PE||" . "\r\n";
        foreach ($venta->getArticuloventas()->toArray() as $key => $art) {
            $txt .= "FTDetail|" . ($key + 1) . "|NIU|" . $art->getCantidad() . "||||" . $art->getArticulo()->getNombre() .
                "|" . round($art->getPrecio() / 1.18, 10) . "|" . ($art->getPrecio()) . "|01|||" . round($art->getImporte() / 1.18 * 0.18, 10) .
                "|" . round($art->getImporte() / 1.18 * 0.18, 10) . "|10|1000|IGV|VAT|18||||||||||||||" . round($art->getImporte() / 1.18, 10) .
                "|||||||||||||||||" . "\r\n";
        }
        $txt .= 'FTTotals|||1001|' . $subtotal . '||||||||0||||' . $igv . '|' . $igv . '|1000|IGV|VAT|||||||||||||' . $venta->getMontototalapagar() . '|1005|' . $subtotal . "\r\n";
        $txt .= 'FTLegends|||||0101' . "\r\n";
        $txt .= 'FTInfo||||||||||||||||||||';
        $fs = new Filesystem();
        $archivo = $this->container->getParameter('kernel.root_dir') . '/data/informes/' . $nombrearchivo . '.txt';
        try {
            $fs->dumpFile($archivo, $txt);
        } catch (IOExceptionInterface $e) {
            echo "Se ha producido un error al crear el archivo " . $e->getPath();
        }
        $token = 'vsLKhT1Szv6MnWmDpDdcNlD9jDgnEx6r0oh21fdU0nOFQVP1ppKVwwBi3ghndAnr';
        $curl = curl_init();
        $contenido = $txt;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://devservice.dmsfact.com/rest/sendDocumentTxt",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $contenido,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Content-Type: text/plain",
                //4 = devolver pdf  formato tiket, sino se envia no se devuelve.
                //1 = devolver pdf formato A4, sino se envia no se devuelve.
                "ReturnFormat: 1",
                "ReturnEncoding:3",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            //$this->addFlash('danger', "cURL Error #:" . $err);
            // $this->addFlash('danger', "Error al registrar la venta en el facturador DMS");
        } else {
            $responseJson = json_decode($response);
        }
        $fichero = $this->container->getParameter('kernel.root_dir') . '/data/informes/' . $nombrearchivo . '.pdf';
        if (isset($responseJson)) {
            if ($responseJson->ContentBase64String == '') {
                $msg = 'Mensaje: ' . $responseJson->ErrorMessage . '<br>' .
                    'Empresa: Montero Placas' . '<br>' .
                    'Token: ' . $token . '<br>' .
                    'Comprobante: ' . $nombrearchivo . '<br>';
                $this->addFlash('danger', $msg);
            }
            try {
                $fs->dumpFile($fichero, base64_decode($responseJson->ContentBase64String));
            } catch (IOExceptionInterface $e) {
                $this->addFlash('danger', "Se ha producido un error al crear el archivo " . $e->getPath()
                );
            }
        }
    }

    public function mandarNotaCreditoAction(Venta $venta)
    {
        $num = str_pad($venta->getSerie(), 3, "0", STR_PAD_LEFT);
        $bol = str_pad($venta->getNumerodedocumento(), 8, "0", STR_PAD_LEFT);
        $letra = $venta->getDocumento()->getTipodocumento();
        $a1_InvoiceID = $letra[0] . $num . '-' . $bol;
        $ruc = '20543215385';
        $a4_DocumentType = $letra[0] == 'F' ? '01' : '03';
        $nombrearchivo = $ruc . '-' . $a4_DocumentType . '-' .$letra[0]. $num . '-' . $bol;
        $subtotal = 0;
        $d1_CustomerID = '88888888';
        $total = $venta->getMontototalapagar();
        if($a4_DocumentType=='03'){
            $d2_CustomerDocumentType = 1;
            if(is_null($venta->getCliente()->getDni())){
                $d2_CustomerDocumentType = 0;
            }else{
                $d1_CustomerID = $venta->getCliente()->getDni();
            }
        }
        else if($a4_DocumentType=='01'){
            $d2_CustomerDocumentType = 6;
            if (!is_null($venta->getCliente()->getRuc())) {
                $d1_CustomerID = $venta->getCliente()->getRuc();
            }
            else{
                $d2_CustomerDocumentType = 0;
            }
        }
        if ($venta->getDocumento()->getIgv()) {
            $subtotal = round($venta->getMontototalapagar() / 1.18, 10);
            $igv = round($venta->getMontototalapagar() / 1.18 * 0.18, 10);
        } else {
            $igv = 0;
        }
        $d5_CustomerStreetName = !is_null($venta->getCliente()->getDireccion()) ? $venta->getCliente()->getDireccion() : '';
        $txt = "FTHeader|" . $a1_InvoiceID . "|" . date_format($venta->getFecha(), 'Y-m-d') . "|" . date_format($venta->getFecha(), 'h:i:s') . "|" . $a4_DocumentType . "|PEN|||||VENTA GRAVADA" . "\r\n";
        $txt .= "FTSupplier|" . $ruc . "|6|Montero|MONTERO PLACAS S.A.C|36104|AV. NICOLAS DE PIEROLA NRO. 261A A.H VILLA MARIA|LIMA|LIMA|LIMA|Distrito Villa María del triunfo|PE|0001" . "\r\n";
        $txt .= "FTCustomer|" . $d1_CustomerID . "|" . $d2_CustomerDocumentType . "|" . $venta->getCliente()->getNombre() . "||" . $d5_CustomerStreetName . "|||||PE||" . "\r\n";
        foreach ($venta->getArticuloventas()->toArray() as $key => $art) {
            $txt .= "FTDetail|" . ($key + 1) . "|NIU|" . $art->getCantidad() . "||||" . $art->getArticulo()->getNombre() .
                "|" . round($art->getPrecio() / 1.18, 10) . "|" . ($art->getPrecio()) . "|01|||" . round($art->getImporte() / 1.18 * 0.18, 10) .
                "|" . round($art->getImporte() / 1.18 * 0.18, 10) . "|10|1000|IGV|VAT|18||||||||||||||" . round($art->getImporte() / 1.18, 10) .
                "|||||||||||||||||" . "\r\n";
        }
        $txt .= 'FTTotals|||1001|' . $subtotal . '||||||||0||||' . $igv . '|' . $igv . '|1000|IGV|VAT|||||||||||||' . $venta->getMontototalapagar() . '|1005|' . $subtotal . "\r\n";
        $txt .= 'FTLegends|||||0101' . "\r\n";
        $txt .= 'FTInfo||||||||||||||||||||';
        $fs = new Filesystem();
        $archivo = $this->container->getParameter('kernel.root_dir') . '/data/informes/' . $nombrearchivo . '.txt';
        try {
            $fs->dumpFile($archivo, $txt);
        } catch (IOExceptionInterface $e) {
            echo "Se ha producido un error al crear el archivo " . $e->getPath();
        }
        $token = 'vsLKhT1Szv6MnWmDpDdcNlD9jDgnEx6r0oh21fdU0nOFQVP1ppKVwwBi3ghndAnr';
        $curl = curl_init();
        $contenido = $txt;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://devservice.dmsfact.com/rest/sendDocumentTxt",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $contenido,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Content-Type: text/plain",
                //4 = devolver pdf  formato tiket, sino se envia no se devuelve.
                //1 = devolver pdf formato A4, sino se envia no se devuelve.
                "ReturnFormat: 1",
                "ReturnEncoding:3",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            //$this->addFlash('danger', "cURL Error #:" . $err);
            // $this->addFlash('danger', "Error al registrar la venta en el facturador DMS");
        } else {
            $responseJson = json_decode($response);
        }
        $fichero = $this->container->getParameter('kernel.root_dir') . '/data/informes/' . $nombrearchivo . '.pdf';
        if (isset($responseJson)) {
            if ($responseJson->ContentBase64String == '') {
                $msg = 'Mensaje: ' . $responseJson->ErrorMessage . '<br>' .
                    'Empresa: Montero Placas' . '<br>' .
                    'Token: ' . $token . '<br>' .
                    'Comprobante: ' . $nombrearchivo . '<br>';
                $this->addFlash('danger', $msg);
            }
            try {
                $fs->dumpFile($fichero, base64_decode($responseJson->ContentBase64String));
            } catch (IOExceptionInterface $e) {
                $this->addFlash('danger', "Se ha producido un error al crear el archivo " . $e->getPath()
                );
            }
        }
    }
    /**
     * Export a Venta entity.
     *
     * @Route("/dms/enviardmf/{id}", name="venta_enviardmf")
     * @Method({"GET", "POST"})
     */
    public function enviarDmsAction(Request $request, Venta $venta, $page = 1){
        $this->mandarPdfAction($venta);
        return $this->redirectToRoute('venta_index');
    }

    /**
     * Export a Venta entity.
     *
     * @Route("/dms/bajarpdf/{id}", name="venta_bajarpdf")
     * @Method({"GET", "POST"})
     */
    public function bajarpdfAction(Request $request, Venta $venta, $page = 1)
    {
        $num = str_pad($venta->getSerie(), 3, "0", STR_PAD_LEFT);
        $bol = str_pad($venta->getNumerodedocumento(), 8, "0", STR_PAD_LEFT);
        $letra = $venta->getDocumento()->getTipodocumento();
        $ruc = '20543215385';
        $a4_DocumentType = $letra[0] == 'F' ? '01' : '03';
        $nombrearchivo =$ruc  . '-' . $a4_DocumentType . '-' .$letra[0]. $num . '-' . $bol;
        $fichero = $this->container->getParameter('kernel.root_dir') . '/data/informes/' . $nombrearchivo . '.pdf';
        $flag = true;
            // TOKEN de autenticacion
            $token = 'vsLKhT1Szv6MnWmDpDdcNlD9jDgnEx6r0oh21fdU0nOFQVP1ppKVwwBi3ghndAnr';
            $arr = array(
                'DocumentType' => $a4_DocumentType,
                'Serie' => $letra[0] . $num,
                'Number' => $bol,
                'IncludePDF' => true,
                'ReturnEncoding' => 1
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://devservice.dmsfact.com/rest/getDocument",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($arr),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $token,
                    "Content-Type: application/json",
                    //4 = devolver pdf  formato tiket, sino se envia no se devuelve.
                    //1 = devolver pdf formato A4, sino se envia no se devuelve.
                    "ReturnFormat: 1",
                    "ReturnEncoding: 3",// devolver la respuesta en formato bae64String
                    "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
               // $this->addFlash('danger', "cURL Error #:" . $err);
                $flag = false;
            } else {
                $responseJson = json_decode($response);
            }
            if (isset($responseJson)) {
                if ($responseJson->RequestState == '6') {
                    $this->addFlash('danger', $responseJson->ErrorMessage);
                    $flag = false;
                } else {
                    if ($responseJson->ContentBase64String == '') {
                        $msg = 'Mensaje: ' . $responseJson->ErrorMessage . '<br>' .
                            'Empresa: Montero Placas' . '<br>' .
                            'Token: ' . $token . '<br>' .
                            'Comprobante: ' . $nombrearchivo . '<br>';
                      //  $this->addFlash('danger', $msg);
                        $flag = false;
                    }
                    $fs = new Filesystem();
                    try {
                        $fs->dumpFile($fichero, base64_decode($responseJson->ContentBase64String));
                    } catch (IOExceptionInterface $e) {
                       // $this->addFlash('danger', "Se ha producido un error al crear el archivo " . $e->getPath());
                        $flag = false;
                    }
                }
            } else
                $flag = false;
       
        if ($flag) {
            $content = file_get_contents($fichero);
            $response = new Response();
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition',
                'attachment;filename=' . $nombrearchivo . '.pdf');
            $response->setContent($content);
            return $response;
        } else
            return $this->redirectToRoute('venta_index');
    }


}

// FUNCIONES DE CONVERSION DE NUMEROS A LETRAS.
 
function centimos()
{
	global $importe_parcial;
         
	$importe_parcial = number_format($importe_parcial, 2, ".", "") * 100;
 
	if ($importe_parcial > 0)
		$num_letra = " con ".decena_centimos($importe_parcial) .  " SOLES";
	else
		$num_letra = " con 00/100 SOLES";
 
	return $num_letra;
}
 
function unidad_centimos($numero)
{ 
   
    $numero=  round($numero);
    switch ($numero) {
        case $numero>8 and $numero<=9: {
                $num_letra = "9/100";
                break;
            }
        case $numero>7 and $numero<=8: {
                $num_letra = "8/100";
                break;
            }
        case $numero>6 and $numero<=7: {
                $num_letra = "7/100";
                break;
            }
        case $numero>5 and $numero<=6: {
                $num_letra = "6/100";
                break;
            }
        case $numero>4 and $numero<=5: {
                $num_letra = "5/100";
                break;
            }
        case $numero>3 and $numero<=4: {
                $num_letra = "4/100";
                break;
            }
        case $numero>2 and $numero<=3: {
                $num_letra = "3/100";
                break;
            }
        case $numero>1 and $numero<=2: {
                $num_letra = "2/100";
                break;
            }
        case $numero>0 and $numero<=1: {
                $num_letra = "1/100";
                break;
            }
        case $numero==0: {
                $num_letra = "00/100";
                break;
        }
    }
    
	return $num_letra;
}
 
function decena_centimos($numero)
{   
	if ($numero >= 10)
	{
		if ($numero >= 90 && $numero <= 99)
		{
			  if ($numero == 90)
				  return "90/100";
			  else if ($numero == 91)
				  return "91/100";
			  else
				  return "9".unidad_centimos($numero - 90);
		}
		if ($numero >= 80 && $numero <= 89)
		{
			if ($numero == 80)
				return "80/100";
			else if ($numero == 81)
				return "81/100";
			else
				return "8".unidad_centimos($numero - 80);
		}
		if ($numero >= 70 && $numero <= 79)
		{
			if ($numero == 70)
				return "70/100";
			else if ($numero == 71)
				return "71/100";
			else
				return "7".unidad_centimos($numero - 70);
		}
		if ($numero >= 60 && $numero <= 69)
		{                     
			if ($numero == 60)
				return "60/100";
			else if ($numero == 61)
				return "61/100";
			else
				return "6".unidad_centimos($numero - 60);
		}
		if ($numero >= 50 && $numero <= 59)
		{
			if ($numero == 50)
				return "50/100";
			else if ($numero == 51)
				return "51/100";
			else
				return "5".unidad_centimos($numero - 50);
		}
		if ($numero >= 40 && $numero <= 49)
		{
			if ($numero == 40)
				return "40/100";
			else if ($numero == 41)
				return "41/100";
			else
				return "4".unidad_centimos($numero - 40);
		}
		if ($numero >= 30 && $numero <= 39)
		{
			if ($numero == 30)
				return "30/100";
			else if ($numero == 31)
				return "31/100";
			else
				return "3".unidad_centimos($numero - 30);
		}
		if ($numero >= 20 && $numero <= 29)
		{
			if ($numero == 20)
				return "20/100";
			else if ($numero == 21)
				return "21/100";
			else
				return "2".unidad_centimos($numero - 20);
		}
		if ($numero >= 10 && $numero <= 19)
		{
			if ($numero == 10)
				return "10/100";
			else if ($numero == 11)
				return "11/100";
			else if ($numero == 12)
				return "12/100";
			else if ($numero == 13)
				return "13/100";
			else if ($numero == 14)
				return "14/100";
			else if ($numero == 15)
				return "15/100";
			else if ($numero == 16)
				return "16/100";
			else if ($numero == 17)
				return "17/100";
			else if ($numero == 18)
				return "18/100";
			else if ($numero == 19)
				return "19/100";
		}
	}
	else
		return unidad_centimos($numero);
}
 
function unidad($numero)
{
	switch ($numero)
	{
		case 9:
		{
			$num = "nueve";
			break;
		}
		case 8:
		{
			$num = "ocho";
			break;
		}
		case 7:
		{
			$num = "siete";
			break;
		}
		case 6:
		{
			$num = "seis";
			break;
		}
		case 5:
		{
			$num = "cinco";
			break;
		}
		case 4:
		{
			$num = "cuatro";
			break;
		}
		case 3:
		{
			$num = "tres";
			break;
		}
		case 2:
		{
			$num = "dos";
			break;
		}
		case 1:
		{
			$num = "uno";
			break;
		}
	}
	return $num;
}

function pesos(){
    $num_letra = $num_letra . ' pesos con ';
}

function decena($numero)
{
	if ($numero >= 90 && $numero <= 99)
	{
		$num_letra = "noventa ";
 
		if ($numero > 90)
			$num_letra = $num_letra."y ".unidad($numero - 90);
	}
	else if ($numero >= 80 && $numero <= 89)
	{
		$num_letra = "ochenta ";
 
		if ($numero > 80)
			$num_letra = $num_letra."y ".unidad($numero - 80);
	}
	else if ($numero >= 70 && $numero <= 79)
	{
			$num_letra = "setenta ";
 
		if ($numero > 70)
			$num_letra = $num_letra."y ".unidad($numero - 70);
	}
	else if ($numero >= 60 && $numero <= 69)
	{
		$num_letra = "sesenta ";
 
		if ($numero > 60)
			$num_letra = $num_letra."y ".unidad($numero - 60);
	}
	else if ($numero >= 50 && $numero <= 59)
	{
		$num_letra = "cincuenta ";
 
		if ($numero > 50)
			$num_letra = $num_letra."y ".unidad($numero - 50);
	}
	else if ($numero >= 40 && $numero <= 49)
	{
		$num_letra = "cuarenta ";
 
		if ($numero > 40)
			$num_letra = $num_letra."y ".unidad($numero - 40);
	}
	else if ($numero >= 30 && $numero <= 39)
	{
		$num_letra = "treinta ";
 
		if ($numero > 30)
			$num_letra = $num_letra."y ".unidad($numero - 30);
	}
	else if ($numero >= 20 && $numero <= 29)
	{
		if ($numero == 20)
			$num_letra = "veinte ";
		else
			$num_letra = "veinti".unidad($numero - 20);
	}
	else if ($numero >= 10 && $numero <= 19)
	{
		switch ($numero)
		{
			case 10:
			{
				$num_letra = "diez ";
				break;
			}
			case 11:
			{
				$num_letra = "once ";
				break;
			}
			case 12:
			{
				$num_letra = "doce ";
				break;
			}
			case 13:
			{
				$num_letra = "trece ";
				break;
			}
			case 14:
			{
				$num_letra = "catorce ";
				break;
			}
			case 15:
			{
				$num_letra = "quince ";
				break;
			}
			case 16:
			{
				$num_letra = "dieciseis ";
				break;
			}
			case 17:
			{
				$num_letra = "diecisiete ";
				break;
			}
			case 18:
			{
				$num_letra = "dieciocho ";
				break;
			}
			case 19:
			{
				$num_letra = "diecinueve ";
				break;
			}
		}
	}
	else
		$num_letra = unidad($numero);
 
	return $num_letra;
}
 
function centena($numero)
{
	if ($numero >= 100)
	{
		if ($numero >= 900 & $numero <= 999)
		{
			$num_letra = "novecientos ";
 
			if ($numero > 900)
				$num_letra = $num_letra.decena($numero - 900);
		}
		else if ($numero >= 800 && $numero <= 899)
		{
			$num_letra = "ochocientos ";
 
			if ($numero > 800)
				$num_letra = $num_letra.decena($numero - 800);
		}
		else if ($numero >= 700 && $numero <= 799)
		{
			$num_letra = "setecientos ";
 
			if ($numero > 700)
				$num_letra = $num_letra.decena($numero - 700);
		}
		else if ($numero >= 600 && $numero <= 699)
		{
			$num_letra = "seiscientos ";
 
			if ($numero > 600)
				$num_letra = $num_letra.decena($numero - 600);
		}
		else if ($numero >= 500 && $numero <= 599)
		{
			$num_letra = "quinientos ";
 
			if ($numero > 500)
				$num_letra = $num_letra.decena($numero - 500);
		}
		else if ($numero >= 400 && $numero <= 499)
		{
			$num_letra = "cuatrocientos ";
 
			if ($numero > 400)
				$num_letra = $num_letra.decena($numero - 400);
		}
		else if ($numero >= 300 && $numero <= 399)
		{
			$num_letra = "trescientos ";
 
			if ($numero > 300)
				$num_letra = $num_letra.decena($numero - 300);
		}
		else if ($numero >= 200 && $numero <= 299)
		{
			$num_letra = "doscientos ";
 
			if ($numero > 200)
				$num_letra = $num_letra.decena($numero - 200);
		}
		else if ($numero >= 100 && $numero <= 199)
		{
			if ($numero == 100)
				$num_letra = "cien ";
			else
				$num_letra = "ciento ".decena($numero - 100);
		}
	}
	else
		$num_letra = decena($numero);
 
	return $num_letra;
}
 
function cien()
{
	global $importe_parcial;
 
	$parcial = 0; $car = 0;
 
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
 
	if ($importe_parcial >= 1 && $importe_parcial <= 9.99)
		$car = 1;
	else if ($importe_parcial >= 10 && $importe_parcial <= 99.99)
		$car = 2;
	else if ($importe_parcial >= 100 && $importe_parcial <= 999.99)
		$car = 3;
 
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
 
         
	$num_letra = centena($parcial).centimos();
 
	return $num_letra;
}
 
function cien_mil()
{
	global $importe_parcial;
 
	$parcial = 0; $car = 0;
 
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
 
	if ($importe_parcial >= 1000 && $importe_parcial <= 9999.99)
		$car = 1;
	else if ($importe_parcial >= 10000 && $importe_parcial <= 99999.99)
		$car = 2;
	else if ($importe_parcial >= 100000 && $importe_parcial <= 999999.99)
		$car = 3;
 
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
 
	if ($parcial > 0)
	{
		if ($parcial == 1)
			$num_letra = "mil ";
		else
			$num_letra = centena($parcial)." mil ";
	}
 
	return $num_letra;
}
 
 
function millon()
{
	global $importe_parcial;
 
	$parcial = 0; $car = 0;
 
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
 
	if ($importe_parcial >= 1000000 && $importe_parcial <= 9999999.99)
		$car = 1;
	else if ($importe_parcial >= 10000000 && $importe_parcial <= 99999999.99)
		$car = 2;
	else if ($importe_parcial >= 100000000 && $importe_parcial <= 999999999.99)
		$car = 3;
 
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
 
	if ($parcial == 1)
		$num_letras = "un millón ";
	else
		$num_letras = centena($parcial)." millones ";
 
	return $num_letras;
}
 
function convertir_a_letras($numero)
{
    
	global $importe_parcial;
 
	$importe_parcial = $numero;
        
	if ($numero < 1000000000)
	{
		if ($numero >= 1000000 && $numero <= 999999999.99)
			$num_letras = millon().cien_mil().cien();
		else if ($numero >= 1000 && $numero <= 999999.99)
			$num_letras = cien_mil().cien();
		else if ($numero >= 1 && $numero <= 999.99)
                         {$num_letras = cien();}
		else if ($numero >= 0.01 && $numero <= 0.99)
		{
                        if ($numero == 0.01) {
                                $num_letras = "CERO CON " . "01/100 SOLES";
                       } else {
//                                $num_letras = convertir_a_letras(($numero * 100)."/100")." céntimos";
                                $num_letras = "CERO CON " . ($numero * 100) . "/100 SOLES";
//                                print_r($num_letras);die();
                        }
                    //				$num_letras = convertir_a_letras(($numero * 100)."/100")." céntimos";
                 }
         }
	return $num_letras;
}