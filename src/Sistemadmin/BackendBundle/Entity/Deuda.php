<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Deuda
 *
 * @ORM\Table(name="deuda")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\DeudaRepository")
 */
class Deuda
{
    /**
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\Cliente")
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
     * @ORM\Column(name="serie", type="integer")
     */
    private $serie;
    
    /**
     * @var int
     *
     * @ORM\Column(name="numerofactura", type="integer")
     */
    private $numerofactura;

    /**
     * @var float
     *
     * @ORM\Column(name="totalapagar", type="float")
     */
    private $totalapagar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechainicio", type="date")
     */
    private $fechainicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechacancelacion", type="date", nullable=true)
     */
    private $fechacancelacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plazoparapagar", type="date", nullable=true)
     */
    private $plazoparapagar;

    /**
     * @var bool
     *
     * @ORM\Column(name="tienedeuda", type="boolean")
     */
    private $tienedeuda;

    /**
     * @var float
     *
     * @ORM\Column(name="deuda", type="float")
     */
    private $deuda;


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
     * @return Deuda
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
     * @return Deuda
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
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return Deuda
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set fechacancelacion
     *
     * @param \DateTime $fechacancelacion
     * @return Deuda
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
     * Set plazoparapagar
     *
     * @param \DateTime $plazoparapagar
     * @return Deuda
     */
    public function setPlazoparapagar($plazoparapagar)
    {
        $this->plazoparapagar = $plazoparapagar;

        return $this;
    }

    /**
     * Get plazoparapagar
     *
     * @return \DateTime
     */
    public function getPlazoparapagar()
    {
        return $this->plazoparapagar;
    }

    /**
     * Set tienedeuda
     *
     * @param boolean $tienedeuda
     * @return Deuda
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
     * Set deuda
     *
     * @param float $deuda
     * @return Deuda
     */
    public function setDeuda($deuda)
    {
        $this->deuda = $deuda;

        return $this;
    }

    /**
     * Get deuda
     *
     * @return float
     */
    public function getDeuda()
    {
        return $this->deuda;
    }

    /**
     * Set serie
     *
     * @param integer $serie
     * @return Deuda
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

    public function getTotalPagado(){

        return $this->getTotalapagar()-$this->getDeuda();
    }
}
