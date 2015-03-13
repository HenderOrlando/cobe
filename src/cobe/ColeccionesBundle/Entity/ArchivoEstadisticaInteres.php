<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;

/**
 * @ORM\Entity
 */
class ArchivoEstadisticaInteres extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaInteres", inversedBy="archivosEstadisticaInteres")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaInteres;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaInteres
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticaInteres
     * @return ArchivoEstadisticaInteres
     */
    public function setEstadisticaInteres(\cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticaInteres)
    {
        $this->estadisticaInteres = $estadisticaInteres;

        return $this;
    }

    /**
     * Get estadisticaInteres
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaInteres 
     */
    public function getEstadisticaInteres()
    {
        return $this->estadisticaInteres;
    }
}
