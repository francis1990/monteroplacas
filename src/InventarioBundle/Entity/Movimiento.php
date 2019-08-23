<?php

namespace InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sistemadmin\BackendBundle\Entity\Articulo;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Movimiento
 *
 * @ORM\Table(name="movimientoInv")
 * @ORM\Entity(repositoryClass="InventarioBundle\Repository\MovimientoRepository")
 */
class Movimiento
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
     * @ORM\Column(name="cantidad", type="float", precision=10, scale=2)
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message ="Debe introducir un número mayor o igual a cero")
     * @Assert\Type(
     * type="float",
     * message="El valor {{ value }} no es valido."
     * )
     */

    private $cantidad;


    /**
     * @var Seccion
     * @ORM\ManyToOne(targetEntity="Seccion")
     * @ORM\JoinColumn(name="seccion", referencedColumnName="id")
     */
    private $seccion;

    /**
     * @var Motivo
     *
     * @ORM\ManyToOne(targetEntity="Motivo")
     * @ORM\JoinColumn(name="motivo", referencedColumnName="id")
     */
    private $motivo;

    /**
     * @var Articulo
     *
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\Articulo")
     * @ORM\JoinColumn(name="articulo", referencedColumnName="id")
     **/

    private $articulo;

    /**
     * @var Date
     *
     * @ORM\Column(name="fecha", type="date")
     * @Assert\Date(message="La fecha no es una fecha válida.")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\ArticuloVenta")
     * @ORM\JoinColumn(name="artventa", referencedColumnName="id",onDelete="cascade")
     */
    private $venta;

    /**
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\ArticuloCompra")
     * @ORM\JoinColumn(name="artcompra", referencedColumnName="id",onDelete="cascade")
     */
    private $compra;

    /**
     * @ORM\ManyToOne(targetEntity="Transferencia",inversedBy="movimientos")
     * @ORM\JoinColumn(name="transferencia", referencedColumnName="id",onDelete="cascade")
     */
    private $transferencia;


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
     * @return Movimiento
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
     * Set seccion
     *
     * @param \InventarioBundle\Entity\Seccion $seccion
     * @return Movimiento
     */
    public function setSeccion(\InventarioBundle\Entity\Seccion $seccion = null)
    {
        $this->seccion = $seccion;

        return $this;
    }

    /**
     * Get seccion
     *
     * @return \InventarioBundle\Entity\Seccion 
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * Set motivo
     *
     * @param \InventarioBundle\Entity\Motivo $motivo
     * @return Movimiento
     */
    public function setMotivo(\InventarioBundle\Entity\Motivo $motivo = null)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return \InventarioBundle\Entity\Motivo 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Movimiento
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

    /**
     * Set articulo
     *
     * @param \Sistemadmin\BackendBundle\Entity\Articulo $articulo
     * @return Movimiento
     */
    public function setArticulo(\Sistemadmin\BackendBundle\Entity\Articulo $articulo = null)
    {
        $this->articulo = $articulo;

        return $this;
    }

    /**
     * Get articulo
     *
     * @return \Sistemadmin\BackendBundle\Entity\Articulo 
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * Set venta
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloVenta $venta
     * @return Movimiento
     */
    public function setVenta(\Sistemadmin\BackendBundle\Entity\ArticuloVenta $venta = null)
    {
        $this->venta = $venta;

        return $this;
    }

    /**
     * Get venta
     *
     * @return \Sistemadmin\BackendBundle\Entity\ArticuloVenta 
     */
    public function getVenta()
    {
        return $this->venta;
    }


    /**
     * Set compra
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloCompra $compra
     * @return Movimiento
     */
    public function setCompra(\Sistemadmin\BackendBundle\Entity\ArticuloCompra $compra = null)
    {
        $this->compra = $compra;

        return $this;
    }

    /**
     * Get compra
     *
     * @return \Sistemadmin\BackendBundle\Entity\ArticuloCompra 
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * Set transferencia
     *
     * @param \InventarioBundle\Entity\Transferencia $transferencia
     * @return Movimiento
     */
    public function setTransferencia(\InventarioBundle\Entity\Transferencia $transferencia = null)
    {
        $this->transferencia = $transferencia;

        return $this;
    }

    /**
     * Get transferencia
     *
     * @return \InventarioBundle\Entity\Transferencia 
     */
    public function getTransferencia()
    {
        return $this->transferencia;
    }
}
