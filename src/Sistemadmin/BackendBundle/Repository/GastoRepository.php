<?php

namespace Sistemadmin\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * GastoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GastoRepository extends EntityRepository
{
    public function GetByParam( $order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');
        $qb->select('e')
            ->from('BackendBundle:Gasto', 'e');


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
    public function GetByParamCount() {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('l');
        $qb->select('COUNT(e)')
            ->from('BackendBundle:Gasto', 'e');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
    
     public function getGastosPorFecha($params) {
        $em = $this->getEntityManager();
       $dql = "SELECT v FROM BackendBundle:Gasto v
          where v.fecha<=:ff and v.fecha>=:fi";
        $consulta = $em->createQuery($dql)
            ->setParameter('fi', $params['fechai'] )
            ->setParameter('ff', $params['fechaf'] );
        return $consulta->getResult();
    }


}
