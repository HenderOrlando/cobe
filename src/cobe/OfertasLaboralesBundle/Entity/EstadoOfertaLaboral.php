<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\EstadoOfertaLaboralRepository")
 * @ORM\Table(options={"comment":"Estados de la OfertaLaboral"})
 */
class EstadoOfertaLaboral extends Estado
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="estadoOfertasLaborales")
     */
    private $ofertasLaboralesEstado;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ofertasLaboralesEstado = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add ofertasLaboralesEstado
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesEstado
     * @return EstadoOfertaLaboral
     */
    public function addOfertasLaboralesEstado(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesEstado)
    {
        $this->ofertasLaboralesEstado[] = $ofertasLaboralesEstado;

        return $this;
    }

    /**
     * Remove ofertasLaboralesEstado
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesEstado
     */
    public function removeOfertasLaboralesEstado(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesEstado)
    {
        $this->ofertasLaboralesEstado->removeElement($ofertasLaboralesEstado);
    }

    /**
     * Get ofertasLaboralesEstado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertasLaboralesEstado()
    {
        return $this->ofertasLaboralesEstado;
    }
}
