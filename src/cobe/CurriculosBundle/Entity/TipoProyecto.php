<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\TipoProyectoRepository")
 * @ORM\Table(options={"comment":"Tipos de Proyectos"})
 */
class TipoProyecto extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Proyecto", mappedBy="tipo")
     */
    private $proyectos;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add proyectos
     *
     * @param \cobe\CurriculosBundle\Entity\Proyecto $proyectos
     * @return TipoProyecto
     */
    public function addProyectos(\cobe\CurriculosBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos[] = $proyectos;

        return $this;
    }

    /**
     * Remove proyectos
     *
     * @param \cobe\CurriculosBundle\Entity\Proyecto $proyectos
     */
    public function removeProyectos(\cobe\CurriculosBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos->removeElement($proyectos);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }
}
