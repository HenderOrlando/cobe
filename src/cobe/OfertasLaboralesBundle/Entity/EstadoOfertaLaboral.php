<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\EstadoOfertaLaboralRepository")
 * @ORM\Table(options={"comment":"Estados de una OfertaLaboral"})
 */
class EstadoOfertaLaboral extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="estado")
     */
    private $ofertasLaborales;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     * @return EstadoOfertaLaboral
     */
    public function addOfertasLaborales(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales[] = $ofertasLaborales;

        return $this;
    }

    /**
     * Remove ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     */
    public function removeOfertasLaborales(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales->removeElement($ofertasLaborales);
    }

    /**
     * Get ofertasLaborales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertasLaborales()
    {
        return $this->ofertasLaborales;
    }
}
