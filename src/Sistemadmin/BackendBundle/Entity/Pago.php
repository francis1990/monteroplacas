<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Pago
 *
 * @ORM\Table(name="pago")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\PagoRepository")
 */
class Pago
{
    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="pagos")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    protected $cliente;

    /**
     * Set venta
     *
     * @param \Sistemadmin\BackendBundle\Entity\Cliente $cliente
     * @return Cliente
     */
    public function setCliente(\Sistemadmin\BackendBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get venta
     *
     * @return \Sistemadmin\BackendBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="numerofactura", type="integer")
     */
    private $numerofactura;
    
        /**
     * @var int
     *
     * @ORM\Column(name="serie", type="integer")
     */
    private $serie;

    /**
     * @var float
     *
     * @ORM\Column(name="totalapagar", type="float")
     */
    private $totalapagar;

    /**
     * @var float
     *
     * @ORM\Column(name="montopagado", type="float")
     */
    private $montopagado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechapago", type="date")
     */
    private $fechapago;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechacancelacion", type="date", nullable=true)
     */
    private $fechacancelacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="tienedeuda", type="boolean")
     */
    private $tienedeuda;
    
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="formadepago", type="string", length=255)
//     */
//    private $formadepago;


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
     * Set numerofactura
     *
     * @param integer $numerofactura
     * @return Pago
     */
    public function setNumerofactura($numerofactura)
    {
        $this->numerofactura = $numerofactura;

        return $this;
    }

    /**
     * Get numerofactura
     *
     * @return integer
     */
    public function getNumerofactura()
    {
        return $this->numerofactura;
    }

    /**
     * Set totalapagar
     *
     * @param float $totalapagar
     * @return Pago
     */
    public function setTotalapagar($totalapagar)
    {
        $this->totalapagar = $totalapagar;

        return $this;
    }

    /**
     * Get totalapagar
     *
     * @return float
     */
    public function getTotalapagar()
    {
        return $this->totalapagar;
    }

    /**
     * Set montopagado
     *
     * @param float $montopagado
     * @return Pago
     */
    public function setMontopagado($montopagado)
    {
        $this->montopagado = $montopagado;

        return $this;
    }

    /**
     * Get montopagado
     *
     * @return float
     */
    public function getMontopagado()
    {
        return $this->montopagado;
    }

    /**
     * Set fechapago
     *
     * @param \DateTime $fechapago
     * @return Pago
     */
    public function setFechapago($fechapago)
    {
        $this->fechapago = $fechapago;

        return $this;
    }

    /**
     * Get fechapago
     *
     * @return \DateTime
     */
    public function getFechapago()
    {
        return $this->fechapago;
    }

    /**
     * Set fechacancelacion
     *
     * @param \DateTime $fechacancelacion
     * @return Pago
     */
    public function setFechacancelacion($fechacancelacion)
    {
        $this->fechacancelacion = $fechacancelacion;

        return $this;
    }

    /**
     * Get fechacancelacion
     *
     * @return \DateTime
     */
    public function getFechacancelacion()
    {
        return $this->fechacancelacion;
    }

    /**
     * Set tienedeuda
     *
     * @param boolean $tienedeuda
     * @return Pago
     */
    public function setTienedeuda($tienedeuda)
    {
        $this->tienedeuda = $tienedeuda;

        return $this;
    }

    /**
     * Get tienedeuda
     *
     * @return boolean
     */
    public function getTienedeuda()
    {
        return $this->tienedeuda;
    }

    /**
     * Set serie
     *
     * @param integer $serie
     * @return Pago
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return integer 
     */
    public function getSerie()
    {
        return $this->serie;
    }
}
