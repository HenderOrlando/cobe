<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class ArchivoPlantilla extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Plantilla", inversedBy="archivos")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=false)
     */
    private $plantilla;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set plantilla
     *
     * @param \cobe\PaginasBundle\Entity\Plantilla $plantilla
     * @return ArchivoPlantilla
     */
    public function setPlantilla(\cobe\PaginasBundle\Entity\Plantilla $plantilla)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \cobe\PaginasBundle\Entity\Plantilla 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }
}
