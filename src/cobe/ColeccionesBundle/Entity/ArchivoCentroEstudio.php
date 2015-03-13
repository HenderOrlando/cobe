<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;

/**
 * @ORM\Entity
 */
class ArchivoCentroEstudio extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\CentroEstudio", inversedBy="archivos")
     * @ORM\JoinColumn(name="centroEstudio", referencedColumnName="id", nullable=false)
     */
    private $centroEstudio;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set centroEstudio
     *
     * @param \cobe\CurriculosBundle\Entity\CentroEstudio $centroEstudio
     * @return ArchivoCentroEstudio
     */
    public function setCentroEstudio(\cobe\CurriculosBundle\Entity\CentroEstudio $centroEstudio)
    {
        $this->centroEstudio = $centroEstudio;

        return $this;
    }

    /**
     * Get centroEstudio
     *
     * @return \cobe\CurriculosBundle\Entity\CentroEstudio 
     */
    public function getCentroEstudio()
    {
        return $this->centroEstudio;
    }
}
