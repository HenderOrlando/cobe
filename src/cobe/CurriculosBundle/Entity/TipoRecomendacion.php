<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\TipoRecomendacionRepository")
 * @ORM\Table(options={"comment":"Tipos de Recomendaciones"})
 */
class TipoRecomendacion extends Tipo
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Recomendacion", mappedBy="tipo")
     */
    private $recomendaciones;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->recomendaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Add recomendaciones
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones
     * @return TipoRecomendacion
     */
    public function addRecomendacione(\cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones)
    {
        $this->recomendaciones[] = $recomendaciones;

        return $this;
    }

    /**
     * Remove recomendaciones
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones
     */
    public function removeRecomendacione(\cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones)
    {
        $this->recomendaciones->removeElement($recomendaciones);
    }

    /**
     * Get recomendaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecomendaciones()
    {
        return $this->recomendaciones;
    }
}
