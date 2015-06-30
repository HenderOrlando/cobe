<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de una Opción"})
 */
class ArchivoEstadisticaOpcion extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaOpcion", inversedBy="archivos")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaOpcion;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaOpcion
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOpcion $estadisticaOpcion
     * @return ArchivoEstadisticaOpcion
     */
    public function setEstadisticaOpcion(\cobe\EstadisticasBundle\Entity\EstadisticaOpcion $estadisticaOpcion)
    {
        $this->estadisticaOpcion = $estadisticaOpcion;

        return $this;
    }

    /**
     * Get estadisticaOpcion
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaOpcion 
     */
    public function getEstadisticaOpcion()
    {
        return $this->estadisticaOpcion;
    }
}
