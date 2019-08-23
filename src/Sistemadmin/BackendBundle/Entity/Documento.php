<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Documento
 *
 * @ORM\Table(name="documento")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\DocumentoRepository")
 */
class Documento
{
    /**
     * @ORM\OneToOne(targetEntity="Venta")
     * @ORM\JoinColumn(name="venta_id", referencedColumnName="id")
     **/
    protected $venta;

    /**
     * Set venta
     *
     * @param \Sistemadmin\BackendBundle\Entity\Venta $venta
     * @return Documento
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
     * @ORM\Column(name="codigo", type="integer")
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="tipodocumento", type="string", length=255)
     */
    private $tipodocumento;

    /**
     * @var int
     *
     * @ORM\Column(name="serie1", type="integer")
     */
    private $serie1;

    /**
     * @var int
     *
     * @ORM\Column(name="serie2", type="string", length=255)
     */
    private $serie2;

    /**
     * @var int
     *
     * @ORM\Column(name="serie3", type="string", length=255)
     */
    private $serie3;

    /**
     * @var int
     *
     * @ORM\Column(name="numero1", type="integer")
     */
    private $numero1;

    /**
     * @var int
     *
     * @ORM\Column(name="numero2", type="integer")
     */
    private $numero2;

    /**
     * @var int
     *
     * @ORM\Column(name="numero3", type="integer")
     */
    private $numero3;

    /**
     * @var int
     *
     * @ORM\Column(name="numerodelineas", type="integer")
     */
    private $numerodelineas;

    /**
     * @var int
     *
     * @ORM\Column(name="igv", type="boolean")
     */
    private $igv;


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
     * Set codigo
     *
     * @param integer $codigo
     * @return Documento
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set tipodocumento
     *
     * @param string $tipodocumento
     * @return Documento
     */
    public function setTipodocumento($tipodocumento)
    {
        $this->tipodocumento = $tipodocumento;

        return $this;
    }

    /**
     * Get tipodocumento
     *
     * @return string
     */
    public function getTipodocumento()
    {
        return $this->tipodocumento;
    }

    /**
     * Set serie1
     *
     * @param integer $serie1
     * @return Documento
     */
    public function setSerie1($serie1)
    {
        $this->serie1 = $serie1;

        return $this;
    }

    /**
     * Get serie1
     *
     * @return integer
     */
    public function getSerie1()
    {
        return $this->serie1;
    }

    /**
     * Set serie2
     *
     * @param integer $serie2
     * @return Documento
     */
    public function setSerie2($serie2)
    {
        $this->serie2 = $serie2;

        return $this;
    }

    /**
     * Get serie2
     *
     * @return integer
     */
    public function getSerie2()
    {
        return $this->serie2;
    }

    /**
     * Set serie3
     *
     * @param integer $serie3
     * @return Documento
     */
    public function setSerie3($serie3)
    {
        $this->serie3 = $serie3;

        return $this;
    }

    /**
     * Get serie3
     *
     * @return integer
     */
    public function getSerie3()
    {
        return $this->serie3;
    }

    /**
     * Set numero1
     *
     * @param integer $numero1
     * @return Documento
     */
    public function setNumero1($numero1)
    {
        $this->numero1 = $numero1;

        return $this;
    }

    /**
     * Get numero1
     *
     * @return integer
     */
    public function getNumero1()
    {
        return $this->numero1;
    }

    /**
     * Set numero2
     *
     * @param integer $numero2
     * @return Documento
     */
    public function setNumero2($numero2)
    {
        $this->numero2 = $numero2;

        return $this;
    }

    /**
     * Get numero2
     *
     * @return integer
     */
    public function getNumero2()
    {
        return $this->numero2;
    }

    /**
     * Set numero3
     *
     * @param integer $numero3
     * @return Documento
     */
    public function setNumero3($numero3)
    {
        $this->numero3 = $numero3;

        return $this;
    }

    /**
     * Get numero3
     *
     * @return integer
     */
    public function getNumero3()
    {
        return $this->numero3;
    }

    /**
     * Set numerodelineas
     *
     * @param integer $numerodelineas
     * @return Documento
     */
    public function setNumerodelineas($numerodelineas)
    {
        $this->numerodelineas = $numerodelineas;

        return $this;
    }

    /**
     * Get numerodelineas
     *
     * @return integer
     */
    public function getNumerodelineas()
    {
        return $this->numerodelineas;
    }

    /**
     * Set igv
     *
     * @param boolean $igv
     * @return Documento
     */
    public function setIgv($igv)
    {
        $this->igv = $igv;

        return $this;
    }

    /**
     * Get igv
     *
     * @return boolean
     */
    public function getIgv()
    {
        return $this->igv;
    }

    public function __toString()
    {
        return $this->tipodocumento."-". $this->getNumero1();

    }
}
