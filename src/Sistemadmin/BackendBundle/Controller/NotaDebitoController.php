<?php

namespace Sistemadmin\BackendBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\NotaDebito;
use Sistemadmin\BackendBundle\Form\NotaDebitoType;
use Sistemadmin\BackendBundle\Helper;

use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use PHPExcel;
use PHPExcel_IOFactory;

/**
 * NotaDebito controller.
 *
 * @Route("/notadebito")
 */
class NotaDebitoController extends Controller
{

    /**
     * Creates a new NotaDebito entity.
     *
     * @Route("/new", name="notadebito_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {                        

        $em = $this->getDoctrine()->getManager();
        $ventas = $em->getRepository('BackendBundle:Venta')->findAll();
        $notadebito = new NotaDebito();        
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\NotaDebitoType', $notadebito);        
        //$form->get('venta_documento')->setData('Danyer');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() )
        {
            //Validar que sólo se puede disminuir la cantidad de productos
            $ultimanotadebito = $this->NotaDebitoMasReciente($notadebito->getVenta()->getId());
            if ($ultimanotadebito != null)
            {
                $i = 0;
                foreach ($notadebito->getVenta()->getArticuloventas() as $key => $articulo) 
                {
                    $cantidadAnterior = $articulo->getCantidad();
                    $cantidadActual =  $notadebito->getArticulonotadebitos()[$key]->getCantidad();
                    if ($cantidadActual < $cantidadAnterior) 
                    {
                        $error = new \Symfony\Component\Form\FormError('La cantidad de artículos no puede ser menor que la que había inicialmente');
                        $form->addError($error);
                        return $this->render('notadebito/new.html.twig', array(                            
                            'form' => $form->createView()
                        ));
                    }
                    $i++;
                }
            }
            else
            {
                //comparar con los articulos de la venta
                $i = 0;
                foreach ($notadebito->getVenta()->getArticuloventas() as $key => $articulo) 
                {
                    $cantidadAnterior = $articulo->getCantidad();
                    $cantidadActual =  $notadebito->getArticulonotadebitos()[$key]->getCantidad();
                    if ($cantidadActual < $cantidadAnterior) 
                    {
                        $error = new \Symfony\Component\Form\FormError('La cantidad de artículos no puede ser menor que la que había inicialmente');
                        $form->addError($error);
                        return $this->render('notadebito/new.html.twig', array(                            
                            'form' => $form->createView()
                        ));
                    }
                    $i++;
                }
            }

            $repository = $em->getRepository('BackendBundle:NotaDebito');
            $result = $repository->Create($notadebito);
            if ($result)
            {
                return $this->redirectToRoute('notadebito_index');
            }
            else
            {
                $error = new \Symfony\Component\Form\FormError('El número de esa nota de crédito ya existe!!(Incremente su valor)');
                $form->addError($error);
                return $this->render('notadebito/new.html.twig', array(                            
                    'form' => $form->createView()
                ));
            }
        }
        return $this->render('notadebito/new.html.twig', array(
            'ventas' => $ventas,
            "form" => $form->CreateView()));
    }

    /**
     * Finds and displays a Venta entity.
     *
     * @Route("/show/{id}", name="notadebito_show")
     * @Method("GET")
     */
    public function showAction(NotaDebito $notadebito)
    {
        $ventum = $notadebito->getVenta();
        $articulonotadebitos = $notadebito->getArticulonotadebitos();
        foreach ($articulonotadebitos as $articulonotadebito) {
            if($articulonotadebito->getImporte() == null || $articulonotadebito->getImporte() == 0){
                $articulonotadebito->setImporte($articulonotadebito->getPrecio() * $articulonotadebito->getCantidad());
            }
        }
        
        return $this->render('notadebito/show.html.twig', array(
            'ventum' => $ventum,
            'notadebito' => $notadebito,
        ));
    }

    
    /**
     * Lists all NotaDebito entities.
     *
     * @Route("/", name="notadebito_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();		
        $pathern='notadebito_index';
        $notadebitos = $em->getRepository('BackendBundle:NotaDebito')->findAll();

        return $this->render('notadebito/index.html.twig', array(
            'notadebitos' => $notadebitos, "pathern" =>$pathern
        ));		
    }

    /**
     * Search  Compras entities.
     *
     * @Route("/buscar/notadebito", name="notadebito_buscar")
     * @Method({"GET", "POST"})
     */
    public function buscarAction(Request $request)
    {

        return $this->render('notadebito/buscar.html.twig', array(
            "buscado" => '','title' =>'Resultados de la búsqueda'            
        ));
    }

    /**
     * Search  Compras entities.
     *
     * @Route("/ajax", name="notadebito_ajax")
     * @Method({"GET"})
     */
    public function ajaxAction(Request $request)
    {    
        if ($request->isXmlHttpRequest()) 
        {
            //Recuperando el id de la venta
            $ventaId = $request->query->get("ventaId");

            //Encontrar los articulos relacionados con la venta $ventaId
            
            $em = $this->getDoctrine()->getManager();
            //Buscar si hay ya una nota de credito asociada a esa venta para enviar los datos asociados a la nota de credito en 
            //lugar de los datos asociados a la venta. Si la hay, seleccinar la nota de credito más reciente
            $notas_de_credito = $em->getRepository('BackendBundle:NotaDebito')->findByVenta($ventaId);

            if (count($notas_de_credito) == 0)
            {
                $venta = $em->getRepository('BackendBundle:Venta')->findOneById($ventaId);
                $articulosVenta = $venta->getArticuloventas();
            }
            else
            {

                $notadebito = $this->NotaDebitoMasReciente($ventaId);

                $articulosVenta = $notadebito->getArticulonotadebitos(); 
            }
            $data = array();
            for ($i=0; $i < count($articulosVenta); $i++) 
            { 
                $data[$i] = array(  
                                    // 'seccion'   => $articulosVenta[$i]->getSeccion()->getNombre(), 
                                    'seccion'   => 1, 
                                    'articulo'  => $articulosVenta[$i]->getArticulo()->getNombre(),
                                    'precio'    => $articulosVenta[$i]->getPrecio(),  
                                    'cantidad'  => $articulosVenta[$i]->getCantidad(),
                                    'importe'   => $articulosVenta[$i]->getImporte()
                                );
            }


            $encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $response = new JsonResponse();
            $response->setStatusCode(200);
            $response->setData(array(                            
                    // 'datos' => $serializer->serialize($venta->getCantidaddearticulos(), 'json')
                    'datos' => $serializer->serialize($data, 'json')
            ));

            return $response;
        }
    }

    /**
     * Search  Compras entities.
     *
     * @Route("/prueba", name="prueba")
     * @Method({"GET"})
     */
    public function pruebaAction(Request $request)
    { 
            //Recuperando el id de la venta
            $ventaId = 2242;

            //Encontrar los articulos relacionados con la venta $ventaId
            
            $em = $this->getDoctrine()->getManager();        
            $venta = $em->getRepository('BackendBundle:Venta')->findOneById($ventaId);
            // $articulosVenta = $venta->getArticuloventas();
            // print_r($venta->getCantidaddearticulos());
            // print_r(count($articulosVenta));
            // $suma = 0;
            // for ($i=0; $i < count($articulosVenta); $i++) { 
            //     print_r($articulosVenta[$i]->getArticulo()->getNombre());
            //     $suma += $articulosVenta[$i]->getCantidad();
            // }
            // print_r($suma);
            // print_r('\n');
            // print_r($articulosVenta[0]->getSeccion());

            // $notadebito = $this->NotaDebitoMasReciente($ventaId);
            // $articulosVenta = $notadebito->getArticulonotadebitos(); 
            // print_r(count($articulosVenta));
            // for ($i=0; $i < count($articulosVenta); $i++) 
            // { 
            //     $data[$i] = array(  
            //                         // 'seccion'   => $articulosVenta[$i]->getSeccion()->getNombre(), 
            //                         'seccion'   => 1, 
            //                         'articulo'  => $articulosVenta[$i]->getArticulo()->getNombre(),
            //                         'precio'    => $articulosVenta[$i]->getPrecio(),  
            //                         'cantidad'  => $articulosVenta[$i]->getCantidad(),
            //                         'importe'   => $articulosVenta[$i]->getImporte()
            //                     );
            //     print_r($data[$i]);
            // }
            print_r($venta->getNumerodedocumento());
            $antiguaDeudaQuery = $em->createQuery(
            'SELECT u
            FROM BackendBundle:Deuda u
            WHERE u.numerofactura = :numerodocumento'  
        )->setParameter('numerodocumento', $venta->getNumerodedocumento());
        $antiguaDeuda = $antiguaDeudaQuery->getResult();
        print_r(count($antiguaDeuda));
        print_r($antiguaDeuda[0]->getDeuda());
            
            die();

            return $this->render('notadebito/buscar.html.twig', array(
            "buscado" => '','title' =>'Resultados de la búsqueda'            
        ));
    }

    function NotaDebitoMasReciente($ventaId)
    {
        //Buscar la nota de credito más reciente
        $em = $this->getDoctrine()->getManager();
        $notas_de_credito = $em->getRepository('BackendBundle:NotaDebito')->findByVenta($ventaId);
        if (count($notas_de_credito) > 0)
        {
            $mayor_id = $notas_de_credito[0]->getId();
            $index = 0;
            for ($i = 1; $i < count($notas_de_credito); $i ++) 
            { 
                $id_ = $notas_de_credito[$i]->getId();
                if ($id_ > $mayor_id)
                {
                    $mayor_id = $id_;
                    $index = $i;
                }
            }
            return $notas_de_credito[$index];
        }
        else
        {
            return null;
        }
    }

    /**
     * Search a Venta entity.
     *
     * @Route("/search", name="notadebito_search")
     * @Method({"GET", "POST"})
     */    
     public function searchAction(Request $request) 
     {
         $em = $this->getDoctrine()->getManager();
        //order of items from database
        $order_by = array();   
        $fechas= $request->request->get('fechas');
        if ($fechas != null) 
        {
            $iniciodate = $request->request->get('iniciodate');
            $finaldate = $request->request->get('finaldate');
            $notadebitosCount = $em->getRepository('BackendBundle:NotaDebito')->GetByFechaRangoParamCount($iniciodate, $finaldate);
            $sort_direction = 'desc';
            $notadebitos = $em->getRepository('BackendBundle:NotaDebito')->GetByFechaRangoParam($iniciodate, $finaldate, $order_by);   
        }  
        else 
        {
            $iniciodate = 1;
            $finaldate = 1;
        }   
        
     
        return $this->render('notadebito/search.html.twig', array(
                    "notadebitos" => $notadebitos,  
                    "iniciodate" => $iniciodate, "finaldate" => $finaldate));
    }
    
    /**
     * Export a NotaDebito entity.
     *
     * @Route("/export/pdf/{id}", name="notadebito_export_pdf")
     * @Method({"GET", "POST"})
     */
    public function exportPdfAction(Request $request, NotaDebito $notadebito)
    {        
        
        $articulonotadebitos = $notadebito->getArticulonotadebitos();
        foreach ($articulonotadebitos as $articuloventa) {
            if($articuloventa->getImporte() == null || $articuloventa->getImporte() == 0){
                $articuloventa->setImporte($articuloventa->getPrecio() * $articuloventa->getCantidad());
            }
        }
       
        $fecha=date_format($notadebito->getFecha(), 'd-m-Y');
        $arrformat=explode('-', $fecha);
		
		$html = $this->renderView(
            'notadebito/reportenotadebito.html.twig',
            array(
                'venta' => $notadebito->getVenta(),
                'notadebito' => $notadebito,
                'articulonotadebitos' => $notadebito->getArticulonotadebitos(),
                'dia'=>$arrformat[0],
                'mes'=>$arrformat[1],
                'anno'=>$arrformat[2],
            )
        );

        $this->returnPDFResponseFromHTML($html);

    }

    public function returnPDFResponseFromHTML($html)
    {
        //set_time_limit(30); uncomment this line according to your needs
        // If you are not in a controller, retrieve of some way the service container and then retrieve it
        //$pdf = $this->container->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //if you are in a controlller use :
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->SetAuthor('Montero Placas');
        $pdf->SetTitle(('NotaDebito Montero Placas'));
        $pdf->SetSubject('notadebito');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 9, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();
        $filename = 'notadebito_pdf';
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly
        
    }
}
