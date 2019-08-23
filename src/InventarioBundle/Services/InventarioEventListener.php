<?php

namespace InventarioBundle\Services;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use InventarioBundle\Entity\Movimiento;
use InventarioBundle\Entity\Transferencia;
use Sistemadmin\BackendBundle\Entity\ArticuloVenta;
use Sistemadmin\BackendBundle\Entity\Venta;
use Sistemadmin\BackendBundle\Entity\ArticuloCompra;
use Sistemadmin\BackendBundle\Entity\Compra;


class InventarioEventListener
{

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $this->movimiento($args, 'nuevo');
    }


    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->movimiento($args, 'editar');
    }


    public function movimiento(LifecycleEventArgs $args, $accion)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if (($entity instanceof ArticuloVenta) || ($entity instanceof ArticuloCompra) || ($entity instanceof Transferencia)) {
            $mov = new Movimiento();
            if ($entity instanceof Transferencia) {
                $move = new Movimiento();
                if ($accion != 'nuevo') {
                    $moves = $em->getRepository("InventarioBundle:Movimiento")->findByTransferencia($entity->getId());
                    if (count($moves) == 2) {
                        $mov = $moves[0];
                        $move = $moves[1];
                    }
                }
                $move = $em->getRepository("InventarioBundle:Movimiento")->movTransferencia($move, $entity, 4, $em);
                $mov = $em->getRepository("InventarioBundle:Movimiento")->movTransferencia($mov, $entity, 3, $em);
                $em->persist($mov);
                $em->persist($move);
            } else {
                if ($entity instanceof ArticuloCompra) {
                    $mov = $em->getRepository("InventarioBundle:Movimiento")->movCompra($mov, $entity, $em, $accion);
                    if (!is_null($mov)) {
                        $em->persist($mov);
                    }
                } elseif ($entity instanceof ArticuloVenta) {

                    $mov = $em->getRepository("InventarioBundle:Movimiento")->movVenta($mov, $entity, $em, $accion);
                    if (!is_null($mov)) {
                        if ($entity->getVenta()->getAnulada())
                            $em->remove($mov);
                        else
                            $em->persist($mov);
                    }
                }
            }
            $em->flush();
        }
        return;
    }

}