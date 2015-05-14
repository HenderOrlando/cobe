<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\PaginasBundle\Entity\Plantilla;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\PlantillaGrupoRepository")
 * @ORM\Table(options={"comment":"Plantillas para los Grupos"})
 */
class PlantillaGrupo extends Plantilla
{
    /**
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\Grupo", mappedBy="plantilla")
     */
    private $grupos;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add grupos
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupos
     * @return PlantillaGrupo
     */
    public function addGrupo(\cobe\GruposBundle\Entity\Grupo $grupos)
    {
        $this->grupos[] = $grupos;

        return $this;
    }

    /**
     * Remove grupos
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupos
     */
    public function removeGrupo(\cobe\GruposBundle\Entity\Grupo $grupos)
    {
        $this->grupos->removeElement($grupos);
    }

    /**
     * Get grupos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

}
