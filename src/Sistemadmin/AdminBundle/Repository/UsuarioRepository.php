<?php

namespace  Sistemadmin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends EntityRepository
{

      public function GetByParam( $order_by = 0, $offset = 0, $limit = 0) {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder('l');       
        $qb->select('e')
                ->from('sistAdminBundle:Usuario', 'e');
        
        
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
                ->from('sistAdminBundle:Usuario', 'e');
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
   
    

}