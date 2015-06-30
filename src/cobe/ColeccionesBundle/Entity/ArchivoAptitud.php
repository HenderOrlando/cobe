<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivos de una Aptitud"})
 */
class ArchivoAptitud extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Aptitud", inversedBy="archivos")
     * @ORM\JoinColumn(name="aptitud", referencedColumnName="id", nullable=false)
     */
    private $aptitud;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set aptitud
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitud
     * @return ArchivoAptitud
     */
    public function setAptitud(\cobe\CurriculosBundle\Entity\Aptitud $aptitud)
    {
        $this->aptitud = $aptitud;

        return $this;
    }

    /**
     * Get aptitud
     *
     * @return \cobe\CurriculosBundle\Entity\Aptitud 
     */
    public function getAptitud()
    {
        return $this->aptitud;
    }
}
