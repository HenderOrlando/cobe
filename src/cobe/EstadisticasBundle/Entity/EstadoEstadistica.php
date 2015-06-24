<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class EstadoEstadistica extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", mappedBy="estado")
     */
    private $estadisticasEstado;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticasEstado = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estadisticasEstado
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEstado
     * @return EstadoEstadistica
     */
    public function addEstadisticasEstado(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEstado)
    {
        $this->estadisticasEstado[] = $estadisticasEstado;

        return $this;
    }

    /**
     * Remove estadisticasEstado
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEstado
     */
    public function removeEstadisticasEstado(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEstado)
    {
        $this->estadisticasEstado->removeElement($estadisticasEstado);
    }

    /**
     * Get estadisticasEstado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasEstado()
    {
        return $this->estadisticasEstado;
    }
}
