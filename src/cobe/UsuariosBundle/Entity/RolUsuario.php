<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Rol;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\RolUsuarioRepository")
 * @ORM\Table(options={"comment":"Roles de los Usuarios"})
 */
class RolUsuario extends Rol
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", mappedBy="rol")
     */
    private $usuarios;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Add usuarios
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuarios
     * @return RolUsuario
     */
    public function addUsuario(\cobe\UsuariosBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;

        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\cobe\UsuariosBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

}
