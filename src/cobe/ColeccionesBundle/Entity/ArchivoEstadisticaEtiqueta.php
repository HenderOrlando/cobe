<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de una Etiqueta"})
 */
class ArchivoEstadisticaEtiqueta extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEtiqueta", inversedBy="archivos")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaEtiqueta;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaEtiqueta
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEtiqueta $estadisticaEtiqueta
     * @return ArchivoEstadisticaEtiqueta
     */
    public function setEstadisticaEtiqueta(\cobe\EstadisticasBundle\Entity\EstadisticaEtiqueta $estadisticaEtiqueta)
    {
        $this->estadisticaEtiqueta = $estadisticaEtiqueta;

        return $this;
    }

    /**
     * Get estadisticaEtiqueta
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaEtiqueta 
     */
    public function getEstadisticaEtiqueta()
    {
        return $this->estadisticaEtiqueta;
    }
}
