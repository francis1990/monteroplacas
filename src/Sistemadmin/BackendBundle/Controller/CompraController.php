<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Compra;
use Sistemadmin\BackendBundle\Form\CompraType;
use Sistemadmin\BackendBundle\Helper;

use PHPExcel;
use PHPExcel_IOFactory;

/**
 * Compra controller.
 *
 * @Route("/compra")
 */
class CompraController extends Controller
{

    /**
     * Export a Compra entity.
     *
     * @Route("/porproveedor/", name="compras_proveedor")
     * @Method({"GET", "POST"})
     */
    public function porProveedorAction(Request $request)
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('fechainicio', 'date',array(
                'widget' => 'single_text',
                'label' => 'Fecha inicio:',
                'format' => 'MM/dd/yyyy',
                'attr' => array('class' => 'form-control  dp_modal '),
                'html5' => false,
            )) ->add('fechafin', 'date',array(
                'widget' => 'single_text',
                'label' => 'Fecha Fin:',
                'format' => 'MM/dd/yyyy',
                'attr' => array('class' => 'form-control  dp_modal '),
                'html5' => false,
            ))
            ->add('proveedor', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
                'label' => 'Proveedor:',
                'class' => 'BackendBundle:Proveedor',
                'placeholder' => 'Seleccione...',
                'required'=>false,
                'attr' => array('class' => 'form-control')
            ))
            ->getForm();
        $form->handleRequest($request);
        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $datosForm=$form->getData();

            $compra = $em->getRepository('BackendBundle:Compra')->getComprasPorProveedor(array(
                'proveedor'=> $datosForm['proveedor']->getId(),
                'fechai'=>date_format($datosForm['fechainicio'],'Y-m-d'),
                'fechaf'=>date_format($datosForm['fechafin'],'Y-m-d')
            ));
            $data = array();
            foreach ($compra as $key => $v) {
                $data[] = array(
                    $v->getArticulo()->getAbreviatura(),
                    $v->getArticulo()->getNombre(),
                  //  $v->getArticulo()->getMedida(),
                    $v->getCantidad(),
                    $v->getImporte(),
                    date_format($v->getCompra()->getFechacompra(), 'd/m/Y'),
                    $v->getCompra()->getNumerofactura(),
                    $v->getCompra()->getProveedor()->getRuc(),
                    $v->getCompra()->getProveedor()->getNombre(),
                    $v->getCantidad()*$v->getImporte()
                   /* $v->getCompra()->getVendedor()->getNumerodocumento(),*/
                );
            }
            // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                ->setCreator('MonteroPlacas')
                ->setTitle('comprasporproveedor')
                ->setLastModifiedBy('Montero')
                ->setDescription('Compras por proveedor')
                ->setSubject('Office 2007 XLSX Test Document')
                ->setKeywords('exportar compras por proveedor')
                ->setCategory('exportar');
            // Agregar Informacion
            $fei=date_format($datosForm['fechainicio'],'d/m/Y');
            $fef=date_format($datosForm['fechafin'],'d/m/Y');
            $range="Compra de Productos por Proveedor del ".$fei." al ".$fef."";
            //dump($range);die;
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
            $celda = $objPHPExcel->setActiveSheetIndex(0);
            $celda->setCellValue('A2', 'Empresa: MacroNetSystem S.A.C. ');
            $celda->setCellValue('A3', 'R.u.c.: 10203040506');
            $celda->mergeCells('A4:I4');
            $celda->setCellValue('A4', $range);
            $celda->setCellValue('A6', 'CODART')
                ->setCellValue('B6', 'NOMART')
                ->setCellValue('C6', 'CANTI')
                ->setCellValue('D6', 'IMPORTE')
                ->setCellValue('E6', 'FECHA')
                ->setCellValue('F6', 'REFERENCIA')
                ->setCellValue('G6', 'RUCPRO')
                ->setCellValue('H6', 'NOMPRO')
                ->setCellValue('I6', 'TOTAL');
               // ->setCellValue('H6', 'CODINT');
            $celda->fromArray($data, '', 'A7');
            $cabecera = 'a6:i6';
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
//            header('Content-Disposition: attachment;filename="comprasporproveedor.xls"');
//            header('Cache-Control: max-age=0');
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->setPreCalculateFormulas(true);
//            $objWriter->save('php://output');
            
            
            //alternatives
            $filename = "comprasporproveedor.xls";
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
        return $this->render('compra/porproveedor.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * Creates a new Compra entity.
     *
     * @Route("/new", name="compra_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $compra = new Compra();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\CompraType', $compra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($compra);
            $em->flush();

            $repository = $em->getRepository('BackendBundle:Compra');

            return $this->redirectToRoute('compra_show', array('id' => $compra->getId()));
        }

        return $this->render('compra/new.html.twig', array(
            'compra' => $compra,
            'form' => $form->createView(),
        ));
    }
    /**
     * Lists all Compra entities.
     *
     * @Route("/{page}", name="compra_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();
        $order_by = array();
        $compraCount = $em->getRepository('BackendBundle:Compra')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($compraCount, $page, $results);
        $compras = $em->getRepository('BackendBundle:Compra')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='compra_index';
        return $this->render('compra/index.html.twig', array(
            'compras' => $compras, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
            "page" =>$page,"pathern" =>$pathern,
            'title'=>'Lista de compras',"iniciodate" => '',"finaldate" => '','dailydate'=>''
        ));
    }

    /**
     * Export a Compra entity.
     *
     * @Route("/export/{dailydate}/{iniciodate}/{finaldate}", name="compra_export")
     * @Method({"GET", "POST"})
     */
    public function exportAction(Request $request,$dailydate=1,$iniciodate=1,$finaldate=1)
    {

        $em = $this->getDoctrine()->getManager();
        if ($dailydate !=1) {
            $compras = $em->getRepository('BackendBundle:Compra')->getArticulosCompra(array('fecha'=>$dailydate));
            $fe=$dailydate;
        }
        if ($iniciodate !=1 && $finaldate !=1) {
            $compras = $em->getRepository('BackendBundle:Compra')->getArticulosCompra(array('fechai'=>$iniciodate,'fechaf'=>$finaldate),'rango');
            $fe=$iniciodate.' al '.$finaldate;
        }
        $data=array();
        foreach($compras as $key=>$c){
            $data[]= array(
                $c->getArticulo()->getAbreviatura(),
                $c->getArticulo()->getNombre(),
                $c->getCompra()->getCantidaddearticulo(),
                $c->getImporte(),
                date_format($c->getCompra()->getFechacompra(),'d/m/Y'),
                $c->getCompra()->getNumerofactura(),
                $c->getCompra()->getProveedor()->getNombre(),
                $c->getCantidad()*$c->getPrecio() // $c->getCompra()->getMontototalpagado(),
            );
        }
        // Crea un nuevo objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
            ->setCreator('MonteroPlacas')
            ->setTitle('compras')
            ->setLastModifiedBy('Montero')
            ->setDescription('Registro de compras')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setKeywords('exportar compras')
            ->setCategory('exportar');
        // Agregar Informacion
        $objPHPExcel ->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel ->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $celda = $objPHPExcel->setActiveSheetIndex(0);
        $celda->setCellValue('A2','Empresa: MONTERO PLACAS SAC');
        $celda->setCellValue('A3','R.u.c.: 20543215385');
        $celda->mergeCells('A4:I4');
        $celda->setCellValue('A4', 'Registro de compras del '.$fe);
        $celda->setCellValue('A6', 'Código de artículo')
            ->setCellValue('B6', 'Nombre de artículo')
            ->setCellValue('C6', 'Cantidad')
            ->setCellValue('D6', 'Importe')
            ->setCellValue('E6', 'Fecha')
            ->setCellValue('F6', 'Referencia')
          //  ->setCellValue('F6', 'CODIT')
            ->setCellValue('G6', 'Proveedor')
            ->setCellValue('H6', 'Total');
        $celda->fromArray($data,'','A7');
          $cabecera = 'a6:h6';
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'font' => array('bold' => true,)
        );
        $celda->getStyle($cabecera)->applyFromArray($style2);;
        $objPHPExcel->setActiveSheetIndex(0);
        $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');
        
        //original
//        header('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
//        header('Content-Type: application/vnd.ms-excel;');
//        header("Content-type: application/x-msexcel");
//        header('Pragma', 'public');
//        header('Content-Disposition: attachment;filename="compras.xls"');
//        header('Cache-Control: max-age=0');
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->setPreCalculateFormulas(true);
//        $objWriter->save('php://output');
        
        //alternatives
            $filename = "compras.xls";
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
     * Finds and displays a Compra entity.
     *
     * @Route("/show/{id}", name="compra_show")
     * @Method("GET")
     */
    public function showAction(Compra $compra)
    {
        $articulocompras = $compra->getArticulocompras();
        foreach ($articulocompras as $articulocompra) {
            if($articulocompra->getImporte()==null || $articulocompra->getImporte()==0){
                $articulocompra->setImporte($articulocompra->getPrecio()*$articulocompra->getCantidad());
            }
        }
        
        
        $deleteForm = $this->createDeleteForm($compra);

        return $this->render('compra/show.html.twig', array(
            'compra' => $compra,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Compra entity.
     *
     * @Route("/{id}/edit", name="compra_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Compra $compra)
    {
        $articulocompras = $compra->getArticulocompras();
        foreach ($articulocompras as $articulocompra) {
            if($articulocompra->getImporte()==null || $articulocompra->getImporte()==0){
                $articulocompra->setImporte($articulocompra->getPrecio()*$articulocompra->getCantidad());
            }
        }
        
        $deleteForm = $this->createDeleteForm($compra);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\CompraType', $compra);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compra);
            $em->flush();

            $repository = $em->getRepository('BackendBundle:Compra');
          //  $repository->Updateasignacion($compra);

            return $this->redirectToRoute('compra_index');
        }

        return $this->render('compra/edit.html.twig', array(
            'compra' => $compra,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Compra entity.
     *
     * @Route("/{id}/delete", name="compra_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Compra $compra)
    {
           $em = $this->getDoctrine()->getManager();
            $em->remove($compra);
            $em->flush();

        return $this->redirectToRoute('compra_index');
    }

    /**
     * Creates a form to delete a Compra entity.
     *
     * @param Compra $compra The Compra entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Compra $compra)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compra_delete', array('id' => $compra->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

        
     /**
     * Search  Compras entities.
     *
     * @Route("/buscar/compras/{page}", name="buscar_compras")
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
            return $this->redirectToRoute('compra_index');
        } 

        $order_by = array();
        $comprasCount = $em->getRepository('BackendBundle:Compra')->GetByBuscarParamCount($nombres,$parametros);
        $results = 2; //paginado     
        $paginator = new Helper\Paginator($comprasCount, $page, $results);
        $compras = $em->getRepository('BackendBundle:Compra')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
                    
        return $this->render('compra/buscar.html.twig', array(
            'compras' => $compras, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,'title' =>'Resultados de la búsqueda',
            "dailydate" => '',"iniciodate" => '',"finaldate" => ''
        ));
    }

     /**
     * Search  Compras entities.
     *
     * @Route("/buscado/compras/{page}/{categoria}/{buscar}", name="buscado_compras")
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
            return $this->redirectToRoute('compra_index');
        }

        $order_by = array();
        $comprasCount = $em->getRepository('BackendBundle:Compra')->GetByBuscarParamCount($nombres,$parametros);
        $results = 2; //paginado     
        $paginator = new Helper\Paginator($comprasCount, $page, $results);
        $compras = $em->getRepository('BackendBundle:Compra')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
                    
        return $this->render('compra/buscar.html.twig', array(
             'compras' => $compras, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
                 "page" =>$page,
                    "categoria" => $categoria,"buscar" => $buscar,'title' =>'Resultados de la búsqueda',
            "dailydate" => '',"iniciodate" => '',"finaldate" => ''
        ));
    }
    
    
    /**
     * Search a Compra entity.
     *
     * @Route("/search/{page}", name="compra_search")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request, $page=1) {
        $em = $this->getDoctrine()->getManager();
        //order of items from database
        $order_by = array();

        $daily = $request->request->get('daily');
        if ($daily !=null) {
            $dailydate = $request->request->get('dailydate');
            $comprasCount = $em->getRepository('BackendBundle:Compra')->GetByFechaDiariaParamCount($dailydate);
            $results = 10; //paginado
            $paginator = new Helper\Paginator($comprasCount, $page, $results);
            $sort_direction = 'desc';
            $compras = $em->getRepository('BackendBundle:Compra')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit());
            $comprado = $em->getRepository('BackendBundle:Compra')->GetTotalCompradoByFecha($dailydate);
        }else {
            $dailydate=1;
        }
        $fechas= $request->request->get('fechas');
        if ($fechas !=null) {
            $iniciodate = $request->request->get('iniciodate');
            $finaldate = $request->request->get('finaldate');
            $comprasCount = $em->getRepository('BackendBundle:Compra')->GetByFechaRangoParamCount($iniciodate,$finaldate);
            $results = 10; //paginado
            $paginator = new Helper\Paginator($comprasCount, $page, $results);
            $sort_direction = 'desc';
            $compras = $em->getRepository('BackendBundle:Compra')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit());
            $comprado = $em->getRepository('BackendBundle:Compra')->GetTotalCompradoByRangoFecha($iniciodate,$finaldate);
        }  else {
            $iniciodate=1;
            $finaldate=1;
        }
        $pathern='compra_index';
        return $this->render('compra/search.html.twig', array(
            "pathern" =>$pathern,
            "compras" => $compras, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
            "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page,
            'title' =>'Informe de Compras - Comprado: '.$comprado.' artículos'
        ));
    }
    
    /**
     * Search a Compra entity.
     *
     * @Route("/searched/{page}/{dailydate}/{iniciodate}/{finaldate}", name="compra_searched")
     * @Method({"GET", "POST"})
     */
    public function searchedAction(Request $request, $page,  $dailydate=1,$iniciodate=1,$finaldate=1) {
        $em = $this->getDoctrine()->getManager();
        //order of items from database
        $order_by = array();

        if ($dailydate !=1) {
            $comprasCount = $em->getRepository('BackendBundle:Compra')->GetByFechaDiariaParamCount($dailydate);
            $results = 10; //paginado
            $paginator = new Helper\Paginator($comprasCount, $page, $results);
            $sort_direction = 'desc';
            $compras = $em->getRepository('BackendBundle:Compra')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit());
            $comprado = $em->getRepository('BackendBundle:Compra')->GetTotalCompradoByFecha($dailydate);
        }
        
        if ($iniciodate !=1 && $finaldate !=1) {
            $comprasCount = $em->getRepository('BackendBundle:Compra')->GetByFechaRangoParamCount($iniciodate,$finaldate);
            $results = 10; //paginado
            $paginator = new Helper\Paginator($comprasCount, $page, $results);
            $sort_direction = 'desc';
            $compras = $em->getRepository('BackendBundle:Compra')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit());
            $comprado = $em->getRepository('BackendBundle:Compra')->GetTotalCompradoByRangoFecha($iniciodate,$finaldate);
        } 
        
        return $this->render('compra/search.html.twig', array(
            "compras" => $compras, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
            "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page,
            'title' =>'Informe de Compras - Comprado: '.$comprado.' artículos'
        ));
    } 


}
