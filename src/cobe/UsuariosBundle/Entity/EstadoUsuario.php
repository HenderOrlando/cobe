<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\EstadoUsuarioRepository")
 * @ORM\Table(options={"comment":"Estados de los Usuarios"})
 */
class EstadoUsuario extends Estado
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", mappedBy="estado")
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
     * @return EstadoUsuario
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
