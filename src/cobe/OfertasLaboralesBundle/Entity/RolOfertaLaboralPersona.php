<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Rol;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\RolOfertaLaboralPersonaRepository")
 * @ORM\Table(options={"comment":"Roles de la Persona en la OfertaLaboral"})
 */
class RolOfertaLaboralPersona extends Rol
{
    /**
     * @ORM\OneToMany(
     *     targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona",
     *     mappedBy="rolOfertaLaboralPersona"
     * )
     */
    private $ofertaLaboralPersona;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->ofertaLaboralPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add ofertaLaboralPersona
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersona
     * @return RolOfertaLaboralPersona
     */
    public function addOfertaLaboralPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersona)
    {
        $this->ofertaLaboralPersona[] = $ofertaLaboralPersona;

        return $this;
    }

    /**
     * Remove ofertaLaboralPersona
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersona
     */
    public function removeOfertaLaboralPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersona)
    {
        $this->ofertaLaboralPersona->removeElement($ofertaLaboralPersona);
    }

    /**
     * Get ofertaLaboralPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertaLaboralPersona()
    {
        return $this->ofertaLaboralPersona;
    }
}
