<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\MensajesBundle\Entity\Mensaje;

/**
 * @ORM\Entity
 */
class ComentarioPublicacion extends Mensaje
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", inversedBy="comentarios")
     * @ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=false)
     */
    private $publicacion;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set publicacion
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacion
     * @return ComentarioPublicacion
     */
    public function setPublicacion(\cobe\PaginasBundle\Entity\Publicacion $publicacion)
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    /**
     * Get publicacion
     *
     * @return \cobe\PaginasBundle\Entity\Publicacion 
     */
    public function getPublicacion()
    {
        return $this->publicacion;
    }
}
