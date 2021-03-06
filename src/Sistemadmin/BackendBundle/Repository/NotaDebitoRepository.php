<?php

namespace Sistemadmin\BackendBundle\Repository;

use Sistemadmin\BackendBundle\Entity\Pago;
use Sistemadmin\BackendBundle\Entity\Deuda;
use Doctrine\ORM\EntityRepository;

/**
 * NotaDebitoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotaDebitoRepository extends EntityRepository
{
	public function Create($notadebito) 
	{
		//Calcular la cantidad de articulos
		$cantArticulos = 0;
	    $total_a_Pagar = 0;
            $borrar=array();
	    foreach ($notadebito->getArticulonotadebitos() as $key => $articulonotadebito) 
	    {
	    	$cantArticulos ++;
	    	$articulonotadebito->setNotadebito($notadebito);
	        $total_a_Pagar += $articulonotadebito->getPrecio() * $articulonotadebito->getCantidad();
                
                if ($articulonotadebito->getCantidad()==0){
                    $borrar[]=$articulonotadebito;
                }
	    }
            foreach ($borrar as $elim){
                $notadebito->removeArticulonotadebito($elim);
            }
            

	    $notadebito->setCantidaddearticulos($cantArticulos);
	    $notadebito->setMontototalapagar($total_a_Pagar);
	    		

            
	    $em = $this->getEntityManager();	
	    $em->persist($notadebito);
	    $em->flush();

		$tipo = $notadebito->getTipo();
		if ($tipo == "Corrección por error en la descripción" || $tipo == "Anulación por error en el RUC")
		{
	        $antiguaDeuda = $this->RemoveVentaRelatedEntities($notadebito->getVenta());
	        $this->CreateVentaRelated($notadebito->getVenta(), $notadebito);		    
		}
		return true;
	}

	public function RemoveVentaRelatedEntities($venta)
	{
		//verificar si existe alguna deuda asociada a esa venta     
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT u
            FROM BackendBundle:Deuda u
            WHERE u.numerofactura = :numerodocumento'  
        )->setParameter('numerodocumento', $venta->getNumerodedocumento());        
        $deuda = $query->getResult();
        if (count($deuda) > 0) 
        {   
           if ($deuda[0]->getTienedeuda() == 1)
           {
               //si la deuda es activa ver si es la unica del cliente 
               $cliente=$deuda[0]->getCliente();               
               $query3 = $em->createQuery(
                                'SELECT COUNT(u.id)
                FROM BackendBundle:Cliente u
                WHERE u.id = :id AND u.deuda = true'
                        )->setParameter('id', $cliente->getId());

                $numerodedeudasactivas = $query3->getSingleScalarResult();

                if ($numerodedeudasactivas == 1) 
                {
                   $cliente->setDeuda(false);
                   $cliente->setMontodeuda(0);
                   $em->persist($cliente);
                   $em->flush();
                }
            }
            $em->remove($deuda[0]);
            $em->flush();
        }

		//verificar si existe algun pago asociado a esa venta            
        $query2 = $em->createQuery(
            'SELECT u
            FROM BackendBundle:Pago u
            WHERE u.numerofactura = :numerodocumento'  
        )->setParameter('numerodocumento', $venta->getNumerodedocumento());        
        $pago = $query2->getResult();
        
        if (count($pago) > 0) 
        { 
            foreach ($pago as $pag) 
            {
             	$em->remove($pag);
            	$em->flush();
        	}
        }
	} 

	public function CreateVentaRelated($venta, $notadebito) 
	{
        if ($venta->getTotalrecibido() != 0) {
            if ($notadebito->getMontototalapagar() - $venta->getTotalrecibido() > 1) {
                $deuda = $this->CreateDeuda($venta, $notadebito);
            }
            $this->CreatePagoInicial($venta, $notadebito);
        }
        $em = $this->getEntityManager();
        //Crear la deuda asociada a la venta
    }

	function CreateDeuda($venta, $notadebito)
	{
        $em = $this->getEntityManager();
            
        //La deuda cuando se crea es con lo que tenia antes menos las disminución que hay en la cantidad a pagar luego de la nota de crédito
        $diferencia = $notadebito->getMontototalapagar() - $venta->getTotalrecibido();
        // $antiguaDeuda = $em->getRepository('BackendBundle:Deuda')->findByNumerofactura($venta->getNumerofactura())->getDeuda();        
        $em = $this->getEntityManager();

        $deuda = new Deuda();
        $deuda->setCliente($venta->getCliente());
        $deuda->setDeuda($diferencia);
        $deuda->setFechainicio($venta->getFecha());
        $deuda->setSerie($venta->getSerie());
        $deuda->setNumerofactura($venta->getNumerodedocumento());
        $deuda->setTienedeuda(true);
        $deuda->setTotalapagar($notadebito->getMontototalapagar());
        $em->persist($deuda);
        $em->flush();

        //Añadir deuda a cliente                
        $cliente = $venta->getCliente();
        $cliente->setDeuda(true);
        // $totaldeuda = $cliente->getMontodeuda() - $antiguaDeuda[0]->getDeuda() + $diferencia;
        $totaldeuda = 0;
        //Buscar todas las deudas que tiene el cliente        
        $deudasCliente = $em->getRepository('BackendBundle:Deuda')->findByCliente($venta->getCliente());
        foreach ($deudasCliente as $key => $deudaCliente) 
        {
            $totaldeuda += $deudaCliente->getDeuda();           
        }
//        $pagosCliente = $em->getRepository('BackendBundle:Pago')->findByCliente($venta->getCliente());
//        foreach ($pagosCliente as $key => $pagoCliente) 
//        {
//            $totaldeuda -= $pagoCliente->getMontopagado();           
//        }
        $cliente->setMontodeuda($totaldeuda);
        $em->persist($cliente);
        $em->flush();
        
        return $deuda;
    }

    function CreatePagoInicial($venta, $notadebito)
    {
        $em = $this->getEntityManager();
        $pago=new Pago();
        $pago->setSerie($venta->getSerie());
        $pago->setNumerofactura($venta->getNumerodedocumento());
        $pago->setTotalapagar($notadebito->getMontototalapagar());
        $pago->setCliente($venta->getCliente());
        $pago->setMontopagado($venta->getTotalrecibido());
        $pago->setFechapago($venta->getFecha());
        
        if ($venta->getTotalrecibido() < $notadebito->getMontototalapagar()) 
        {
                $pago->setTienedeuda(true);
        }
        else
        {
            $pago->setTienedeuda(false);
        }
        
        $em->persist($pago);
        $em->flush();
    }

    public function DeleteVenta($venta) 
    {
    	//verificar si existe alguna deuda asociada a esa venta     
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT u
            FROM BackendBundle:Deuda u
            WHERE u.numerofactura = :numerodocumento'  
        )->setParameter('numerodocumento', $venta->getNumerodedocumento());        
        $deuda = $query->getResult();
        if (count($deuda) > 0) {   
           if ($deuda[0]->getTienedeuda() == 1){
               //si la deuda es activa ver si es la unica del cliente 
               $cliente=$deuda[0]->getCliente();               
               $query3 = $em->createQuery(
                                'SELECT u
                FROM BackendBundle:Deuda u
                WHERE u.cliente = :id AND u.tienedeuda = true'
                        )->setParameter('id', $cliente->getId());

                $deudasActivasCliente = $query3->getResult();
                
                if (count($deudasActivasCliente) == 1) 
                {
                	$cliente->setDeuda(false);                   
	                $cliente->setMontodeuda(0);
	                $em->persist($cliente);
	                $em->flush();
                }
                else
                {
                    //Buscar todas las deudas que tiene el cliente        
                    $totaldeuda = 0;
                    foreach ($deudasActivasCliente as $key => $deudaCliente) 
                    {
                        if($deudaCliente->getId() != $deuda[0]->getId()){
                            $totaldeuda += $deudaCliente->getDeuda();
                        }
                    }
                    $cliente->setMontodeuda($totaldeuda);                    
                    $em->persist($cliente);
                    $em->flush();
                }
            }
            $em->remove($deuda[0]);
            $em->flush();
        }
		//verificar si existe algun pago asociado a esa venta            
        $query2 = $em->createQuery(
            'SELECT u
            FROM BackendBundle:Pago u
            WHERE u.numerofactura = :numerodocumento'  
        )->setParameter('numerodocumento', $venta->getNumerodedocumento());        
        $pago = $query2->getResult();
        
        if (count($pago) > 0) { 
            foreach ($pago as $pag) {
             $em->remove($pag);
            $em->flush();
        }
        }
        //una vez eliminado todo lo asociado a esa venta la puedo eliminar   
        // en lugar de eliminar la pongo como anulada
        $venta->setAnulada(true);
        $em->persist($venta);
        $em->flush();
        return true;
    }


    public function GetByFechaRangoParamCount($fecha1, $fecha2) 
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');       
        $qb->select('COUNT(e)')
                ->from('BackendBundle:NotaDebito', 'e')
                ->where('e.fecha >= :fecha1 and e.fecha<=:fecha2')
        ->setParameter('fecha1', $fecha1 )
               ->setParameter('fecha2', $fecha2 )
                 ->orderBy('e.fecha','desc');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }

    public function GetByFechaRangoParam($fecha1, $fecha2, $order_by = 0, $offset = 0, $limit = 0) 
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');       
        $qb->select('e')
                ->from('BackendBundle:NotaDebito', 'e')
                ->where('e.fecha >= :fecha1 and e.fecha<=:fecha2')
        ->setParameter('fecha1', $fecha1)
                ->setParameter('fecha2', $fecha2 )
                 ->orderBy('e.fecha','desc');
        
        
        //Show all if offset and limit not set, also show all when limit is 0
        if ((isset($offset)) && (isset($limit))) {
            if ($limit > 0) {
                $qb->setFirstResult($offset);
                $qb->setMaxResults($limit);
            }           
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
