<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Deuda;
use Sistemadmin\BackendBundle\Form\DeudaType;
use Doctrine\ORM\EntityRepository;
use PHPExcel;
use PHPExcel_IOFactory;

use Sistemadmin\BackendBundle\Helper;
/**
 * Deuda controller.
 *
 * @Route("/deuda")
 */
class DeudaController extends Controller
{
    /**
     * Lists all Deuda entities.
     *
     * @Route("/{page}", name="deuda_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
         $em = $this->getDoctrine()->getManager();
        
        $order_by = array();
        $deudasCount = $em->getRepository('BackendBundle:Deuda')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($deudasCount, $page, $results);
        $deudas = $em->getRepository('BackendBundle:Deuda')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='deuda_index';

        return $this->render('deuda/index.html.twig', array(
            'deudas' => $deudas,
            'sort_dir' => $sort_direction,
            'paginator' => $paginator,
             "page" =>$page,
            "pathern" =>$pathern
        ));        
    }

    /**
     * Finds and displays a Deuda entity.
     *
     * @Route("/show/{id}", name="deuda_show")
     * @Method("GET")
     */
    public function showAction(Deuda $deuda)
    {
//        $deleteForm = $this->createDeleteForm($deuda);

        return $this->render('deuda/show.html.twig', array(
            'deuda' => $deuda,
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Venta entity.
     *
     * @Route("/{id}/delete", name="deuda_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, Deuda $deuda)
    {             
             $repository =$this->getDoctrine()->getRepository('BackendBundle:Deuda');
            $repository->DeleteDeuda($deuda);

        return $this->redirectToRoute('deuda_index');
    }

    /**
     * Export a Compra entity.
     *
     * @Route("/export/", name="deuda_export")
     * @Method({"GET", "POST"})
     */
    public function exportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deuda = $em->getRepository('BackendBundle:Deuda')->findAll();
        $data=array();
        foreach($deuda as $key=>$c){
            $data[]= array(
                $c->getFechainicio()!==null? date_format($c->getFechainicio(),'d/m/Y'):'',
                $c-> getFechacancelacion()!==null? date_format($c-> getFechacancelacion(),'d/m/Y'):'',
                $c->getSerie().'-'. $c->getNumerofactura(),
                $c->getCliente()->getNombre(),
                $c->getTotalapagar(),
                $c->getTotalapagar()- $c->getDeuda(),
                $c->getDeuda(),
            );
        }
        // Crea un nuevo objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
            ->setCreator('MonteroPlacas')
            ->setTitle('deudas')
            ->setLastModifiedBy('Montero')
            ->setDescription('Registro de deudas')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setKeywords('exportar deudas')
            ->setCategory('exportar');
        // Agregar Informacion
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
        $celda = $objPHPExcel->setActiveSheetIndex(0);
        $celda->setCellValue('A2','Empresa: MONTERO PLACAS SAC');
        $celda->setCellValue('A3','R.u.c.: 20543215385');
        $celda->mergeCells('A4:I4');
        $celda->setCellValue('A4', 'Registro de deudas ');
        $celda->setCellValue('A6', 'Fecha de inicio')
            ->setCellValue('B6', 'Fecha de cancelación')
            ->setCellValue('C6', 'Referencia')
            ->setCellValue('D6', 'Cliente')
            ->setCellValue('E6', 'Total a pagar')
            ->setCellValue('F6', 'A cuenta')
            ->setCellValue('G6', 'Deuda');
        $celda->fromArray($data,'','A7');
        $cabecera = 'a6:g6';
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'font' => array('bold' => true,)
        );
        $celda->getStyle($cabecera)->applyFromArray($style2);
        $objPHPExcel->setActiveSheetIndex(0);
        $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');
        
//        header('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
//        header('Content-Type: application/vnd.ms-excel;');
//        header("Content-type: application/x-msexcel");
//        header('Pragma', 'public');
//        header('Content-Disposition: attachment;filename="deudas.xls"');
//        header('Cache-Control: max-age=0');
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->setPreCalculateFormulas(true);
//        $objWriter->save('php://output');
        
        
        //alternatives
            $filename = "deudas.xls";
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
     * Search a Deuda entity.
     *
     * @Route("/search/{page}", name="deuda_search")
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
            $deudasCount = $em->getRepository('BackendBundle:Deuda')->GetByFechaDiariaParamCount($dailydate);           
            $results = 10; //paginado     
            $paginator = new Helper\Paginator($deudasCount, $page, $results);
            $sort_direction = 'desc';
            $deudas = $em->getRepository('BackendBundle:Deuda')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit()); 
            
        }else {
            $dailydate=1;
        }     
        $fechas= $request->request->get('fechas');
        if ($fechas !=null) {
            $iniciodate = $request->request->get('iniciodate');
            $finaldate = $request->request->get('finaldate');
            $deudasCount = $em->getRepository('BackendBundle:Deuda')->GetByFechaRangoParamCount($iniciodate,$finaldate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($deudasCount, $page, $results);
            $sort_direction = 'desc';
            $deudas = $em->getRepository('BackendBundle:Deuda')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit());  
            
        }  else {
            $iniciodate=1;
            $finaldate=1;
        }   
        
     
        return $this->render('deuda/search.html.twig', array(
                    "deudas" => $deudas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                    "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page));
    }
    
     /**
     * Searched a Deuda entity.
     *
     * @Route("/searched/{page}/{dailydate}/{iniciodate}/{finaldate}", name="deuda_searched")
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
            $deudasCount = $em->getRepository('BackendBundle:Deuda')->GetByFechaDiariaParamCount($dailydate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($deudasCount, $page, $results);
            $sort_direction = 'desc';
            $deudas = $em->getRepository('BackendBundle:Deuda')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit());    
            
        }     
        
        if ($iniciodate !=1 && $finaldate !=1) {            
            $deudasCount = $em->getRepository('BackendBundle:Deuda')->GetByFechaRangoParamCount($iniciodate,$finaldate);           
            $results = 10; //paginado
            $paginator = new Helper\Paginator($deudasCount, $page, $results);
            $sort_direction = 'desc';
            $deudas = $em->getRepository('BackendBundle:Deuda')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit()); 
            
        }
        
       
        return $this->render('deuda/search.html.twig', array(
             "deudas" => $deudas,
            'sort_dir' => $sort_direction,
            'paginator' => $paginator,
             "dailydate" => $dailydate,
            "iniciodate" => $iniciodate,
            "finaldate" => $finaldate, "page" =>$page));
    }
    
       /**
     * Export por vendedor
     *
     * @Route("/porcliente/", name="deudas_cliente")
     * @Method({"GET", "POST"})
     */
    public function porClienteAction(Request $request)
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
            ->add('cliente', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
                'label' => 'Cliente:',
                'class' => 'BackendBundle:Cliente',
                'placeholder' => 'Seleccione...',
                'attr' => array('class' => 'form-control')
            ))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $datosForm = $form->getData();

            $deudas = $em->getRepository('BackendBundle:Deuda')->getDeudasActivasPorCliente(array(
                'cliente' => $datosForm['cliente']->getId(),
                'fechai' => date_format($datosForm['fechainicio'], 'Y-m-d'),
                'fechaf' => date_format($datosForm['fechafin'], 'Y-m-d')
            ));
            
             $deudasinactivas = $em->getRepository('BackendBundle:Deuda')->getDeudasInActivasPorCliente(array(
                'cliente' => $datosForm['cliente']->getId(),
                'fechai' => date_format($datosForm['fechainicio'], 'Y-m-d'),
                'fechaf' => date_format($datosForm['fechafin'], 'Y-m-d')
            ));
            
            $cliente=$datosForm['cliente']; 
            if (count($deudas)>0) {
                $cliente->setDeuda(1);  
            }else{
                $cliente->setDeuda(0);            
            }
            $em->persist($cliente);
            $em->flush();
            
            
            return $this->render('deuda/deudasporcliente.html.twig', array(
                    "deudas" => $deudas,  "deudasinactivas" => $deudasinactivas,"clientename" => $datosForm['cliente']->getNombre(), 
                    "iniciodate" => date_format($datosForm['fechainicio'], 'Y-m-d'),"finaldate" =>  date_format($datosForm['fechafin'], 'Y-m-d')));
            
        }
        return $this->render('deuda/porcliente.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    
         /**
     * Search  Compras entities.
     *
     * @Route("/buscar/deudas/{page}", name="buscar_deudas")
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
            return $this->redirectToRoute('deuda_index');
        } 

        $order_by = array();
        $deudasCount = $em->getRepository('BackendBundle:Deuda')->GetByBuscarParamCount($nombres,$parametros);
        $results = 2; //paginado     
        $paginator = new Helper\Paginator($deudasCount, $page, $results);
        $deudas = $em->getRepository('BackendBundle:Deuda')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
                    
        return $this->render('deuda/buscar.html.twig', array(
            'deudas' => $deudas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,'title' =>'Resultados de la búsqueda',
            "dailydate" => '',"iniciodate" => '',"finaldate" => ''
        ));
    }

     /**
     * Search  Compras entities.
     *
     * @Route("/buscado/deudas/{page}/{categoria}/{buscar}", name="buscado_deudas")
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
            return $this->redirectToRoute('deuda_index');
        }

        $order_by = array();
        $deudasCount = $em->getRepository('BackendBundle:Deuda')->GetByBuscarParamCount($nombres,$parametros);
        $results = 2; //paginado     
        $paginator = new Helper\Paginator($deudasCount, $page, $results);
        $deudas = $em->getRepository('BackendBundle:Deuda')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
                    
        return $this->render('deuda/buscar.html.twig', array(
             'deudas' => $deudas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,'title' =>'Resultados de la búsqueda',
            "dailydate" => '',"iniciodate" => '',"finaldate" => ''
        ));
    }
}
