<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use InventarioBundle\Entity\Seccion;

/**
 * ArticuloNotaDebito
 *
 * @ORM\Table(name="articulo_nota_debito")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\ArticuloNotaDebitoRepository")
 */
class ArticuloNotaDebito
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
     * @ORM\ManyToOne(targetEntity="NotaDebito")
     * @ORM\JoinColumn(name="notadebito_id", referencedColumnName="id",onDelete="cascade")
     **/
    protected $notadebito;
    
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
     * @return ArticuloNotaDebito
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
     * @return ArticuloNotaDebito
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
     * @return ArticuloNotaDebito
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
     * Set notadebito
     *
     * @param \Sistemadmin\BackendBundle\Entity\NotaDebito $notadebito
     * @return ArticuloNotaDebito
     */
    public function setNotadebito(\Sistemadmin\BackendBundle\Entity\NotaDebito $notadebito = null)
    {
        $this->notadebito = $notadebito;

        return $this;
    }

    /**
     * Get notadebito
     *
     * @return \Sistemadmin\BackendBundle\Entity\NotaDebito
     */
    public function getNotaDebito()
    {
        return $this->notadebito;
    }

    /**
     * Set articulo
     *
     * @param \Sistemadmin\BackendBundle\Entity\Articulo $articulo
     * @return ArticuloNotaDebito
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
