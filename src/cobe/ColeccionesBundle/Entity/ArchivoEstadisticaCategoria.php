<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de una Categoria"})
 */
class ArchivoEstadisticaCategoria extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaCategoria", inversedBy="archivos")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaCategoria;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaCategoria
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaCategoria $estadisticaCategoria
     * @return ArchivoEstadisticaCategoria
     */
    public function setEstadisticaCategoria(\cobe\EstadisticasBundle\Entity\EstadisticaCategoria $estadisticaCategoria)
    {
        $this->estadisticaCategoria = $estadisticaCategoria;

        return $this;
    }

    /**
     * Get estadisticaCategoria
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaCategoria 
     */
    public function getEstadisticaCategoria()
    {
        return $this->estadisticaCategoria;
    }
}
