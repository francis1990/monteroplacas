<?php
/**
 * Created by PhpStorm.
 * User: sasuke
 * Date: 1/6/2018
 * Time: 4:45 PM
 */

namespace Sistemadmin\BackendBundle\Services;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sistemadmin\BackendBundle\Entity\Deuda;
use Sistemadmin\BackendBundle\Entity\Venta;

class EntityEvenListener
{

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }


    public function postUpdate(LifecycleEventArgs $args)
    {
       // $this->setTotalRecibidoVenta($args,'update');

    }

    public function postRemove(LifecycleEventArgs $args)
    {
        //$this->setTotalRecibidoVenta($args,'delete');
    }

    private function setTotalRecibidoVenta(LifecycleEventArgs $args,$accion){
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof Deuda ) {
            $ventas= $em->getRepository('BackendBundle:Venta')->findBy(array('serie'=>$entity->getSerie(),'numerodedocumento'=>$entity->getNumerofactura()));
            if(count($ventas)>0){
                $venta=$ventas[0];
                if($accion=='delete')
                    $venta->setTotalrecibido(0);
                if($accion=='update')
                    $venta->setTotalrecibido($entity->getTotalPagado());
                $em->persist($venta);
                $em->flush();
            }
        }
    }
}