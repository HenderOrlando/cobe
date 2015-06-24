<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class ArchivoEstadisticaOfertaLaboral extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(
     *     targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral",
     *     inversedBy="archivosEstadisticaOfertaLaboral"
     * )
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaOfertaLaboral;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaOfertaLaboral
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticaOfertaLaboral
     * @return ArchivoEstadisticaOfertaLaboral
     */
    public function setEstadisticaOfertaLaboral(\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticaOfertaLaboral)
    {
        $this->estadisticaOfertaLaboral = $estadisticaOfertaLaboral;

        return $this;
    }

    /**
     * Get estadisticaOfertaLaboral
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral 
     */
    public function getEstadisticaOfertaLaboral()
    {
        return $this->estadisticaOfertaLaboral;
    }
}
