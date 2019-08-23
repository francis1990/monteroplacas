<?php

namespace Sistemadmin\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Vendedor
 *
 * @ORM\Table(name="vendedor")
 * @ORM\Entity(repositoryClass="Sistemadmin\BackendBundle\Repository\VendedorRepository")
 */
class Vendedor
{


    public function __construct()
    {

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
     * @ORM\Column(name="dni", type="integer", nullable=true)
     * @Assert\Length(max=8)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

 
    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var int
     *
     * @ORM\Column(name="telefono", type="string", nullable=true)
     * @Assert\Length(max=30)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", nullable=true)
     * @Assert\Length(max=30)
     */
    private $celular;

 
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;
        
    /**
     * @var boolean $utilizado
     *
     * @ORM\Column(name="utilizado", type="boolean", nullable=true)
     */
    protected $utilizado;   
 

    /**
     * @var string
     *
     * @ORM\Column(name="imagename", type="string", length=255, nullable=true)
     */
    private $imagename;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/vendedor' ;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/vendedor' ;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'gallery/';
    }

    public function upload($finalname) {

//          ini_set('max_execution_time', 300);
//          ini_set('memory_limit', '64M');

        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(), $this->getFile()->getClientOriginalName()
        );

        $origin = 'gallery/' . $this->getFile()->getClientOriginalName();
        $targetname1='gallery/' . $finalname . '.' . $this->getFile()->getClientOriginalExtension();
        $finalname1 = 'gallery/vendedor/' . $finalname . '.' . $this->getFile()->getClientOriginalExtension();

        $filesystem = new Filesystem();
        // renombro la imagen con el nombre que recibo como argumento en filename
        if (!$filesystem->exists($targetname1)) {
            $filesystem->rename($origin, $targetname1);
        }
        //verifico antes de copiar para mi carpeta destino que no exista y si lo hace elimino
        if ($filesystem->exists($finalname1)) {
            $filesystem->remove($finalname1);
        }
        $filesystem->copy($targetname1, $finalname1);

        $this->setImagename($finalname . '.' .$this->getFile()->getClientOriginalExtension());
        //elimino la que quedo en la carpeta original
        if ($filesystem->exists($targetname1)) {
            $filesystem->remove($targetname1);
        }

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Set imagename
     *
     * @param string $imagename
     * @return ArticuloInterno
     */
    public function setImagename($imagename)
    {
        $this->imagename = $imagename;

        return $this;
    }

    /**
     * Get imagename
     *
     * @return string
     */
    public function getImagename()
    {
        return $this->imagename;
    }

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
     * Set dni
     *
     * @param integer $dni
     * @return Vendedor
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return integer
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Vendedor
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

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
     * Set direccion
     *
     * @param string $direccion
     * @return Vendedor
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     * @return Vendedor
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return integer
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param integer $celular
     * @return Vendedor
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }


    /**
     * Set email
     *
     * @param string $email
     * @return Vendedor
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }



    public function __toString()
    {
        return $this->nombre."-". $this->dni;

    }

    /**
     * Set utilizado
     *
     * @param boolean $utilizado
     * @return Vendedor
     */
    public function setUtilizado($utilizado)
    {
        $this->utilizado = $utilizado;

        return $this;
    }

    /**
     * Get utilizado
     *
     * @return boolean 
     */
    public function getUtilizado()
    {
        return $this->utilizado;
    }
}
