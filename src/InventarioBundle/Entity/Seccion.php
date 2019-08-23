<?php

namespace InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sistemadmin\BackendBundle\Util\Util;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Seccion
 *
 * @ORM\Table(name="seccion")
 * @ORM\Entity(repositoryClass="InventarioBundle\Repository\SeccionRepository")
 * @UniqueEntity(fields= {"alias","almacen"}, errorPath="nombre",message="Ya existe una sección con el mismo nombre para el almacén seleccionado.")
 */
class Seccion
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
     * @var Almacen
     *
     * @ORM\ManyToOne(targetEntity="Almacen")
     */
    private $almacen;

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
     * @return Seccion
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
     * Set almacen
     *
     * @param \InventarioBundle\Entity\Almacen $almacen
     * @return Seccion
     */
    public function setAlmacen(\InventarioBundle\Entity\Almacen $almacen = null)
    {
        $this->almacen = $almacen;

        return $this;
    }

    /**
     * Get almacen
     *
     * @return \InventarioBundle\Entity\Almacen 
     */
    public function getAlmacen()
    {
        return $this->almacen;
    }

    public function __toString()
    {
        return  $this->getAlmacen()->getNombre()  . '-' . $this->getNombre();
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Seccion
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
