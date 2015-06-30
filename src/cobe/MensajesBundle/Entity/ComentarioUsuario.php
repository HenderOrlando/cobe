<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\MensajesBundle\Entity\Comentario;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Comentarios a un Usuario"})
 */
class ComentarioUsuario extends Comentario
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="comentarios")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     */
    private $usuario;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set usuario
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuario
     * @return ComentarioUsuario
     */
    public function setUsuario(\cobe\UsuariosBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \cobe\UsuariosBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
