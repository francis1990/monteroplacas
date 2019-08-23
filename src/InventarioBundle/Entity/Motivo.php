<?php

namespace InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sistemadmin\BackendBundle\Util\Util;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Motivo
 *
 * @ORM\Table(name="motivo")
 * @ORM\Entity(repositoryClass="InventarioBundle\Repository\MotivoRepository")
 * @UniqueEntity(fields= {"alias"}, errorPath="nombre",message="Existe un elemento con el mismo nombre.")
 */
class Motivo
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     * @Assert\NotBlank(message="Debe rellenar el campo")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=50)
     * @Assert\NotBlank(message="Debe rellenar el campo")
     */
    private $abreviatura;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255,nullable=true)
     */
    private $descripcion;

    /**
     * @var bool
     *
     * @ORM\Column(name="tipo", type="integer", nullable=true)
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="conceptodefault", type="integer",nullable=true)
     */
    private $conceptodefault;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", nullable=false)
     *
     */
    private $alias;

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
     * @return Motivo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $this->alias = Util::getSlug($nombre);

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
     * Set abreviatura
     *
     * @param string $abreviatura
     * @return Motivo
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string 
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Motivo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * Set conceptodefault
     *
     * @param integer $conceptodefault
     * @return Motivo
     */
    public function setConceptodefault($conceptodefault)
    {
        $this->conceptodefault = $conceptodefault;

        return $this;
    }

    /**
     * Get conceptodefault
     *
     * @return integer 
     */
    public function getConceptodefault()
    {
        return $this->conceptodefault;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Motivo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Motivo
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
