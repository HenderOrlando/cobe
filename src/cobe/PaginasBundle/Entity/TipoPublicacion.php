<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;

/**
 * @ORM\Entity
 */
class TipoPublicacion extends Tipo
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="tipoPublicacion")
     */
    private $publicacionesTipo;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publicacionesTipo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add publicacionesTipo
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacionesTipo
     * @return TipoPublicacion
     */
    public function addPublicacionesTipo(\cobe\PaginasBundle\Entity\Publicacion $publicacionesTipo)
    {
        $this->publicacionesTipo[] = $publicacionesTipo;

        return $this;
    }

    /**
     * Remove publicacionesTipo
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacionesTipo
     */
    public function removePublicacionesTipo(\cobe\PaginasBundle\Entity\Publicacion $publicacionesTipo)
    {
        $this->publicacionesTipo->removeElement($publicacionesTipo);
    }

    /**
     * Get publicacionesTipo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublicacionesTipo()
    {
        return $this->publicacionesTipo;
    }
}
