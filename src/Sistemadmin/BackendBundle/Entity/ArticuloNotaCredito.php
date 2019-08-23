<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ArticuloNotaCredito
 *
 * @ORM\Table(name="articulo_notacredito")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\ArticuloNotaCreditoRepository")
 */
class ArticuloNotaCredito
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
     * @ORM\ManyToOne(targetEntity="NotaCredito")
     * @ORM\JoinColumn(name="notacredito_id", referencedColumnName="id",onDelete="cascade")
     **/
    protected $notacredito;
    
    /**
     * @ORM\ManyToOne(targetEntity="Articulo")
     * @ORM\JoinColumn(name="articulo_id", referencedColumnName="id")
     **/
    protected $articulo;

    /**
     * @var float
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;

    /**
     * @var float
     *
     * @ORM\Column(name="importe", type="float")
     */
    private $importe;




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
     * @return ArticuloNotaCredito
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
     * Set precio
     *
     * @param float $precio
     * @return ArticuloNotaCredito
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set importe
     *
     * @param float $importe
     * @return ArticuloNotaCredito
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return float 
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set notacredito
     *
     * @param \Sistemadmin\BackendBundle\Entity\NotaCredito $notacredito
     * @return ArticuloNotaCredito
     */
    public function setNotacredito(\Sistemadmin\BackendBundle\Entity\NotaCredito $notacredito = null)
    {
        $this->notacredito = $notacredito;

        return $this;
    }

    /**
     * Get notacredito
     *
     * @return \Sistemadmin\BackendBundle\Entity\NotaCredito 
     */
    public function getNotaCredito()
    {
        return $this->notacredito;
    }

    /**
     * Set articulo
     *
     * @param \Sistemadmin\BackendBundle\Entity\Articulo $articulo
     * @return ArticuloNotaCredito
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

}
