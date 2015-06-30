<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivos de un Reconocimiento de una Persona"})
 */
class ArchivoReconocimientoPersona extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\ReconocimientoPersona", inversedBy="archivos")
     * @ORM\JoinColumn(name="reconocimientoPersona", referencedColumnName="id", nullable=false)
     */
    private $reconocimientoPersona;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set reconocimientoPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientoPersona
     * @return ArchivoReconocimientoPersona
     */
    public function setReconocimientoPersona(\cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientoPersona)
    {
        $this->reconocimientoPersona = $reconocimientoPersona;

        return $this;
    }

    /**
     * Get reconocimientoPersona
     *
     * @return \cobe\CurriculosBundle\Entity\ReconocimientoPersona 
     */
    public function getReconocimientoPersona()
    {
        return $this->reconocimientoPersona;
    }
}
