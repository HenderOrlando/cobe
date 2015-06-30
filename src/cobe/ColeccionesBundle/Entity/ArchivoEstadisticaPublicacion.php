<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de una Publicacion"})
 */
class ArchivoEstadisticaPublicacion extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion", inversedBy="archivos")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadistica
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadistica
     * @return ArchivoEstadisticaPublicacion
     */
    public function setEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadistica)
    {
        $this->estadistica = $estadistica;

        return $this;
    }

    /**
     * Get estadistica
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaPublicacion 
     */
    public function getEstadistica()
    {
        return $this->estadistica;
    }
}
