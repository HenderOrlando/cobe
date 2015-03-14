<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;

/**
 * @ORM\Entity
 */
class TipoEstadistica extends Tipo
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", mappedBy="tipo")
     */
    private $estadisticasTipo;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticasTipo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estadisticasTipo
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasTipo
     * @return TipoEstadistica
     */
    public function addEstadisticasTipo(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasTipo)
    {
        $this->estadisticasTipo[] = $estadisticasTipo;

        return $this;
    }

    /**
     * Remove estadisticasTipo
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasTipo
     */
    public function removeEstadisticasTipo(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasTipo)
    {
        $this->estadisticasTipo->removeElement($estadisticasTipo);
    }

    /**
     * Get estadisticasTipo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasTipo()
    {
        return $this->estadisticasTipo;
    }
}
