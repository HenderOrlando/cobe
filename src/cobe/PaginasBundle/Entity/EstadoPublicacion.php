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
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="estadoPublicacion")
     */
    private $publicacionesEstado;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
