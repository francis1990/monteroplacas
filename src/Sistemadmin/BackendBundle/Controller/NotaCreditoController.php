<?php

namespace Sistemadmin\BackendBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\BackendBundle\Entity\NotaCredito;
use Sistemadmin\BackendBundle\Form\NotaCreditoType;
use Sistemadmin\BackendBundle\Helper;

use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use PHPExcel;
use PHPExcel_IOFactory;

/**
 * NotaCredito controller.
 *
 * @Route("/notacredito")
 */
class NotaCreditoController extends Controller
{

    /**
     * Creates a new NotaCredito entity.
     *
     * @Route("/new", name="notacredito_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {                        

        $em = $this->getDoctrine()->getManager();
        $ventas = $em->getRepository('BackendBundle:Venta')->findAll();
        $notacredito = new NotaCredito();        
        $form = $this->createForm('Sistemadmin\BackendBundle\Form\NotaCreditoType', $notacredito);        
        //$form->get('venta_documento')->setData('Danyer');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() )
        {
            //Validar que sólo se puede disminuir la cantidad de productos
            $ultimanotacredito = $this->NotaCreditoMasReciente($notacredito->getVenta()->getId());
            if ($ultimanotacredito != null)
            {
                $i = 0;
                foreach ($ultimanotacredito->getArticulonotacreditos() as $key => $articulo) 
                {
                    $cantidadAnterior = $articulo->getCantidad();
                    $cantidadActual = $notacredito->getArticulonotacreditos()[$i]->getCantidad();
                    if ($cantidadActual > $cantidadAnterior) 
                    {
                        $error = new \Symfony\Component\Form\FormError('La cantidad de artículos no puede ser mayor que la que había inicialmente');
                        $form->addError($error);
                        return $this->render('notacredito/new.html.twig', array(                            
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
                foreach ($notacredito->getArticulonotacreditos() as $key => $articulo) 
                {
                    $cantidadAnterior = $notacredito->getVenta()->getArticuloventas()[$i]->getCantidad();
                    $cantidadActual = $articulo->getCantidad();
                    if ($cantidadActual > $cantidadAnterior) 
                    {
                        $error = new \Symfony\Component\Form\FormError('La cantidad de artículos no puede ser mayor que la que había inicialmente');
                        $form->addError($error);
                        return $this->render('notacredito/new.html.twig', array(                            
                            'form' => $form->createView()
                        ));
                    }
                    $i++;
                }
            }

            $repository = $em->getRepository('BackendBundle:NotaCredito');
            $result = $repository->Create($notacredito);
            if ($result)
            {
                return $this->redirectToRoute('notacredito_index');
            }
            else
            {
                $error = new \Symfony\Component\Form\FormError('El número de esa nota de crédito ya existe!!(Incremente su valor)');
                $form->addError($error);
                return $this->render('notacredito/new.html.twig', array(                            
                    'form' => $form->createView()
                ));
            }
        }
        return $this->render('notacredito/new.html.twig', array(
            'ventas' => $ventas,
            "form" => $form->CreateView()));
    }

    /**
     * Finds and displays a Venta entity.
     *
     * @Route("/show/{id}", name="notacredito_show")
     * @Method("GET")
     */
    public function showAction(NotaCredito $notacredito)
    {
        $ventum = $notacredito->getVenta();
        $articulonotacreditos = $notacredito->getArticulonotacreditos();
        foreach ($articulonotacreditos as $articulonotacredito) {
            if($articulonotacredito->getImporte() == null || $articulonotacredito->getImporte() == 0){
                $articulonotacredito->setImporte($articulonotacredito->getPrecio() * $articulonotacredito->getCantidad());
            }
        }
        
     

        return $this->render('notacredito/show.html.twig', array(
            'ventum' => $ventum,
            'notacredito' => $notacredito,
        ));
    }

    /**
     * Displays a form to edit an existing NotaCredito entity.
     *
     * @Route("/{id}/edit", name="documento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, NotaCredito $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);
        $editForm = $this->createForm('Sistemadmin\BackendBundle\Form\NotaCreditoType', $documento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documento);
            $em->flush();

            return $this->redirectToRoute('documento_index');
        }

        return $this->render('documento/edit.html.twig', array(
            'documento' => $documento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NotaCredito entity.
     *
     * @Route("/{id}", name="documento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, NotaCredito $documento)
    {
        $form = $this->createDeleteForm($documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documento);
            $em->flush();
        }

        return $this->redirectToRoute('documento_index');
    }

    /**
     * Creates a form to delete a NotaCredito entity.
     *
     * @param NotaCredito $documento The NotaCredito entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(NotaCredito $documento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('documento_delete', array('id' => $documento->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
    /**
     * Lists all NotaCredito entities.
     *
     * @Route("/", name="notacredito_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();		
        $pathern='notacredito_index';
        $notascredito = $em->getRepository('BackendBundle:NotaCredito')->findAll();

        return $this->render('notacredito/index.html.twig', array(
            'notascredito' => $notascredito, "pathern" =>$pathern
        ));		
    }

    /**
     * Search  Compras entities.
     *
     * @Route("/buscar/notacredito", name="notacredito_buscar")
     * @Method({"GET", "POST"})
     */
    public function buscarAction(Request $request)
    {
//         $em = $this->getDoctrine()->getManager();
                        
//         $categoria= $request->request->get('categoria');  
//         $buscar= $request->request->get('buscar'); 
          
//         $nombres = array($categoria);
//         $parametros[0] = $buscar;
        
// //        print_r( $parametros);
// //        die();
        
//         if ($buscar == null ) {
//             return $this->redirectToRoute('venta_index');
//         } 

//         $order_by = array();
//         $ventasCount = $em->getRepository('BackendBundle:Venta')->GetByBuscarParamCount($nombres,$parametros);
//         //$results = 2; //paginado     
//         //$paginator = new Helper\Paginator($ventasCount, $page, $results);
//         //$ventas = $em->getRepository('BackendBundle:Venta')->GetByBuscarParam($nombres,$parametros, $order_by, $paginator->getOffset(), $paginator->getLimit());
//         $ventas = $em->getRepository('BackendBundle:Venta')->GetByBuscarParam($nombres, $parametros, $order_by, 0, $ventasCount);
//         //$sort_direction = 'desc';
                    
//         // return $this->render('venta/buscar.html.twig', array(
//         //     'ventas' => $ventas, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
//         //          "page" =>$page,
//         //             "categoria" => $categoria,"buscar" => $buscar,'title' =>'Resultados de la búsqueda',
//         //     "dailydate" => '',"iniciodate" => '',"finaldate" => ''
//         // ));

        return $this->render('notacredito/buscar.html.twig', array(
            "buscado" => '','title' =>'Resultados de la búsqueda'            
        ));
    }

    /**
     * Search  Compras entities.
     *
     * @Route("/ajax", name="notacredito_ajax")
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
            $notas_de_credito = $em->getRepository('BackendBundle:NotaCredito')->findByVenta($ventaId);

            if (count($notas_de_credito) == 0)
            {
                $venta = $em->getRepository('BackendBundle:Venta')->findOneById($ventaId);
                $articulosVenta = $venta->getArticuloventas();
            }
            else
            {
                //Buscar la nota de credito más reciente
                // $mayor_fecha = $notas_de_credito[0]->getFecha();
                // $index = 0;
                // for ($i = 1; $i < count($notas_de_credito); $i ++) 
                // { 
                //     $fecha = $notas_de_credito[$i]->getFecha();
                //     if ($fecha > $mayor_fecha)
                //     {
                //         $mayor_fecha = $fecha;
                //         $index = $i;
                //     }
                // }

                $notacredito = $this->NotaCreditoMasReciente($ventaId);

                $articulosVenta = $notacredito->getArticulonotacreditos(); 
            }
            
            // print_r($articulosVenta);
            // die();

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

            // $notacredito = $this->NotaCreditoMasReciente($ventaId);
            // $articulosVenta = $notacredito->getArticulonotacreditos(); 
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

            return $this->render('notacredito/buscar.html.twig', array(
            "buscado" => '','title' =>'Resultados de la búsqueda'            
        ));
    }

    function NotaCreditoMasReciente($ventaId)
    {
        //Buscar la nota de credito más reciente
        $em = $this->getDoctrine()->getManager();
        $notas_de_credito = $em->getRepository('BackendBundle:NotaCredito')->findByVenta($ventaId);
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
     * @Route("/search", name="notacredito_search")
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
            $notacreditosCount = $em->getRepository('BackendBundle:NotaCredito')->GetByFechaRangoParamCount($iniciodate, $finaldate);
            $sort_direction = 'desc';
            $notacreditos = $em->getRepository('BackendBundle:NotaCredito')->GetByFechaRangoParam($iniciodate, $finaldate, $order_by);   
        }  
        else 
        {
            $iniciodate = 1;
            $finaldate = 1;
        }   
        
     
        return $this->render('notacredito/search.html.twig', array(
                    "notacreditos" => $notacreditos,  
                    "iniciodate" => $iniciodate, "finaldate" => $finaldate));
    }
    
    /**
     * Export a NotaCredito entity.
     *
     * @Route("/export/pdf/{id}", name="notacredito_export_pdf")
     * @Method({"GET", "POST"})
     */
    public function exportPdfAction(Request $request, NotaCredito $notacredito)
    {        
        
        $articulonotacreditos = $notacredito->getArticulonotacreditos();
        foreach ($articulonotacreditos as $articuloventa) {
            if($articuloventa->getImporte() == null || $articuloventa->getImporte() == 0){
                $articuloventa->setImporte($articuloventa->getPrecio() * $articuloventa->getCantidad());
            }
        }
       
        $fecha=date_format($notacredito->getFecha(), 'd-m-Y');
        $arrformat=explode('-', $fecha);
		
		$html = $this->renderView(
            'notacredito/reportenotacredito.html.twig',
            array(
                'venta' => $notacredito->getVenta(),
                'notacredito' => $notacredito,
                'articulonotacreditos' => $notacredito->getArticulonotacreditos(),
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
        $pdf->SetTitle(('Venta Montero Placas'));
        $pdf->SetSubject('notacredito');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 9, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();
        $filename = 'notacredito_pdf';
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly
        
    } 
}
