<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;

/**
 * @ORM\Entity
 */
class EstadoVotacion extends Estado
{
    /**
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\Votacion", mappedBy="estado")
     */
    private $votaciones;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->votaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add votaciones
     *
     * @param \cobe\GruposBundle\Entity\Votacion $votaciones
     * @return EstadoVotacion
     */
    public function addVotacione(\cobe\GruposBundle\Entity\Votacion $votaciones)
    {
        $this->votaciones[] = $votaciones;

        return $this;
    }

    /**
     * Remove votaciones
     *
     * @param \cobe\GruposBundle\Entity\Votacion $votaciones
     */
    public function removeVotacione(\cobe\GruposBundle\Entity\Votacion $votaciones)
    {
        $this->votaciones->removeElement($votaciones);
    }

    /**
     * Get votaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotaciones()
    {
        return $this->votaciones;
    }
}
