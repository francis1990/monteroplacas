<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TipoCambio
 *
 * @ORM\Table(name="tipo_cambio")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\TipoCambioRepository")
 * @UniqueEntity("fecha", message="Ya se ha configurado el tipo de cambio para esta fecha")
 */
class TipoCambio
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
     * @ORM\Column(name="compra", type="float")
     */
    private $compra;

    /**
     * @var float
     *
     * @ORM\Column(name="venta", type="float")
     */
    private $venta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", unique=true)
     */
    private $fecha;


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
     * Set compra
     *
     * @param float $compra
     * @return TipoCambio
     */
    public function setCompra($compra)
    {
        $this->compra = $compra;

        return $this;
    }

    /**
     * Get compra
     *
     * @return float 
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * Set venta
     *
     * @param float $venta
     * @return TipoCambio
     */
    public function setVenta($venta)
    {
        $this->venta = $venta;

        return $this;
    }

    /**
     * Get venta
     *
     * @return float 
     */
    public function getVenta()
    {
        return $this->venta;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TipoCambio
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
