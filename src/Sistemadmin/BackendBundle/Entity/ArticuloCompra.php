<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticuloVenta
 *
 * @ORM\Table(name="articulo_compra")
 * @ORM\Entity()
 */
class ArticuloCompra
{

    /**
     * @ORM\ManyToOne(targetEntity="Compra", inversedBy="articulocompras")
     * @ORM\JoinColumn(name="compra_id", referencedColumnName="id", onDelete="cascade")
     **/
    protected $compra;

    /**
     * @ORM\ManyToOne(targetEntity="Articulo")
     * @ORM\JoinColumn(name="articulo_id", referencedColumnName="id")
     **/
    protected $articulo;

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
     * @var Seccion
     * @ORM\ManyToOne(targetEntity="InventarioBundle\Entity\Seccion")
     * @ORM\JoinColumn(name="seccion", referencedColumnName="id")
     */
    private $seccion;

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
     * @param float $cantidad
     * @return ArticuloCompra
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return ArticuloCompra
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
     * Set compra
     *
     * @param \Sistemadmin\BackendBundle\Entity\Compra $compra
     * @return ArticuloCompra
     */
    public function setCompra(\Sistemadmin\BackendBundle\Entity\Compra $compra = null)
    {
        $this->compra = $compra;

        return $this;
    }

    /**
     * Get compra
     *
     * @return \Sistemadmin\BackendBundle\Entity\Compra 
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * Set articulo
     *
     * @param \Sistemadmin\BackendBundle\Entity\Articulo $articulo
     * @return ArticuloCompra
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
     * Set importe
     *
     * @param float $importe
     * @return ArticuloCompra
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
//        if($this->importe == null)
//            return $this->precio*$this->cantidad;
//        else
//            return $this->importe;
    }

    /**
     * Set seccion
     *
     * @param \InventarioBundle\Entity\Seccion $seccion
     * @return ArticuloCompra
     */
    public function setSeccion(\InventarioBundle\Entity\Seccion $seccion = null)
    {
        $this->seccion = $seccion;

        return $this;
    }

    /**
     * Get seccion
     *
     * @return \InventarioBundle\Entity\Seccion 
     */
    public function getSeccion()
    {
        return $this->seccion;
    }
}
