<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\PaginasBundle\Entity\Plantilla;

/**
 * @ORM\Entity
 */
class PlantillaPublicacion extends Plantilla
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="plantilla")
     */
    private $publicaciones;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->publicaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     * @return PlantillaPublicacion
     */
    public function addPublicacione(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones[] = $publicaciones;

        return $this;
    }

    /**
     * Remove publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     */
    public function removePublicacione(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones->removeElement($publicaciones);
    }

    /**
     * Get publicaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublicaciones()
    {
        return $this->publicaciones;
    }
}
