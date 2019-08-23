<?php

namespace Sistemadmin\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * DeudaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DeudaRepository extends EntityRepository
{
     
    public function GetByParam( $order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');       
        $qb->select('e,c')
                ->from('BackendBundle:Deuda', 'e')
        ->innerJoin('e.cliente','c');
        //Show all if offset and limit not set, also show all when limit is 0
        if ((isset($offset)) && (isset($limit))) {
            if ($limit > 0) {
                $qb->setFirstResult($offset);
                $qb->setMaxResults($limit);
            }           
        }
        //Adding defined sorting parameters from variable into query
        if($offset != 0){
        foreach ($order_by as $key => $value) {
            $qb->add('orderBy', 'l.' . $key . ' ' . $value);
        }}
        $query = $qb->getQuery();
        $deudas=$query->getArrayResult();
        foreach ($deudas as $key=>$deuda){
            $deudas[$key]['tipodocumento']=$this->getTipoDocumento($deuda);
        }
        return $deudas;
    }

    public function GetByParamCount() {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');       
        $qb->select('COUNT(e)')
                ->from('BackendBundle:Deuda', 'e');    
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
    
    public function GetBySerieAndDocumento( $serie, $numdocumento) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');       
        $qb->select('e')
                ->from('BackendBundle:Deuda', 'e')
                ->where('e.serie = :serie and e.numerofactura=:numdocumento')
        ->setParameter('serie', $serie)
                ->setParameter('numdocumento', $numdocumento );
          $query = $qb->getQuery();
        return $query->getResult();  
    }
   
     //elimina la deuda y reduce el monto que debe el cliente
     function DeleteDeuda($deuda) {
        $em = $this->getEntityManager();

        //si la deuda es activa ver si es la unica del cliente 
        $cliente = $deuda->getCliente();
        $query3 = $em->createQuery(
                        'SELECT COUNT(u.id)
                                    FROM BackendBundle:Cliente u
                                    WHERE u.id = :id AND u.deuda = true'
                )->setParameter('id', $cliente->getId());

        $numerodedeudasactivas = $query3->getSingleScalarResult();

        if ($numerodedeudasactivas == 1) {
            $cliente->setDeuda(0);
            $totaldeuda = $cliente->getMontodeuda() - $deuda->getTotalapagar();
            $cliente->setMontodeuda($totaldeuda);
            $em->persist($cliente);
            $em->flush();
        }else{
            $totaldeuda = $cliente->getMontodeuda() - $deuda->getTotalapagar();
            $cliente->setMontodeuda($totaldeuda);
            $em->persist($cliente);
            $em->flush();
        }

        $em->remove($deuda);
        $em->flush();
    }

       
    public function getDeudasActivasPorCliente($params) {
        $em = $this->getEntityManager();
       $dql = "SELECT d FROM BackendBundle:Deuda d where d.cliente=:client and d.fechainicio<=:ff and d.fechainicio>=:fi and d.tienedeuda = true";
        $consulta = $em->createQuery($dql)
            ->setParameter('fi', $params['fechai'] )
            ->setParameter('ff', $params['fechaf'] )
            ->setParameter('client', $params['cliente'] );
        return $consulta->getResult();

    }
    
    public function getDeudasInActivasPorCliente($params) {
        $em = $this->getEntityManager();
       $dql = "SELECT d FROM BackendBundle:Deuda d where d.cliente=:client and d.fechainicio<=:ff and d.fechainicio>=:fi and (d.tienedeuda != true or d.tienedeuda IS NULL)";
        $consulta = $em->createQuery($dql)
            ->setParameter('fi', $params['fechai'] )
            ->setParameter('ff', $params['fechaf'] )
            ->setParameter('client', $params['cliente'] );
        return $consulta->getResult();

    }
    
     //////////////////////////////////////////////search functions
    
    
    public function GetByFechaDiariaParam($fecha1, $order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');       
        $qb->select('e')
                ->from('BackendBundle:Deuda', 'e')
                ->where('e.fechainicio = :fecha1')
                 ->orderBy('e.fechainicio','desc')
        ->setParameter('fecha1', $fecha1 );
        
        
        //Show all if offset and limit not set, also show all when limit is 0
        if ((isset($offset)) && (isset($limit))) {
            if ($limit > 0) {
                $qb->setFirstResult($offset);
                $qb->setMaxResults($limit);
            }           
        }
        $query = $qb->getQuery();
        $deudas=$query->getArrayResult();
        foreach ($deudas as $key=>$deuda){
            $deudas[$key]['tipodocumento']=$this->getTipoDocumento($deuda);
        }
        return $deudas;
    }

    public function GetByFechaDiariaParamCount($fecha1) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');       
        $qb->select('COUNT(e)')
                ->from('BackendBundle:Deuda', 'e')
                ->where('e.fechainicio = :fecha1 ')
                 ->orderBy('e.fechainicio','desc')
        ->setParameter('fecha1',$fecha1);    
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
    
    public function GetByFechaRangoParam($fecha1, $fecha2,$order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');       
        $qb->select('e')
                ->from('BackendBundle:Deuda', 'e')
                ->where('e.fechainicio >= :fecha1 and e.fechainicio<=:fecha2 ')
        ->setParameter('fecha1', $fecha1)
                ->setParameter('fecha2', $fecha2 )
                 ->orderBy('e.fechainicio','desc');
        
        
        //Show all if offset and limit not set, also show all when limit is 0
        if ((isset($offset)) && (isset($limit))) {
            if ($limit > 0) {
                $qb->setFirstResult($offset);
                $qb->setMaxResults($limit);
            }           
        }
        $query = $qb->getQuery();

        $deudas=$query->getArrayResult();
        foreach ($deudas as $key=>$deuda){
            $deudas[$key]['tipodocumento']=$this->getTipoDocumento($deuda);
        }
        return $deudas;
    }

    public function GetByFechaRangoParamCount($fecha1,$fecha2) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');       
        $qb->select('COUNT(e)')
                ->from('BackendBundle:Deuda', 'e')
                ->where('e.fechainicio >= :fecha1 and e.fechainicio<=:fecha2 ')
        ->setParameter('fecha1', $fecha1 )
               ->setParameter('fecha2', $fecha2 )
                 ->orderBy('e.fechainicio','desc');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }  
    
          
    public function GetByBuscarParamCount($nombres,$parametros) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');      
        foreach ($parametros as $key => $par) {
            if ($parametros[$key] == 'true') {
                $parametros[$key] = 1;
            } elseif ($parametros[$key] == 'false') {
                $parametros[$key] = 0;
            }
        }
        $qb->select('COUNT(e)')
                ->from('BackendBundle:Deuda', 'e');
       $sent='';
        foreach ($parametros as $key=>$par){
            if ($key==0) {
                $sent = 'e.' . $nombres[$key] . ' LIKE :' . $nombres[$key] . '1';
            }
            else{
               $sent = $sent . ' and e.' . $nombres[$key] . ' = :' . $nombres[$key] . '1'; 
            }                   
        }
        $qb->where($sent); 
        
                       
        foreach ($parametros as $key=>$par){
            if ($key==0) {
                $qb->setParameter( $nombres[$key] . '1', '%' . $parametros[$key] . '%');
            }
            else{
                 $qb->setParameter($nombres[$key]. '1', $parametros[$key] );              
            }                   
        }
                        
//        print_r( $qb->getDQL());
//                die();
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
    
    public function GetByBuscarParam($nombres,$parametros, $order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        foreach ($parametros as $key => $par) {
            if ($parametros[$key] == 'true') {
                $parametros[$key] = 1;
            } elseif ($parametros[$key] == 'false') {
                $parametros[$key] = 0;
            }
        }
        
        $qb = $em->createQueryBuilder('l');       
         $qb->select('e')
                ->from('BackendBundle:Deuda', 'e');

        $sent='';
        foreach ($parametros as $key=>$par){
            if ($key==0) {
                $sent = 'e.' . $nombres[$key] . ' LIKE :' . $nombres[$key] . '1';
            }
            else{
               $sent = $sent . ' and e.' . $nombres[$key] . ' = :' . $nombres[$key] . '1'; 
            }                   
        }
        $qb->where( $sent); 
        
                       
        foreach ($parametros as $key=>$par){
            if ($key==0) {
                $qb->setParameter($nombres[$key] . '1', '%' . $parametros[$key] . '%');
            }
            else{
                 $qb->setParameter($nombres[$key]. '1', $parametros[$key] );              
            }                   
        }
        
//                print_r($qb->getDQL());
//                die();
        
        //Show all if offset and limit not set, also show all when limit is 0
        if ((isset($offset)) && (isset($limit))) {
            if ($limit > 0) {
                $qb->setFirstResult($offset);
                $qb->setMaxResults($limit);
            }           
        }
        //Adding defined sorting parameters from variable into query
        if($offset != 0){
        foreach ($order_by as $key => $value) {
            $qb->add('orderBy', 'l.' . $key . ' ' . $value);
        }}
        $query = $qb->getQuery();
        $deudas=$query->getArrayResult();
        foreach ($deudas as $key=>$deuda){
            $deudas[$key]['tipodocumento']=$this->getTipoDocumento($deuda);
        }
        return $deudas;
    }

        
    
    ////////////error fix functions
    
     public function GeneralDeudasFix() {
        $em = $this->getEntityManager();
        $dql = "SELECT v FROM BackendBundle:Deuda v"
                . " where v.tienedeuda = true";
        $consulta = $em->createQuery($dql);
        $deudas = $consulta->getResult();

        foreach ($deudas as $key => $deuda) {
            if ($deuda->getDeuda() < 1) {                
               $deuda->setTienedeuda(false); 
               $deuda->setFechacancelacion(new \DateTime());
               $deuda->setDeuda(0); 
               $cliente = $deuda->getCliente();
               $totaldeuda = $cliente->getMontodeuda() - $deuda->getDeuda();
               $cliente->setMontodeuda($totaldeuda);
               $em->persist($cliente);
               $em->persist($deuda);
               $em->flush();
            }
        }
        return true;
    }
    public function getTipoDocumento($deuda) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('d.tipodocumento')
            ->from('BackendBundle:Venta', 'v')
            ->innerJoin('v.documento','d')
            ->andWhere('v.numerodedocumento=:numero')
            ->andWhere('v.serie=:serie')
            ->setParameter('numero', $deuda['numerofactura'])
            ->setParameter('serie', $deuda['serie']);
        $query = $qb->getQuery();
        $tipo= $query->getArrayResult();
        return count($tipo)>0? $tipo[0]['tipodocumento']:'';
    }


}