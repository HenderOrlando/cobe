<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class ArchivoProyecto extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Proyecto", inversedBy="archivos")
     * @ORM\JoinColumn(name="proyecto", referencedColumnName="id", nullable=false)
     */
    private $proyecto;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set proyecto
     *
     * @param \cobe\CurriculosBundle\Entity\Proyecto $proyecto
     * @return ArchivoProyecto
     */
    public function setProyecto(\cobe\CurriculosBundle\Entity\Proyecto $proyecto)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \cobe\CurriculosBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
}
