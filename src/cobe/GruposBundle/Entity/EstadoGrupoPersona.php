<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\GruposBundle\Repository\EstadoGrupoPersonaRepository")
 * @ORM\Table(options={"comment":"Estados de las Personas en el Grupo"})
 */
class EstadoGrupoPersona extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\GrupoPersona", mappedBy="estadoPersona")
     */
    private $grupoPersona;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->grupoPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add grupoPersona
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $grupoPersona
     * @return EstadoGrupoPersona
     */
    public function addGrupoPersona(\cobe\GruposBundle\Entity\GrupoPersona $grupoPersona)
    {
        $this->grupoPersona[] = $grupoPersona;

        return $this;
    }

    /**
     * Remove grupoPersona
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $grupoPersona
     */
    public function removeGrupoPersona(\cobe\GruposBundle\Entity\GrupoPersona $grupoPersona)
    {
        $this->grupoPersona->removeElement($grupoPersona);
    }

    /**
     * Get grupoPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrupoPersona()
    {
        return $this->grupoPersona;
    }
}
