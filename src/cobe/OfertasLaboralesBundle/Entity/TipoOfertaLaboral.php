<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\TipoOfertaLaboralRepository")
 * @ORM\Table(options={"comment":"Tipos de las OfertaLaboral"})
 */
class TipoOfertaLaboral extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="tipoOfertasLaborales")
     */
    private $ofertasLaboralesTipo;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->ofertasLaboralesTipo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add ofertasLaboralesTipo
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesTipo
     * @return TipoOfertaLaboral
     */
    public function addOfertasLaboralesTipo(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesTipo)
    {
        $this->ofertasLaboralesTipo[] = $ofertasLaboralesTipo;

        return $this;
    }

    /**
     * Remove ofertasLaboralesTipo
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesTipo
     */
    public function removeOfertasLaboralesTipo(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesTipo)
    {
        $this->ofertasLaboralesTipo->removeElement($ofertasLaboralesTipo);
    }

    /**
     * Get ofertasLaboralesTipo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertasLaboralesTipo()
    {
        return $this->ofertasLaboralesTipo;
    }
}
