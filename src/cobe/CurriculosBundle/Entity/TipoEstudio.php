<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\TipoEstudioRepository")
 * @ORM\Table(options={"comment":"Tipos de Estudios (Pregrado,  Doctorado, Especialización, Maestría)"})
 */
class TipoEstudio extends Tipo
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Estudio", mappedBy="tipo")
     */
    private $estudios;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estudios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estudios
     *
     * @param \cobe\CurriculosBundle\Entity\Estudio $estudios
     * @return TipoEstudio
     */
    public function addEstudio(\cobe\CurriculosBundle\Entity\Estudio $estudios)
    {
        $this->estudios[] = $estudios;

        return $this;
    }

    /**
     * Remove estudios
     *
     * @param \cobe\CurriculosBundle\Entity\Estudio $estudios
     */
    public function removeEstudio(\cobe\CurriculosBundle\Entity\Estudio $estudios)
    {
        $this->estudios->removeElement($estudios);
    }

    /**
     * Get estudios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudios()
    {
        return $this->estudios;
    }
}
