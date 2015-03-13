<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;

/**
 * @ORM\Entity
 */
class Caracteristica extends Etiqueta
{
    /**
     * @ORM\ManyToMany(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", mappedBy="caracteristicas")
     */
    private $estadisticasCaracteristica;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estadisticasCaracteristica = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add estadisticasCaracteristica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasCaracteristica
     * @return Caracteristica
     */
    public function addEstadisticasCaracteristica(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasCaracteristica)
    {
        $this->estadisticasCaracteristica[] = $estadisticasCaracteristica;

        return $this;
    }

    /**
     * Remove estadisticasCaracteristica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasCaracteristica
     */
    public function removeEstadisticasCaracteristica(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasCaracteristica)
    {
        $this->estadisticasCaracteristica->removeElement($estadisticasCaracteristica);
    }

    /**
     * Get estadisticasCaracteristica
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasCaracteristica()
    {
        return $this->estadisticasCaracteristica;
    }
}
