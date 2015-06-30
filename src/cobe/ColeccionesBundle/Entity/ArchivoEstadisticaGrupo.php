<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de un Grupo"})
 */
class ArchivoEstadisticaGrupo extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaGrupo", inversedBy="archivos")
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
