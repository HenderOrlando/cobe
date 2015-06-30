<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de una Caracteristica"})
 */
class ArchivoEstadisticaCaracteristica extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaCaracteristica", inversedBy="archivos")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaCaracteristica;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaCaracteristica
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaCaracteristica $estadisticaCaracteristica
     * @return ArchivoEstadisticaCaracteristica
     */
    public function setEstadisticaCaracteristica(\cobe\EstadisticasBundle\Entity\EstadisticaCaracteristica $estadisticaCaracteristica)
    {
        $this->estadisticaCaracteristica = $estadisticaCaracteristica;

        return $this;
    }

    /**
     * Get estadisticaCaracteristica
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaCaracteristica 
     */
    public function getEstadisticaCaracteristica()
    {
        return $this->estadisticaCaracteristica;
    }
}
