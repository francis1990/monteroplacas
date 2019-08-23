<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Caja
 *
 * @ORM\Table(name="caja")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\CajaRepository")
 */
class Caja
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechacuadre", type="date")
     */
    private $fechacuadre;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     * @return Caja
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fechacuadre
     *
     * @param \DateTime $fechacuadre
     * @return Caja
     */
    public function setFechacuadre($fechacuadre)
    {
        $this->fechacuadre = $fechacuadre;

        return $this;
    }

    /**
     * Get fechacuadre
     *
     * @return \DateTime 
     */
    public function getFechacuadre()
    {
        return $this->fechacuadre;
    }
}
