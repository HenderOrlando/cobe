<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;

/**
 * @ORM\Entity
 */
class ArchivoRecomendacion extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Recomendacion", inversedBy="archivos")
     * @ORM\JoinColumn(name="recomendacion", referencedColumnName="id", nullable=false)
     */
    private $recomendacion;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set recomendacion
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendacion
     * @return ArchivoRecomendacion
     */
    public function setRecomendacion(\cobe\CurriculosBundle\Entity\Recomendacion $recomendacion)
    {
        $this->recomendacion = $recomendacion;

        return $this;
    }

    /**
     * Get recomendacion
     *
     * @return \cobe\CurriculosBundle\Entity\Recomendacion 
     */
    public function getRecomendacion()
    {
        return $this->recomendacion;
    }
}
