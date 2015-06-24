<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class ArchivoEstadisticaAptitud extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaAptitud", inversedBy="archivosEstadisticaAptitud")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaAptitud;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaAptitud
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticaAptitud
     * @return ArchivoEstadisticaAptitud
     */
    public function setEstadisticaAptitud(\cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticaAptitud)
    {
        $this->estadisticaAptitud = $estadisticaAptitud;

        return $this;
    }

    /**
     * Get estadisticaAptitud
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaAptitud 
     */
    public function getEstadisticaAptitud()
    {
        return $this->estadisticaAptitud;
    }
}
