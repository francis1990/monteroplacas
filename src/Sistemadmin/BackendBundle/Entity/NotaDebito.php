<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * NotaDebito
 *
 * @ORM\Table(name="nota_debito")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\NotaDebitoRepository")
 */
class NotaDebito
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
     /**
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\Venta")
     * @ORM\JoinColumn(name="venta_id", referencedColumnName="id",onDelete="cascade")
     **/
    protected $venta;
    
    /**
     * @ORM\OneToMany(targetEntity="Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito", cascade={"persist", "remove"}, mappedBy="notadebito" )
     * @Assert\Count(min=0, minMessage="Debe agregar al menos 1 artículo")
     */
    protected $articulonotadebitos;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo", type="string", length=255, nullable=true)
     */
    private $motivo;
    
        /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;
    
       /**
     * @var string
     *
     * @ORM\Column(name="serie", type="string", length=255)
     */
    private $serie;

    /**
     * @var string
     *
     * @ORM\Column(name="numerodedocumento", type="string", length=255)
     */
    private $numerodedocumento;
    
        /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

       /**
     * @var int
     *
     * @ORM\Column(name="cantidaddearticulos", type="float", nullable=true)
     */
    private $cantidaddearticulos;

    /**
     * @var float
     *
     * @ORM\Column(name="montototalapagar", type="float", nullable=true)
     */
    private $montototalapagar;
    
        /**
     * @var float
     *
     * @ORM\Column(name="totalrecibido", type="float", nullable=true)
     */
    private $totalrecibido;

  

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return NotaDebito
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articulonotadebitos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return NotaDebito
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
     * Set serie
     *
     * @param string $serie
     * @return NotaDebito
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return string 
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set numerodedocumento
     *
     * @param string $numerodedocumento
     * @return NotaDebito
     */
    public function setNumerodedocumento($numerodedocumento)
    {
        $this->numerodedocumento = $numerodedocumento;

        return $this;
    }

    /**
     * Get numerodedocumento
     *
     * @return string 
     */
    public function getNumerodedocumento()
    {
        return $this->numerodedocumento;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return NotaDebito
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
     * Set cantidaddearticulos
     *
     * @param float $cantidaddearticulos
     * @return NotaDebito
     */
    public function setCantidaddearticulos($cantidaddearticulos)
    {
        $this->cantidaddearticulos = $cantidaddearticulos;

        return $this;
    }

    /**
     * Get cantidaddearticulos
     *
     * @return float 
     */
    public function getCantidaddearticulos()
    {
        return $this->cantidaddearticulos;
    }

    /**
     * Set montototalapagar
     *
     * @param float $montototalapagar
     * @return NotaDebito
     */
    public function setMontototalapagar($montototalapagar)
    {
        $this->montototalapagar = $montototalapagar;

        return $this;
    }

    /**
     * Get montototalapagar
     *
     * @return float 
     */
    public function getMontototalapagar()
    {
        return $this->montototalapagar;
    }

    /**
     * Set totalrecibido
     *
     * @param float $totalrecibido
     * @return NotaDebito
     */
    public function setTotalrecibido($totalrecibido)
    {
        $this->totalrecibido = $totalrecibido;

        return $this;
    }

    /**
     * Get totalrecibido
     *
     * @return float 
     */
    public function getTotalrecibido()
    {
        return $this->totalrecibido;
    }

    /**
     * Add articulonotadebitos
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito $articulonotadebitos
     * @return NotaDebito
     */
    public function addArticulonotadebito(\Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito $articulonotadebitos)
    {
        $this->articulonotadebitos[] = $articulonotadebitos;

        return $this;
    }

    /**
     * Remove articulonotadebitos
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito $articulonotadebitos
     */
    public function removeArticulonotadebito(\Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito $articulonotadebitos)
    {
        $this->articulonotadebitos->removeElement($articulonotadebitos);
    }

    /**
     * Get articulonotadebitos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticulonotadebitos()
    {
        return $this->articulonotadebitos;
    }

    /**
     * Set venta
     *
     * @param \Sistemadmin\BackendBundle\Entity\Venta $venta
     * @return NotaDebito
     */
    public function setVenta(\Sistemadmin\BackendBundle\Entity\Venta $venta = null)
    {
        $this->venta = $venta;

        return $this;
    }

    /**
     * Get venta
     *
     * @return \Sistemadmin\BackendBundle\Entity\Venta 
     */
    public function getVenta()
    {
        return $this->venta;
    }
}
