<?php
/**
 * Created by PhpStorm.
 * User: sasuke
 * Date: 27/8/2018
 * Time: 5:37 AM
 */

namespace InventarioBundle\Services;

use Doctrine\ORM\EntityManager;
use PHPExcel;

class InventarioService
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function movimientosFecha($params = null)
    {

        $movs = $this->em->getRepository('InventarioBundle:Movimiento')->findMovimientosByFecha($params);
        $data = array();
        foreach ($movs as $key => $m) {
            $data[] = array(
                date_format($m->getfecha(), 'd/m/Y'),
                $m->getArticulo()->getNombre(),
                $m->getMotivo()->getNombre(),
                $m->getSeccion()->getAlmacen()->getNombre(),
                $m->getSeccion()->getNombre(),
                $m->getCantidad(),
            );
        }
        // Crea un nuevo objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
            ->setCreator('MonteroPlacas')
            ->setTitle('movimientos')
            ->setLastModifiedBy('Montero')
            ->setDescription('Movimientos diarios')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setKeywords('exportar movimientos')
            ->setCategory('exportar');
        // Agregar Informacion
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $celda = $objPHPExcel->setActiveSheetIndex(0);
        $celda->setCellValue('A2', 'Empresa: MONTERO PLACAS SAC');
        $celda->setCellValue('A3', 'R.u.c.: 20543215385');
        $celda->mergeCells('A4:I4');
        $celda->setCellValue('A4', 'Registro de Movimientos');
        $celda->setCellValue('A6', 'Fecha')
            ->setCellValue('B6', 'Artículo')
            ->setCellValue('C6', 'Motivo')
            ->setCellValue('D6', 'Almacén')
            ->setCellValue('E6', 'Sección')
            ->setCellValue('F6', 'Cantidad');
        $celda->fromArray($data, '', 'A7');
        $cabecera = 'a6:F6';
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'font' => array('bold' => true,)
        );
        $celda->getStyle($cabecera)->applyFromArray($style2);
        $objPHPExcel->setActiveSheetIndex(0);
        $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');
        $filename = "movimientos.xls";
        ob_end_clean();
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header("Pragma: no-cache");
        header("Expires: 0");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean();

        /*Desconecta el objeto PHPExcel para que no se quede en memoria*/
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
    }

    public function getInventarioGeneral($seccion = null,$fecha,$articulo)
    {
        $movs = $this->em->getRepository('InventarioBundle:Movimiento')->getInventarioArticulos($seccion,$fecha,false,false,$articulo);
        $data = array();
        foreach ($movs as $key => $m) {
            $totalentrada = $this->em->getRepository('InventarioBundle:Movimiento')->getInventarioPorTipo(1, $m['sid'], $m['aid']);
            $totalsalida = $this->em->getRepository('InventarioBundle:Movimiento')->getInventarioPorTipo(-1, $m['sid'], $m['aid']);
            $data[] = array(
                $m['anom'],
                $m['alnom'],
                $m['snom'],
                $m['existencia'],
                $totalentrada,
                $totalsalida,
            );
        }

        // Crea un nuevo objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
            ->setCreator('MonteroPlacas')
            ->setTitle('movimientos')
            ->setLastModifiedBy('Montero')
            ->setDescription('Movimientos diarios')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setKeywords('exportar movimientos')
            ->setCategory('exportar');
        // Agregar Informacion
        $fecha = is_null($fecha)? date('d/m/Y'):date_format(date_create_from_format('Y-m-d', $fecha), 'd/m/Y');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $celda = $objPHPExcel->setActiveSheetIndex(0);
        $celda->setCellValue('A2', 'Fecha ');
        $celda->setCellValue('B2', $fecha);
        $celda->setCellValue('A4', 'Inventario general')
            ->setCellValue('A6', 'Artículo')
            ->setCellValue('B6', 'Almacén')
            ->setCellValue('C6', 'Sección')
            ->setCellValue('D6', 'Cantidad')
            ->setCellValue('E6', 'Ingreso total')
            ->setCellValue('F6', 'Salida total');

        $celda->mergeCells('A4:I4');
        $celda->fromArray($data, '', 'A7');
        $cabecera = 'a6:F6';
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'font' => array('bold' => true,)
        );
        $celda->getStyle($cabecera)->applyFromArray($style2);
        $objPHPExcel->setActiveSheetIndex(0);
        $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');
        $filename = "inventario.xls";
        ob_end_clean();
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header("Pragma: no-cache");
        header("Expires: 0");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean();

        /*Desconecta el objeto PHPExcel para que no se quede en memoria*/
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
    }

    public function getInventarioDiario($seccion, $fecha,$articulo)
    {
        $movs = $this->em->getRepository('InventarioBundle:Movimiento')->getInventarioArticulos($seccion, $fecha,false,false,$articulo,false);
        $data = array();

        foreach ($movs as $key => $m) {
            $art = $this->em->getRepository('BackendBundle:Articulo')->find($m['aid']);

            $ingmat = $this->em->getRepository('InventarioBundle:Movimiento')->getInventarioPorTipo(1, $m['sid'], $m['aid'], $fecha);
            $usodia = $this->em->getRepository('InventarioBundle:Movimiento')->getInventarioPorTipo(-1, $m['sid'], $m['aid'], $fecha);
            $sant = $m['existencia'] + $usodia - $ingmat;
//            $data[] = array(
//                $art->getNombre(),
//                $sant == 0 ? '0' : $sant < 0 ? -$sant : $sant,
//                $ingmat == 0 ? '0' : $ingmat < 0 ? -$ingmat : $ingmat,
//                ($sant + $ingmat) < 0 ? -($sant + $ingmat) : ($sant + $ingmat),
//                $usodia == 0 ? '0' : $usodia < 0 ? -$usodia : $usodia,
//                $m['existencia'] < 0 ? -$m['existencia'] : $m['existencia'],
//            );
            $data[] = array(
                $art->getNombre(),
                $sant ,
                $ingmat,
                ($sant + $ingmat) ,
                $usodia ,
                $m['existencia'],
            );
        }
        $snom = $seccion != 0 ? $this->em->getRepository('InventarioBundle:Seccion')->find($seccion)->getNombre() : '';

        // Crea un nuevo objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
            ->setCreator('MonteroPlacas')
            ->setTitle('movimientos')
            ->setLastModifiedBy('Montero')
            ->setDescription('Movimientos diarios')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setKeywords('exportar movimientos')
            ->setCategory('exportar');
        // Agregar Informacion

        $fecha = is_null($fecha)? date('d/m/Y'):date_format(date_create_from_format('Y-m-d', $fecha), 'd/m/Y');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $celda = $objPHPExcel->setActiveSheetIndex(0);
        $celda->setCellValue('A1', 'Sección: ' . $snom);
        $celda->setCellValue('A2', 'Contrata: ');
        $celda->setCellValue('A3', 'Fecha: ' . $fecha);

        $celda->setCellValue('A4', 'Inventario diario')
            ->setCellValue('A6', 'Material')
            ->setCellValue('B6', 'Stock Anterior')
            ->setCellValue('C6', 'Ingeso material')
            ->setCellValue('D6', 'Total de material')
            ->setCellValue('E6', 'Uso del dia')
            ->setCellValue('F6', 'Stock del dia');


        $celda->mergeCells('A4:I4');
        $celda->fromArray($data, '', 'A7');
        $cabecera = 'a6:F6';
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'font' => array('bold' => true,)
        );
        $celda->getStyle($cabecera)->applyFromArray($style2);
        $objPHPExcel->setActiveSheetIndex(0);
        $celda->getStyle($cabecera)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('54ae86');
        $filename = "inventario.xls";
        ob_end_clean();
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header("Pragma: no-cache");
        header("Expires: 0");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean();

        /*Desconecta el objeto PHPExcel para que no se quede en memoria*/
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
    }
}