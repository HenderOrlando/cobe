<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de un Estudiante"})
 */
class ArchivoEstadisticaEstudiante extends Archivo
{
    /**
     * @ORM\ManyToOne(
     *     targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante",
     *     inversedBy="archivos"
     * )
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaEstudiante;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaEstudiante
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticaEstudiante
     * @return ArchivoEstadisticaEstudiante
     */
    public function setEstadisticaEstudiante(\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticaEstudiante)
    {
        $this->estadisticaEstudiante = $estadisticaEstudiante;

        return $this;
    }

    /**
     * Get estadisticaEstudiante
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante 
     */
    public function getEstadisticaEstudiante()
    {
        return $this->estadisticaEstudiante;
    }
}
