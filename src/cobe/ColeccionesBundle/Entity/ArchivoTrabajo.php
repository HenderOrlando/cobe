<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class ArchivoTrabajo extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", inversedBy="archivos")
     * @ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)
     */
    private $ofertaLaboral;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set ofertaLaboral
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertaLaboral
     * @return ArchivoTrabajo
     */
    public function setOfertaLaboral(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertaLaboral)
    {
        $this->ofertaLaboral = $ofertaLaboral;

        return $this;
    }

    /**
     * Get ofertaLaboral
     *
     * @return \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral 
     */
    public function getOfertaLaboral()
    {
        return $this->ofertaLaboral;
    }
}
