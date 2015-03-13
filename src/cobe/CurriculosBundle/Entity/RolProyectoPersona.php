<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Rol;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\RolProyectoPersonaRepository")
 * @ORM\Table(options={"comment":"Roles de las Personas en los Proyectos"})
 */
class RolProyectoPersona extends Rol
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\ProyectoPersona", mappedBy="rolProyectoPersona")
     */
    private $proyectoPersona;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proyectoPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add proyectoPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $proyectoPersona
     * @return RolProyectoPersona
     */
    public function addProyectoPersona(\cobe\CurriculosBundle\Entity\ProyectoPersona $proyectoPersona)
    {
        $this->proyectoPersona[] = $proyectoPersona;

        return $this;
    }

    /**
     * Remove proyectoPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $proyectoPersona
     */
    public function removeProyectoPersona(\cobe\CurriculosBundle\Entity\ProyectoPersona $proyectoPersona)
    {
        $this->proyectoPersona->removeElement($proyectoPersona);
    }

    /**
     * Get proyectoPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectoPersona()
    {
        return $this->proyectoPersona;
    }
}
