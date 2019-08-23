<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Compra
 *
 * @ORM\Table(name="compra")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\CompraRepository")
 * @UniqueEntity("numerofactura", message="Ya existe una compra con ese número de documento")
 */
class Compra
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
     * @ORM\Column(name="cantidaddearticulo", type="float")
     */
    private $cantidaddearticulo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechacompra", type="date")
     */
    private $fechacompra;

    /**
     * @var float
     *
     * @ORM\Column(name="montototalpagado", type="float")
     */
    private $montototalpagado;

    /**
     * @var string
     *
     * @ORM\Column(name="moneda", type="string", length=255)
     */
    private $moneda;



    /**
     * @ORM\OneToMany(targetEntity="Sistemadmin\BackendBundle\Entity\ArticuloCompra", cascade={"persist", "remove"}, mappedBy="compra" ,orphanRemoval=true)
     *   @ORM\JoinColumn(referencedColumnName="id")
     * @Assert\Count(min=1, minMessage="Debe agregar al menos 1 artículo")
     */
    protected $articulocompras;

    /**
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\Proveedor")
     */
    protected $proveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="centrodecosto", type="string", length=255)
     */
    private $centrodecosto;

    /**
     * @var string
     *
     * @ORM\Column(name="formadepago", type="string", length=255)
     */
    private $formadepago;

    /**
     * @var string
     *
     * @ORM\Column(name="numerofactura", type="string", length=10,unique=true )
     */
    private $numerofactura;

    /**
     * @var int
     *
     * @ORM\Column(name="garantia", type="string", length=255, nullable=true)
     */
    private $garantia;

    /**
     * @var int
     *
     * @ORM\Column(name="tiempoentrega", type="integer")
     */
    private $tiempoentrega;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articulocompras = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set cantidaddearticulo
     *
     * @param float $cantidaddearticulo
     * @return Compra
     */
    public function setCantidaddearticulo($cantidaddearticulo)
    {
        $this->cantidaddearticulo = $cantidaddearticulo;

        return $this;
    }

    /**
     * Get cantidaddearticulo
     *
     * @return float
     */
    public function getCantidaddearticulo()
    {
        return $this->cantidaddearticulo;
    }

    /**
     * Set fechacompra
     *
     * @param \DateTime $fechacompra
     * @return Compra
     */
    public function setFechacompra($fechacompra)
    {
        $this->fechacompra = $fechacompra;

        return $this;
    }

    /**
     * Get fechacompra
     *
     * @return \DateTime 
     */
    public function getFechacompra()
    {
        return $this->fechacompra;
    }

    /**
     * Set montototalpagado
     *
     * @param float $montototalpagado
     * @return Compra
     */
    public function setMontototalpagado($montototalpagado)
    {
        $this->montototalpagado = $montototalpagado;

        return $this;
    }

    /**
     * Get montototalpagado
     *
     * @return float 
     */
    public function getMontototalpagado()
    {
        return $this->montototalpagado;
    }

    /**
     * Set moneda
     *
     * @param string $moneda
     * @return Compra
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get moneda
     *
     * @return string 
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Add articulocompras
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloCompra $articulocompras
     * @return Compra
     */
    public function addArticulocompra(\Sistemadmin\BackendBundle\Entity\ArticuloCompra $articulocompras)
    {
        $articulocompras->setCompra($this);
        $this->articulocompras[] = $articulocompras;

        return $this;
    }

    /**
     * Remove articulocompras
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloCompra $articulocompras
     */
    public function removeArticulocompra(\Sistemadmin\BackendBundle\Entity\ArticuloCompra $articulocompras)
    {
        $this->articulocompras->removeElement($articulocompras);
    }

    /**
     * Get articulocompras
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticulocompras()
    {
        return $this->articulocompras;
    }

    /**
     * Set proveedor
     *
     * @param \Sistemadmin\BackendBundle\Entity\Proveedor $proveedor
     * @return Compra
     */
    public function setProveedor(\Sistemadmin\BackendBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \Sistemadmin\BackendBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set centrodecosto
     *
     * @param string $centrodecosto
     * @return Compra
     */
    public function setCentrodecosto($centrodecosto)
    {
        $this->centrodecosto = $centrodecosto;

        return $this;
    }

    /**
     * Get centrodecosto
     *
     * @return string 
     */
    public function getCentrodecosto()
    {
        return $this->centrodecosto;
    }

    /**
     * Set formadepago
     *
     * @param string $formadepago
     * @return Compra
     */
    public function setFormadepago($formadepago)
    {
        $this->formadepago = $formadepago;

        return $this;
    }

    /**
     * Get formadepago
     *
     * @return string 
     */
    public function getFormadepago()
    {
        return $this->formadepago;
    }

    /**
     * Set garantia
     *
     * @param integer $garantia
     * @return Compra
     */
    public function setGarantia($garantia)
    {
        $this->garantia = $garantia;

        return $this;
    }

    /**
     * Get garantia
     *
     * @return integer 
     */
    public function getGarantia()
    {
        return $this->garantia;
    }

    /**
     * Set tiempoentrega
     *
     * @param integer $tiempoentrega
     * @return Compra
     */
    public function setTiempoentrega($tiempoentrega)
    {
        $this->tiempoentrega = $tiempoentrega;

        return $this;
    }

    /**
     * Get tiempoentrega
     *
     * @return integer 
     */
    public function getTiempoentrega()
    {
        return $this->tiempoentrega;
    }

    /**
     * Set numerofactura
     *
     * @param string $numerofactura
     * @return Compra
     */
    public function setNumerofactura($numerofactura)
    {
        $this->numerofactura = $numerofactura;

        return $this;
    }

    /**
     * Get numerofactura
     *
     * @return string 
     */
    public function getNumerofactura()
    {
        return $this->numerofactura;
    }
	
	 public function __toString()
    {
        return  $this->numerofactura;

    }
}
