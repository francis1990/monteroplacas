<?php

namespace InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sistemadmin\BackendBundle\Entity\Articulo;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Transferencia
 *
 * @ORM\Table(name="transferencia")
 * @ORM\Entity(repositoryClass="InventarioBundle\Repository\TransferenciaRepository")
 */
class Transferencia
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
     * @Assert\Type(
     * type="float",
     * message="El valor  no es valido."
     * )
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message ="Debe introducir un número mayor o igual a cero")
     *
     */
    private $cantidad;

    /**
     * @var Articulo
     *
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\Articulo")
     * @ORM\JoinColumn(name="articulo", referencedColumnName="id")
     **/

    private $articulo;

    /**
     * @var Seccion
     * @ORM\ManyToOne(targetEntity="Seccion")
     * @ORM\JoinColumn(name="seccionini", referencedColumnName="id")
     */
    private $seccionini;

    /**
     * @var Seccion
     * @ORM\ManyToOne(targetEntity="Seccion")
     * @ORM\JoinColumn(name="seccionfin", referencedColumnName="id")
     */
    private $seccionfin;


    /**
     * @var Date
     *
     * @ORM\Column(name="fecha", type="date")
     * @Assert\Date(message="La fecha no es una fecha válida.")
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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Transferencia
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Transferencia
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
     * @return Transferencia
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
     * Set seccionini
     *
     * @param \InventarioBundle\Entity\Seccion $seccionini
     * @return Transferencia
     */
    public function setSeccionini(\InventarioBundle\Entity\Seccion $seccionini = null)
    {
        $this->seccionini = $seccionini;

        return $this;
    }

    /**
     * Get seccionini
     *
     * @return \InventarioBundle\Entity\Seccion 
     */
    public function getSeccionini()
    {
        return $this->seccionini;
    }

    /**
     * Set seccionfin
     *
     * @param \InventarioBundle\Entity\Seccion $seccionfin
     * @return Transferencia
     */
    public function setSeccionfin(\InventarioBundle\Entity\Seccion $seccionfin = null)
    {
        $this->seccionfin = $seccionfin;

        return $this;
    }

    /**
     * Get seccionfin
     *
     * @return \InventarioBundle\Entity\Seccion 
     */
    public function getSeccionfin()
    {
        return $this->seccionfin;
    }

    /**
     * @Assert\IsTrue(message = "La sección actual y sección destino deben ser diferentes",payload="seccionfin")
     */
    public function isSeccionesValidas(){
        return $this->getSeccionfin()->getId()!= $this->getSeccionini()->getId();
    }

}
