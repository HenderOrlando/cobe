<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\PaginasBundle\Entity\Plantilla;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\PlantillaUsuarioRepository")
 * @ORM\Table(options={"comment":"Plantillas para Usuario"})
 */
class PlantillaUsuario extends Plantilla
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", mappedBy="plantilla")
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
     * @return PlantillaUsuario
     */
    public function addUsuarios(\cobe\UsuariosBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;

        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuarios
     */
    public function removeUsuarios(\cobe\UsuariosBundle\Entity\Usuario $usuarios)
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
