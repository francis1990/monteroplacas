<?php

namespace InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sistemadmin\BackendBundle\Util\Util;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Almacen
 *
 * @ORM\Table(name="almacen")
 * @ORM\Entity(repositoryClass="InventarioBundle\Repository\AlmacenRepository")
 * @UniqueEntity(fields= {"alias"}, errorPath="nombre",message="Existe un elemento con el mismo nombre.")
 */
class Almacen
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
     * @return Almacen
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

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Almacen
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
