<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de una Empresa"})
 */
class ArchivoEstadisticaEmpresa extends Archivo
{
    /**
     * @ORM\ManyToOne(
     *     targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa",
     *     inversedBy="archivos"
     * )
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaEmpresa;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaEmpresa
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticaEmpresa
     * @return ArchivoEstadisticaEmpresa
     */
    public function setEstadisticaEmpresa(\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticaEmpresa)
    {
        $this->estadisticaEmpresa = $estadisticaEmpresa;

        return $this;
    }

    /**
     * Get estadisticaEmpresa
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaEmpresa 
     */
    public function getEstadisticaEmpresa()
    {
        return $this->estadisticaEmpresa;
    }
}
