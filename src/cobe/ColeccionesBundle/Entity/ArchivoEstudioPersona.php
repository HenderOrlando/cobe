<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo que acredita un Estudio realizado por una Persona"})
 */
class ArchivoEstudioPersona extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\EstudioPersona", inversedBy="archivos")
     * @ORM\JoinColumn(name="estudioPersona", referencedColumnName="id", nullable=false)
     */
    private $estudioPersona;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set estudioPersona
     *
     * @param \cobe\CurriculosBundle\Entity\EstudioPersona $estudioPersona
     * @return ArchivoEstudioPersona
     */
    public function setEstudioPersona(\cobe\CurriculosBundle\Entity\EstudioPersona $estudioPersona)
    {
        $this->estudioPersona = $estudioPersona;

        return $this;
    }

    /**
     * Get estudioPersona
     *
     * @return \cobe\CurriculosBundle\Entity\EstudioPersona 
     */
    public function getEstudioPersona()
    {
        return $this->estudioPersona;
    }
}
