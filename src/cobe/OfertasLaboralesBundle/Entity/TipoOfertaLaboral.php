<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\TipoOfertaLaboralRepository")
 * @ORM\Table(options={"comment":"Tipos de las Oferta Laboral"})
 */
class TipoOfertaLaboral extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="tipo")
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
     * @return TipoOfertaLaboral
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
