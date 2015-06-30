<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Rol;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\RolOfertaLaboralPersonaRepository")
 * @ORM\Table(options={"comment":"Roles de la Persona en la Oferta Laboral"})
 */
class RolOfertaLaboralPersona extends Rol
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(
     *     targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona",
     *     mappedBy="rol"
     * )
     */
    private $proponentes;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->proponentes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add proponentes
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $proponentes
     * @return RolOfertaLaboralPersona
     */
    public function addProponente(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $proponentes)
    {
        $this->proponentes[] = $proponentes;

        return $this;
    }

    /**
     * Remove proponentes
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $proponentes
     */
    public function removeProponente(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $proponentes)
    {
        $this->proponentes->removeElement($proponentes);
    }

    /**
     * Get proponentes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProponente()
    {
        return $this->proponentes;
    }
}
