<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Rol;

/**
 * @ORM\Entity(repositoryClass="cobe\GruposBundle\Repository\RolGrupoPersonaRepository")
 * @ORM\Table(options={"comment":"Roles de las Personas en el Grupo"})
 */
class RolGrupoPersona extends Rol
{
    /**
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\GrupoPersona", mappedBy="rolPersona")
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
     * @return RolGrupoPersona
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
