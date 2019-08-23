<?php

namespace Sistemadmin\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * CompraRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompraRepository extends EntityRepository
{
    public function GetByParamCount() {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('COUNT(e)')
            ->from('BackendBundle:Compra', 'e');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
    public function GetByParam( $order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');
        $qb->select('e')
            ->from('BackendBundle:Compra', 'e');


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
        return $query->getResult();
    }

    public function getComprasPorProveedor($params) {
        $em = $this->getEntityManager();
        $dql = "SELECT ac,c,a FROM BackendBundle:ArticuloCompra ac
          JOIN ac.compra c
          JOIN ac.articulo a
          where  c.proveedor=:prov and c.fechacompra<=:ff and c.fechacompra>=:fi";
        $consulta = $em->createQuery($dql)
            ->setParameter('fi', $params['fechai'] )
            ->setParameter('ff', $params['fechaf'] )
            ->setParameter('prov', $params['proveedor'] );
        return $consulta->getResult();

    }

    public function getArticulosCompra($params,$tipo='dia') {
        if($tipo=='rango'){
            $fecha='c.fechacompra<=:ff and c.fechacompra>=:fi';
            $param=array('fi'=>$params['fechai'],'ff'=>$params['fechaf']);
        }
        else
        {
            $fecha='c.fechacompra=:fecha';
            $param=array('fecha'=>$params['fecha']);
        }
        $em = $this->getEntityManager();
        $dql = "SELECT ac,c FROM BackendBundle:ArticuloCompra ac
          JOIN ac.compra c
          JOIN ac.articulo a
          where  ".$fecha;
        $consulta = $em->createQuery($dql)
         ->setParameters($param);
        return $consulta->getResult();

    }

    public function GetByFechaDiariaParamCount($fecha1) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('COUNT(e)')
            ->from('BackendBundle:Compra', 'e')
            ->where('e.fechacompra = :fecha1')
            ->orderBy('e.fechacompra','desc')
            ->setParameter('fecha1',$fecha1);
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }

    public function GetByFechaDiariaParam($fecha1, $order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('e')
            ->from('BackendBundle:Compra', 'e')
            ->where('e.fechacompra = :fecha1')
            ->orderBy('e.fechacompra','desc')
            ->setParameter('fecha1', $fecha1 );


        //Show all if offset and limit not set, also show all when limit is 0
        if ((isset($offset)) && (isset($limit))) {
            if ($limit > 0) {
                $qb->setFirstResult($offset);
                $qb->setMaxResults($limit);
            }
        }
        //Adding defined sorting parameters from variable into query
//        if($offset != 0){
//        foreach ($order_by as $key => $value) {
//            $qb->add('orderBy', 'l.' . $key . ' ' . $value);
//        }}
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function GetTotalCompradoByFecha($fecha1) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('sum(e.cantidaddearticulo) as comprado')
            ->from('BackendBundle:Compra', 'e')
            ->where('e.fechacompra = :fecha1 ')
            ->setParameter('fecha1', $fecha1 );
        $query= $qb->getQuery();
        $comprado= $query->getSingleScalarResult();
        return $comprado==null?0:$comprado;
    }

    public function GetByFechaRangoParamCount($fecha1,$fecha2) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('COUNT(e)')
            ->from('BackendBundle:Compra', 'e')
            ->where('e.fechacompra >= :fecha1 and e.fechacompra<=:fecha2')
            ->setParameter('fecha1', $fecha1 )
            ->setParameter('fecha2', $fecha2 )
            ->orderBy('e.fechacompra','desc');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }

    public function GetByFechaRangoParam($fecha1, $fecha2,$order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');
        $qb->select('e')
            ->from('BackendBundle:Compra', 'e')
            ->where('e.fechacompra >= :fecha1 and e.fechacompra<=:fecha2 ')
            ->setParameter('fecha1', $fecha1)
            ->setParameter('fecha2', $fecha2 )
            ->orderBy('e.fechacompra','desc');


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

    public function GetTotalCompradoByRangoFecha($fecha1,$fecha2) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('sum(e.cantidaddearticulo) as comprado')
            ->from('BackendBundle:Compra', 'e')
            ->where('e.fechacompra >= :fecha1 and e.fechacompra<=:fecha2 ')
            ->setParameter('fecha1', $fecha1)
            ->setParameter('fecha2', $fecha2 );
        $query= $qb->getQuery();
        $comprado= $query->getSingleScalarResult();
        return $comprado==null?0:$comprado;
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
                ->from('BackendBundle:Compra', 'e');
//                 ->where('e.nombre LIKE :name1 and e.deuda = :deuda1')
//                ->setParameter('name1', '%' . $parametros[0] . '%')
//                ->setParameter('deuda1', $parametros[1]);

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
                ->from('BackendBundle:Compra', 'e');
//                 ->where('e.nombre LIKE :name1 and e.deuda = :deuda1')
//                ->setParameter('name1', '%' . $parametros[0] . '%')
//                ->setParameter('deuda1', $parametros[1]);
                
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
        return $query->getResult();
    }
}
