<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Tipo de una Estadística"})
 */
class TipoEstadistica extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", mappedBy="tipo")
     */
    private $estadisticas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticas
     * @return TipoEstadistica
     */
    public function addEstadisticas(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticas
     */
    public function removeEstadisticas(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasTipo()
    {
        return $this->estadisticas;
    }
}
