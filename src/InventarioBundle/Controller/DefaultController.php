<?php

namespace InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Helper;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/inventariomain",name="inventario_main")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $movimientos = $em->getRepository('InventarioBundle:Movimiento')->getInventarioArticulos();
        return $this->render('InventarioBundle:Default:index.html.twig', array(
            'movimientos' => $movimientos,
        ));
    }

    /**
     * @Route("/exportar/invgeneral",name="inventario_general")
     * @Method({"GET", "POST"})
     */
    public function inventarioGeneralAction(Request $request)
    {
        $data=$request->query;
            $fecha= $data->get('min')!=''?date_format(\DateTime::createFromFormat('d/m/Y', $data->get('min')),'Y-m-d'):null;
            $art=$data->get('articulo')!=0? $data->get('articulo'):null;
            $sec=$data->get('seccion')!=0? $data->get('seccion'):null;
            return $this->get('inventario.services')->getInventarioGeneral($sec != 0 ? $sec : null,$fecha,$art!=0?$art:null);
    }

    /**
     * @Route("/exportar/invdiario/{sec}/{fecha}/{articulo}",name="inventario_diario")
     * @Method({"GET", "POST"})
     */
    public function inventarioDiarioAction($sec, $fecha,$articulo)
    {
        if ($fecha == 0)
        {
            $fecha = date('Y-m-d');
        }
        $sec=$sec != 0 ? $sec : null;
        $art=$articulo != 0 ? $articulo : null;
        return $this->get('inventario.services')->getInventarioDiario($sec, $fecha,$art);
    }

    /**
     * @Route("/invdiariomenu/{fecha}/{seccion}/{articulo}",name="inventario_diario_menu")
     * @Method({"GET", "POST"})
     */
    public function inventarioDiarioMenuAction($fecha,$seccion,$articulo)
    {

        $fecha =$fecha == 0 ? new \DateTime():  new \DateTime($fecha);
        $seccion = $seccion != 0 ? $seccion : null;
        $particulo = $articulo != 0 ? $articulo : null;

        $movimientos = $this->getDoctrine()->getRepository('InventarioBundle:Movimiento')->getInventarioDiarioMenu($seccion,date_format($fecha,'Y-m-d'),$particulo);
       
        return $this->render('@Inventario/inventario/inventario_diario.html.twig', array(
            'movimientos' => $movimientos,
            'fecha' => $fecha,
            'seccion' =>  $seccion != 0 ? $seccion : 0,
            'articulo'=>$articulo
        ));
    }

    /**
     * @Route("/invgeneralmenu/{fecha}/{seccion}/{articulo}",name="inventario_general_menu")
     * @Method({"GET", "POST"})
     */
    public function inventarioGeneralMenuAction($fecha = 0, $seccion = 0,$articulo=0)
    {
        $fecha =$fecha == 0 ? new \DateTime():  new \DateTime($fecha);
        $seccion = $seccion != 0 ? $seccion : null;
        $particulo = $articulo != 0 ? $articulo : null;
        $movimientos = $this->getDoctrine()->getRepository('InventarioBundle:Movimiento')->getInventarioGeneralMenu($seccion, date_format($fecha,'Y-m-d'),$particulo);

        return $this->render('@Inventario/inventario/inventario_general.html.twig', array(
            'movimientos' => $movimientos,
            'fecha' => $fecha,
            'seccion' =>  $seccion != 0 ? $seccion : 0,
            'articulo'=>$articulo
        ));
    }

    /**
     * @Route("/compinvdiariomenu/{idart}",name="compra_inventario_diario_menu")
     * @Method({"GET", "POST"})
     */
    public function inventarioDiarioCompraAction($idart)
    {
        $compras = $this->getDoctrine()->getRepository('InventarioBundle:Movimiento')->getCompraArt($idart);
        return $this->render('@Inventario/inventario/inventario_diario_compra_venta.html.twig', array(
            'compras' => $compras
        ));
    }

    /**
     * @Route("/ventinvdiariomenu/{idart}",name="venta_inventario_diario_menu")
     * @Method({"GET", "POST"})
     */
    public function inventarioDiarioVentaAction($idart)
    {
        $ventas = $this->getDoctrine()->getRepository('InventarioBundle:Movimiento')->getVentaArt($idart);
        return $this->render('@Inventario/inventario/inventario_diario_compra_venta.html.twig', array(
            'ventas' => $ventas
        ));
    }
}
