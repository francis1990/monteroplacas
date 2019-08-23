<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Venta
 *
 * @ORM\Table(name="venta")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\VentaRepository")
 */
class Venta
{

     /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Venta", inversedBy="venta" )
     * @ORM\JoinTable(name="venta_nota",
     *   joinColumns={
     *     @ORM\JoinColumn(name="venta_id", referencedColumnName="id",onDelete="cascade", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="nota_id", referencedColumnName="id", nullable=true)
     *   }
     * )
     */
    protected $venta;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Sistemadmin\BackendBundle\Entity\ArticuloVenta", cascade={"persist", "remove"}, mappedBy="venta" )
     * @Assert\Count(min=1, minMessage="Debe agregar al menos 1 artículo")
     */
    protected $articuloventas;


    /**
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\Documento")
     */
    protected $documento;

    /**
     * @ORM\ManyToOne(targetEntity="Sistemadmin\BackendBundle\Entity\Cliente")
     */
    protected $cliente;


    /**
     * @ORM\ManyToOne(targetEntity="Vendedor", inversedBy="ventas")
     * @ORM\JoinColumn(name="vendedor_id", referencedColumnName="id")
     */
    protected $vendedor;

    /**
     * Set vendedor
     *
     * @param \Sistemadmin\BackendBundle\Entity\Vendedor $vendedor
     * @return Venta
     */
    public function setVendedor(\Sistemadmin\BackendBundle\Entity\Vendedor $vendedor = null)
    {
        $this->vendedor = $vendedor;

        return $this;
    }

    /**
     * Get vendedor
     *
     * @return \Sistemadmin\BackendBundle\Entity\Vendedor
     */
    public function getVendedor()
    {
        return $this->vendedor;
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
     * @var string
     *
     * @ORM\Column(name="centrodecosto", type="string", length=255)
     */
    private $centrodecosto;

    /**
     * @var string
     *
     * @ORM\Column(name="formadepago", type="string", length=255)
     */
    private $formadepago;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;




    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidaddearticulos", type="float")
     */
    private $cantidaddearticulos;

    /**
     * @var float
     *
     * @ORM\Column(name="montototalapagar", type="float")
     */
    private $montototalapagar;

    /**
     * @var float
     *
     * @ORM\Column(name="totalrecibido", type="float", nullable=true)
     */
    private $totalrecibido;
    
    /**
     * @var int
     *
     * @ORM\Column(name="finalizada", type="boolean", nullable=true)
     */
    private $finalizada;    
    
    /**
     * @var boolean $utilizado
     *
     * @ORM\Column(name="anulada", type="boolean", nullable=true)
     */
    protected $anulada;   


    /**
     * @var string
     *
     * @ORM\Column(name="newnumerofactura", type="string", length=255, nullable=true)
     */
    private $newnumerofactura;
    
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
     * Set centrodecosto
     *
     * @param string $centrodecosto
     * @return Venta
     */
    public function setCentrodecosto($centrodecosto)
    {
        $this->centrodecosto = $centrodecosto;

        return $this;
    }

    /**
     * Get centrodecosto
     *
     * @return string
     */
    public function getCentrodecosto()
    {
        return $this->centrodecosto;
    }

    /**
     * Set formadepago
     *
     * @param string $formadepago
     * @return Venta
     */
    public function setFormadepago($formadepago)
    {
        $this->formadepago = $formadepago;

        return $this;
    }

    /**
     * Get formadepago
     *
     * @return string
     */
    public function getFormadepago()
    {
        return $this->formadepago;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Venta
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
     * Set observacion
     *
     * @param string $observacion
     * @return Venta
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set cantidaddearticulos
     *
     * @param integer $cantidaddearticulos
     * @return Venta
     */
    public function setCantidaddearticulos($cantidaddearticulos)
    {
        $this->cantidaddearticulos = $cantidaddearticulos;

        return $this;
    }

    /**
     * Get cantidaddearticulos
     *
     * @return integer
     */
    public function getCantidaddearticulos()
    {
        return $this->cantidaddearticulos;
    }

    /**
     * Set montototalapagar
     *
     * @param float $montototalapagar
     * @return Venta
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
     * @return Venta
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
     * Constructor
     */
    public function __construct()
    {
        $this->articuloventas = new \Doctrine\Common\Collections\ArrayCollection();
    }
 

    /**
     * Get articulosventa
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticuloventas()
    {
        return $this->articuloventas;
    }

    /**
     * Set cliente
     *
     * @param \Sistemadmin\BackendBundle\Entity\Cliente $cliente
     * @return Venta
     */
    public function setCliente(\Sistemadmin\BackendBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Sistemadmin\BackendBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set documento
     *
     * @param \Sistemadmin\BackendBundle\Entity\Documento $documento
     * @return Venta
     */
    public function setDocumento(\Sistemadmin\BackendBundle\Entity\Documento $documento = null)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * Get documento
     *
     * @return \Sistemadmin\BackendBundle\Entity\Documento
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Set serie
     *
     * @param string $serie
     * @return Venta
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
     * @return Venta
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
     * Add articuloventas
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloVenta $articuloventas
     * @return Venta
     */
    public function addArticuloventa(\Sistemadmin\BackendBundle\Entity\ArticuloVenta $articuloventas)
    {
        $articuloventas->setVenta($this);
        $this->articuloventas[] = $articuloventas;

        return $this;
    }

    /**
     * Remove articuloventas
     *
     * @param \Sistemadmin\BackendBundle\Entity\ArticuloVenta $articuloventas
     */
    public function removeArticuloventa(\Sistemadmin\BackendBundle\Entity\ArticuloVenta $articuloventas)
    {
        $this->articuloventas->removeElement($articuloventas);
    }

    public function getNumeroVenta(){
        return $this->serie.'-'.$this->numerodedocumento;
    }
    

    /**
     * Set finalizada
     *
     * @param boolean $finalizada
     * @return Venta
     */
    public function setFinalizada($finalizada)
    {
        $this->finalizada = $finalizada;

        return $this;
    }

    /**
     * Get finalizada
     *
     * @return boolean 
     */
    public function getFinalizada()
    {
        return $this->finalizada;
    }
    
    /**
     * @Assert\IsTrue(message = "El monto pagado debe ser igual o inferior al monto por pagar")     *
     */
    public function isLegalVenta()
    {
        return $this->montototalapagar >= $this->totalrecibido;
    }

    /**
     * Set anulada
     *
     * @param boolean $anulada
     * @return Venta
     */
    public function setAnulada($anulada)
    {
        $this->anulada = $anulada;

        return $this;
    }

    /**
     * Get anulada
     *
     * @return boolean 
     */
    public function getAnulada()
    {
        return $this->anulada;
    }

    /**
     * Set newnumerofactura
     *
     * @param string $newnumerofactura
     * @return Venta
     */
    public function setNewnumerofactura($newnumerofactura)
    {
        $this->newnumerofactura = $newnumerofactura;

        return $this;
    }

    /**
     * Get newnumerofactura
     *
     * @return string 
     */
    public function getNewnumerofactura()
    {
        return $this->newnumerofactura;
    }
    
    public function __toString()
    {
        return $this->serie."-". $this->numerodedocumento;

    }
    

    /**
     * Add venta
     *
     * @param \Sistemadmin\BackendBundle\Entity\Venta $venta
     * @return Venta
     */
    public function addVenta(\Sistemadmin\BackendBundle\Entity\Venta $venta)
    {
        $this->venta[] = $venta;

        return $this;
    }

    /**
     * Remove venta
     *
     * @param \Sistemadmin\BackendBundle\Entity\Venta $venta
     */
    public function removeVenta(\Sistemadmin\BackendBundle\Entity\Venta $venta)
    {
        $this->venta->removeElement($venta);
    }

    /**
     * Get venta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVenta()
    {
        return $this->venta;
    }
}
