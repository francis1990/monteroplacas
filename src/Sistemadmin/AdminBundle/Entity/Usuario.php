<?php

namespace Sistemadmin\AdminBundle\Entity;



use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Sistemadmin\AdminBundle\Repository\UsuarioRepository")
 */
class Usuario  implements AdvancedUserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=25)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     *
     * @var string salt
     */
    protected $salt;

    /**
     * @ORM\ManyToMany(targetEntity="Rol")
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $rols;

    public function __construct()
    {
        $this->activo=true;
    }

    public function getSalt()
    {
        return $this->salt;
    }


    /** se invoca cuando el usuario cierra sesión **/
    public function eraseCredentials()
    {
        return false;
    }

    public function getPassword()
    {
        return $this->password;
    }


    public function setPassword($value)
    {
        $this->password = $value;
    }


    public function getUsername()
    {
        return $this->username;
    }


    public function setUsername($value)
    {
        $this->username = $value;
    }
    public function getUserRoles()
    {
        $r[] = $this->rols->toArray();
        $list = $r[0];
        $roles[] = 'ROLE_USER';

        foreach ($list as $role) {
            $temp = $role->getRole();
            array_push($roles, $temp);
        }
        return array_unique($roles);
    }

    public function getRoles()
    {
        return $this->getUserRoles();
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
     * Set activo
     *
     * @param boolean $activo
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->activo
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->activo
            ) = unserialize($serialized);
    }
    public function isAccountNonExpired()
    {
        return true;
    }
    public function isAccountNonLocked()
    {
        return true;
    }
    public function isCredentialsNonExpired()
    {
        return true;
    }
    public function isEnabled()
    {
        return $this->activo;
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }


    /**
     * Add rols
     *
     * @param \Sistemadmin\AdminBundle\Entity\Rol $rols
     * @return Usuario
     */
    public function addRol(\Sistemadmin\AdminBundle\Entity\Rol $rols)
    {
        $this->rols[] = $rols;

        return $this;
    }

    /**
     * Remove rols
     *
     * @param \Sistemadmin\AdminBundle\Entity\Rol $rols
     */
    public function removeRol(\Sistemadmin\AdminBundle\Entity\Rol $rols)
    {
        $this->rols->removeElement($rols);
    }

    /**
     * Get rols
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRols()
    {
        return $this->rols;
    }
}
