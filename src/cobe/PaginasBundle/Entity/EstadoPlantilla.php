<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\EstadoPlantillaRepository")
 * @ORM\Table(options={"comment":"Estados para las Plantillas"})
 */
class EstadoPlantilla extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Plantilla", mappedBy="estado")
     */
    private $plantillas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->plantillas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add plantillas
     *
     * @param \cobe\PaginasBundle\Entity\Plantilla $plantillas
     * @return EstadoPlantilla
     */
    public function addPlantilla(\cobe\PaginasBundle\Entity\Plantilla $plantillas)
    {
        $this->plantillas[] = $plantillas;

        return $this;
    }

    /**
     * Remove plantillas
     *
     * @param \cobe\PaginasBundle\Entity\Plantilla $plantillas
     */
    public function removePlantilla(\cobe\PaginasBundle\Entity\Plantilla $plantillas)
    {
        $this->plantillas->removeElement($plantillas);
    }

    /**
     * Get plantillas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlantillas()
    {
        return $this->plantillas;
    }

}
