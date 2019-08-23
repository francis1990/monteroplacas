<?php

namespace Sistemadmin\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sistemadmin\BackendBundle\Entity\Pago;
use Symfony\Component\HttpFoundation\Request;
use PHPExcel;
use PHPExcel_IOFactory;

class DefaultController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function mainAction()
    {
        return $this->render('BackendBundle:Default:main.html.twig');
    }
    
        /**
     * @Route("/errorcorrection", name="error_correction")
     */
    public function errorCorrAction()
    {
        return $this->render('BackendBundle:Default:errorfix.html.twig');
    }
    
    /**
     * @Route("/error/general/pagos", name="error_pagos_general")
     */
    public function errorGeneralPagosAction()
    {
        set_time_limit(120);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BackendBundle:Pago');
        $repository->GeneralPagosFix();

        return $this->render('BackendBundle:Default:errorfix.html.twig');
    }
    
    /**
     * @Route("/error/general/deudas", name="error_deudas_general")
     */
    public function errorGeneralDeudasAction()
    {
        set_time_limit(240);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BackendBundle:Deuda');
        $repository->GeneralDeudasFix();

        return $this->render('BackendBundle:Default:errorfix.html.twig');
    }    
    
    /**
     * @Route("/error/general/clientes", name="error_clientes_general")
     */
    public function errorGeneralClientesAction()
    {
        set_time_limit(120);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BackendBundle:Cliente');
        $repository->GeneralClientesFix();

        return $this->render('BackendBundle:Default:errorfix.html.twig');
    }
    /**

     * @Route("/excelgeneral", name="excel_general")
     * @Method({"GET", "POST"})
     */
    public function excelGeneralAction(Request $request)
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('iniciodate', 'date', array(
                'widget' => 'single_text',
                'label' => 'Fecha inicio:',
                'format' => 'MM/dd/yyyy',
                'attr' => array('class' => 'form-control  dp_modal '),
                'html5' => false,
            ))->add('finaldate', 'date', array(
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
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                ->setCreator('MonteroPlacas')
                ->setTitle('venta_general')
                ->setLastModifiedBy('Montero')
                ->setDescription('Ventas generales')
                ->setSubject('Office 2007 XLSX Test Document')
                ->setKeywords('exportar ventas generales')
                ->setCategory('exportar');
            $data=array();
            //---------------------------------
            self::rellenarCuerpo($objPHPExcel,$data,'FACTURAS COMPRAS');
            $objPHPExcel->getSheetCount();
            $objPHPExcel->createSheet();//creamos la pestaña
            self::rellenarCuerpo($objPHPExcel,$data,'FACTURAS DE VENTA',1);
            $objPHPExcel->createSheet();//creamos la pestaña
            self::rellenarCuerpo($objPHPExcel,$data,'BOLETAS DE VENTAS',2);
            $objPHPExcel->createSheet();//creamos la pestaña
            $objPHPExcel->setActiveSheetIndex(3);
            $celda = $objPHPExcel->setActiveSheetIndex(3);
            $objPHPExcel->getActiveSheet()->setTitle('TOTAL');
            $celda->mergeCells('F5:G6');
            $celda->mergeCells('h5:j6');
            $celda->setCellValue('F5','VENTA');
            $celda->mergeCells('F7:G8');
            $celda->mergeCells('h7:j8');
            $celda->setCellValue('F7','GASTOS');
            $celda->mergeCells('F9:G10');
            $celda->mergeCells('h9:j10');
            $celda->setCellValue('F9','FALTA');
            $celda->mergeCells('F11:G12');
            $celda->mergeCells('h11:j12');
            $celda->setCellValue('F11','A FAVOR');
            $cabecera = 'f4:j12';
            $style2 = array(
                'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
                'font' => array('bold' => true,)
            );
            $celda->getStyle($cabecera)->applyFromArray($style2);



            //alternatives
            $filename = "ventageneral.xls";
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
        return $this->render('BackendBundle:Default:general.html.twig', array(
            'form' => $form->createView(),
            'vendedor'=>0
        ));
    }

    function rellenarCuerpo($objPHPExcel,$datos,$name,$index=0){

        // Agregar Informacion
        $objPHPExcel->setActiveSheetIndex($index);
        $objPHPExcel->getActiveSheet()->setTitle($name);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('h')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $celda = $objPHPExcel->setActiveSheetIndex($index);
        $celda->setCellValue('D4','FECHA DE EMISION');
        $celda->setCellValue('E4','SERIE');
        $celda->setCellValue('F4','NUMERO');
        $celda->setCellValue('G4','APELLIDOS Y NOMBRES O RAZON SOCIAL');
        $celda->setCellValue('H4','IGV');
        $celda->setCellValue('I4','IMPORTE TOTAL');
        $celda->mergeCells('D4:D6');
        $celda->mergeCells('E4:E6');
        $celda->mergeCells('F4:F6');
        $celda->mergeCells('G4:G6');
        $celda->mergeCells('H4:H6');
        $celda->mergeCells('I4:I6');
        //$celda->fromArray($datos,'','d7');
        $cabecera = 'd4:I4';
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'font' => array('bold' => true,)
        );
        $celda->getStyle($cabecera)->applyFromArray($style2);
        $objPHPExcel->setActiveSheetIndex($index);
        $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');

    }
}
