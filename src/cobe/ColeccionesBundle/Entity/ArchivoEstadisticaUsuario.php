<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;

/**
 * @ORM\Entity
 */
class ArchivoEstadisticaUsuario extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaUsuario", inversedBy="archivos")
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
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadistica
     * @return ArchivoEstadisticaUsuario
     */
    public function setEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadistica)
    {
        $this->estadistica = $estadistica;

        return $this;
    }

    /**
     * Get estadistica
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaUsuario 
     */
    public function getEstadistica()
    {
        return $this->estadistica;
    }
}
