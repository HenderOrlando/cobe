<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Estado de una Votación"})
 */
class EstadoVotacion extends Estado
{
    /**
     * @MaxDepth(2)
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
    public function addVotaciones(\cobe\GruposBundle\Entity\Votacion $votaciones)
    {
        $this->votaciones[] = $votaciones;

        return $this;
    }

    /**
     * Remove votaciones
     *
     * @param \cobe\GruposBundle\Entity\Votacion $votaciones
     */
    public function removeVotaciones(\cobe\GruposBundle\Entity\Votacion $votaciones)
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
