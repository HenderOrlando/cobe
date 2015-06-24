<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class ArchivoEstadisticaMensaje extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(
     *     targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaMensaje",
     *     inversedBy="archivosEstadisticaMensaje"
     * )
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaMensaje;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaMensaje
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticaMensaje
     * @return ArchivoEstadisticaMensaje
     */
    public function setEstadisticaMensaje(\cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticaMensaje)
    {
        $this->estadisticaMensaje = $estadisticaMensaje;

        return $this;
    }

    /**
     * Get estadisticaMensaje
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaMensaje 
     */
    public function getEstadisticaMensaje()
    {
        return $this->estadisticaMensaje;
    }
}
