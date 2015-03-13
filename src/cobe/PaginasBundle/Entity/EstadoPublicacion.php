<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;

/**
 * @ORM\Entity
 */
class EstadoPublicacion extends Estado
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="estadoPublicacion")
     */
    private $publicacionesEstado;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publicacionesEstado = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add publicacionesEstado
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacionesEstado
     * @return EstadoPublicacion
     */
    public function addPublicacionesEstado(\cobe\PaginasBundle\Entity\Publicacion $publicacionesEstado)
    {
        $this->publicacionesEstado[] = $publicacionesEstado;

        return $this;
    }

    /**
     * Remove publicacionesEstado
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacionesEstado
     */
    public function removePublicacionesEstado(\cobe\PaginasBundle\Entity\Publicacion $publicacionesEstado)
    {
        $this->publicacionesEstado->removeElement($publicacionesEstado);
    }

    /**
     * Get publicacionesEstado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublicacionesEstado()
    {
        return $this->publicacionesEstado;
    }
}
