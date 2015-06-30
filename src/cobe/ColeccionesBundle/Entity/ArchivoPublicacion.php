<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Publicación"})
 */
class ArchivoPublicacion extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", inversedBy="archivos")
     * @ORM\JoinColumn(name="publicacion", referencedColumnName="id")
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
     * @return ArchivoPublicacion
     */
    public function setPublicacion(\cobe\PaginasBundle\Entity\Publicacion $publicacion = null)
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
