<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;

/**
 * @ORM\Entity
 */
class ArchivoEstadisticaGrupo extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaGrupo", inversedBy="archivosEstadisticaGrupo")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaGrupo;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaGrupo
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticaGrupo
     * @return ArchivoEstadisticaGrupo
     */
    public function setEstadisticaGrupo(\cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticaGrupo)
    {
        $this->estadisticaGrupo = $estadisticaGrupo;

        return $this;
    }

    /**
     * Get estadisticaGrupo
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaGrupo 
     */
    public function getEstadisticaGrupo()
    {
        return $this->estadisticaGrupo;
    }
}
