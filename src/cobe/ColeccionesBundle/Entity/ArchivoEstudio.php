<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;

/**
 * @ORM\Entity
 */
class ArchivoEstudio extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Estudio", inversedBy="archivos")
     * @ORM\JoinColumn(name="estudio", referencedColumnName="id", nullable=false)
     */
    private $estudio;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estudio
     *
     * @param \cobe\CurriculosBundle\Entity\Estudio $estudio
     * @return ArchivoEstudio
     */
    public function setEstudio(\cobe\CurriculosBundle\Entity\Estudio $estudio)
    {
        $this->estudio = $estudio;

        return $this;
    }

    /**
     * Get estudio
     *
     * @return \cobe\CurriculosBundle\Entity\Estudio 
     */
    public function getEstudio()
    {
        return $this->estudio;
    }
}
