<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\Vendedor;
use Sistemadmin\BackendBundle\Form\VendedorType;
use Symfony\Component\Form\FormError;
use PHPExcel;
use PHPExcel_IOFactory;

use Sistemadmin\BackendBundle\Helper;
/**
 * Vendedor controller.
 *
 * @Route("/vendedor")
 */
class VendedorController extends Controller
{
    
    /**
     * Export a Venta entity.
     *
     * @Route("/vendidoporvendedor/{id}/{iniciodate}/{finaldate}", name="vendedores_export")
     * @Method({"GET", "POST"})
     */
    public function porVendedorAction(Vendedor $vendedor,$iniciodate,$finaldate)
    {
        
            $em = $this->getDoctrine()->getManager();

            $ventas = $em->getRepository('BackendBundle:Vendedor')->getVentasPorVendedor(array(
                'vendedor'=> $vendedor->getId(),
                'fechai'=>$iniciodate,
                'fechaf'=>$finaldate
            ));
             
            $data = array();
            foreach ($ventas as $key => $v) {
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
            //$fei=date_format($iniciodate);
            //$fef=date_format($datosForm['fechafin'],'d/m/Y');
           $range='Venta de Productos por Vendedor del '.$iniciodate.' al '.$finaldate;
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
                ->setCellValue('J6', 'CODINT');
            $celda->fromArray($data, '', 'A7');
            $cabecera = 'a6:J6';
            $style2 = array(
                'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
                'font' => array('bold' => true,)
            );
            $celda->getStyle($cabecera)->applyFromArray($style2);;
            $objPHPExcel->setActiveSheetIndex(0);
            $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="ventasporvendedor.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            /*Desconecta el objeto PHPExcel para que no se quede en memoria*/
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
        

    }
    
    
     /**
     * Creates a new Vendedor entity.
     *
     * @Route("/new", name="vendedor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
        $vendedor = new Vendedor();
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\VendedorType', $vendedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository =$this->getDoctrine()->getRepository('BackendBundle:Vendedor');
            $result =$repository->Create($vendedor);

            if ($result){
                
            return $this->redirectToRoute('vendedor_show', array('id' => $vendedor->getId()));
            
            }
            else{
                $this->addFlash(
                    'danger',
                    'El vendedor ya existe!! (Nombre ya registrado)!'
                );
            }
            
            
        }

        return $this->render('vendedor/new.html.twig', array(
            'vendedor' => $vendedor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Vendedor entity.
     *
     * @Route("/show/{id}", name="vendedor_show")
     * @Method("GET")
     */
    public function showAction(Vendedor $vendedor)
    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
        $deleteForm = $this->createDeleteForm($vendedor);

        return $this->render('vendedor/show.html.twig', array(
            'vendedor' => $vendedor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Vendedor entity.
     *
     * @Route("/{id}/edit", name="vendedor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vendedor $vendedor)
    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
        $deleteForm = $this->createDeleteForm($vendedor);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\VendedorType', $vendedor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vendedor);
            $em->flush();

            return $this->redirectToRoute('vendedor_index');
        }

        return $this->render('vendedor/edit.html.twig', array(
            'vendedor' => $vendedor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Vendedor entity.
     *
     * @Route("/delete/{id}", name="vendedor_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, Vendedor $vendedor)
    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_VENTA')) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
         $em = $this->getDoctrine()->getManager();
             $repository =$this->getDoctrine()->getRepository('BackendBundle:Vendedor');
            $repository->Delete($vendedor);

        return $this->redirectToRoute('vendedor_index');          
        
    }

    /**
     * Creates a form to delete a Vendedor entity.
     *
     * @param Vendedor $vendedor The Vendedor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vendedor $vendedor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vendedor_delete', array('id' => $vendedor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }   
    
       /**
     * Search a Venta entity.
     *
     * @Route("/vendido/{page}", name="vendedor_vendido")
     * @Method({"GET", "POST"})
     */    
     public function vendidoAction(Request $request, $page=1) {
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
            $vendedorCount = $em->getRepository('BackendBundle:Vendedor')->GetByFechaDiariaParamCount($dailydate);           
            // $results = 2; //paginado     
            // $paginator = new Helper\Paginator($vendedorCount, $page, $results);
            // $sort_direction = 'desc';
            // $vendedoresArr = $em->getRepository('BackendBundle:Vendedor')->GetByFechaDiariaParam($dailydate, $order_by, $paginator->getOffset(), $paginator->getLimit());        
            $vendedoresArr = $em->getRepository('BackendBundle:Vendedor')->GetByFechaDiariaParam($dailydate, $order_by);
        }else {
            $dailydate=1;
        }     
        $fechas= $request->request->get('fechas');
        if ($fechas !=null) {
            $iniciodate = $request->request->get('iniciodate');
            $finaldate = $request->request->get('finaldate');
            $vendedorCount = $em->getRepository('BackendBundle:Vendedor')->GetByFechaRangoParamCount($iniciodate,$finaldate);           
            // $results = 2; //paginado
            // $paginator = new Helper\Paginator($vendedorCount, $page, $results);
            // $sort_direction = 'desc';
            // $vendedoresArr = $em->getRepository('BackendBundle:Vendedor')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by, $paginator->getOffset(), $paginator->getLimit());  
            $vendedoresArr = $em->getRepository('BackendBundle:Vendedor')->GetByFechaRangoParam($iniciodate,$finaldate, $order_by);  
        }  else {
            $iniciodate=1;
            $finaldate=1;
        }           
        
        $vendedores = $vendedoresArr['vendedores'];
        $vendido = $vendedoresArr['vendido'];
        
     
        // return $this->render('vendedor/vendido.html.twig', array(
        //             "vendedores" => $vendedores,  "vendido" => $vendido,'sort_dir' => $sort_direction, 'paginator' => $paginator,
        //             "dailydate" => $dailydate,"iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page)
        // );

        return $this->render('vendedor/vendido.html.twig', array(
                    "vendedores" => $vendedores,  "vendido" => $vendido, "dailydate" => $dailydate,
                    "iniciodate" => $iniciodate,"finaldate" => $finaldate, "page" =>$page)
        );
    }
    
    
    /**
     * Lists all Vendedor entities.
     *
     * @Route("/{page}", name="vendedor_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
         $em = $this->getDoctrine()->getManager();
        
        $order_by = array();
        $vendedorsCount = $em->getRepository('BackendBundle:Vendedor')->GetByParamCount();
        // $results = 10; //paginado     
        // $paginator = new Helper\Paginator($vendedorsCount, $page, $results);
        // $vendedors = $em->getRepository('BackendBundle:Vendedor')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $vendedors = $em->getRepository('BackendBundle:Vendedor')->GetByParam( $order_by );
        // $sort_direction = 'desc';
         $pathern='vendedor_index';    

        // return $this->render('vendedor/index.html.twig', array(
        //     'vendedors' => $vendedors, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
        //          "page" =>$page,"pathern" =>$pathern
        // ));        

        return $this->render('vendedor/index.html.twig', array(
            'vendedors' => $vendedors, "page" =>$page,"pathern" =>$pathern
        ));        
    }


}
