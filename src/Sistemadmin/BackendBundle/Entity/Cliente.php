<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cliente
 *
 * @ORM\Table(name="cliente")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\ClienteRepository")
 */
class Cliente
{


    public function __construct()
    {
        $this->ventas = new ArrayCollection();
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
     * @ORM\Column(name="dni", type="string", length=11, nullable=true)
     * @Assert\Length(max=11)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="ruc", type="string", length=11, nullable=true)
     * @Assert\Length(max=11,min=11)
     */
    private $ruc;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

     /**
     * @var string
     *
     * @ORM\Column(name="razonsoc", type="text", nullable=true)
     */
    private $razonsoc;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var int
     *
     * @ORM\Column(name="telefono", type="integer", nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255, nullable=true)
     */
    private $tipo;

    /**
     * @var int
     *
     * @ORM\Column(name="deuda", type="boolean", nullable=true)
     */
    private $deuda;

    /**
     * @var float
     *
     * @ORM\Column(name="montodeuda", type="float", nullable=true)
     */
    private $montodeuda;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechacancelacion", type="date", nullable=true)
     */
    private $fechacancelacion;
    
    /**
     * @var boolean $utilizado
     *
     * @ORM\Column(name="utilizado", type="boolean", nullable=true)
     */
    protected $utilizado;   

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
     * Set nombre
     *
     * @param string $nombre
     * @return Cliente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set razonsoc
     *
     * @param string $razonsoc
     * @return Cliente
     */
    public function setRazonsoc($razonsoc)
    {
        $this->razonsoc = $razonsoc;

        return $this;
    }

    /**
     * Get razonsoc
     *
     * @return string
     */
    public function getRazonsoc()
    {
        return $this->razonsoc;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Cliente
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     * @return Cliente
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return integer
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

 

    /**
     * Set email
     *
     * @param string $email
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set deuda
     *
     * @param boolean $deuda
     * @return Cliente
     */
    public function setDeuda($deuda)
    {
        $this->deuda = $deuda;

        return $this;
    }

    /**
     * Get deuda
     *
     * @return boolean
     */
    public function getDeuda()
    {
        return $this->deuda;
    }

    /**
     * Set montodeuda
     *
     * @param float $montodeuda
     * @return Cliente
     */
    public function setMontodeuda($montodeuda)
    {
        $this->montodeuda = $montodeuda;

        return $this;
    }

    /**
     * Get montodeuda
     *
     * @return float
     */
    public function getMontodeuda()
    {
        return $this->montodeuda;
    }

    /**
     * Set fechacancelacion
     *
     * @param \DateTime $fechacancelacion
     * @return Cliente
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
    

    public function getNombreApellidos(){
      return $this->nombre;
    }    

    public function __toString()
    {
        return $this->nombre."-". $this->ruc;

    }


    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Cliente
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set dni
     *
     * @param string $dni
     * @return Cliente
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set ruc
     *
     * @param string $ruc
     * @return Cliente
     */
    public function setRuc($ruc)
    {
        $this->ruc = $ruc;

        return $this;
    }

    /**
     * Get ruc
     *
     * @return string 
     */
    public function getRuc()
    {
        return $this->ruc;
    }

    /**
     * Set utilizado
     *
     * @param boolean $utilizado
     * @return Cliente
     */
    public function setUtilizado($utilizado)
    {
        $this->utilizado = $utilizado;

        return $this;
    }

    /**
     * Get utilizado
     *
     * @return boolean 
     */
    public function getUtilizado()
    {
        return $this->utilizado;
    }
}
