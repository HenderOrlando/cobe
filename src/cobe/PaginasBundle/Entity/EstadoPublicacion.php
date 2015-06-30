<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\EstadoPublicacionRepository")
 * @ORM\Table(options={"comment":"Estados para las Publicaciones"})
 */
class EstadoPublicacion extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="estado")
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
     * @return EstadoPublicacion
     */
    public function addPublicaciones(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones[] = $publicaciones;

        return $this;
    }

    /**
     * Remove publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     */
    public function removePublicaciones(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
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
