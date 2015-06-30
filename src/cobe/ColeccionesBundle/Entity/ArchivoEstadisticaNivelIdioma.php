<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Estadística de una NivelIdioma"})
 */
class ArchivoEstadisticaNivelIdioma extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaNivelIdioma", inversedBy="archivos")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadisticaNivelIdioma;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estadisticaNivelIdioma
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaNivelIdioma $estadisticaNivelIdioma
     * @return ArchivoEstadisticaNivelIdioma
     */
    public function setEstadisticaNivelIdioma(\cobe\EstadisticasBundle\Entity\EstadisticaNivelIdioma $estadisticaNivelIdioma)
    {
        $this->estadisticaNivelIdioma = $estadisticaNivelIdioma;

        return $this;
    }

    /**
     * Get estadisticaNivelIdioma
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadisticaNivelIdioma 
     */
    public function getEstadisticaNivelIdioma()
    {
        return $this->estadisticaNivelIdioma;
    }
}
