<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\EstadisticasBundle\Repository\CaracteristicaRepository")
 * @ORM\Table(options={"comment":"Caracteristicas de las Estadísticas"})
 */
class Caracteristica extends Etiqueta
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaCaracteristica", mappedBy="caracteristica")
     */
    private $estadisticas;
    
    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", mappedBy="caracteristicas")
     */
    private $estadisticasUsos;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasUsos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaCaracteristica $estadisticas
     * @return Caracteristica
     */
    public function addEstadisticas($estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaCaracteristica $estadisticas
     */
    public function removeEstadisticas($estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
    }

    /**
     * Add estadisticasCaracteristica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticas
     * @return Caracteristica
     */
    public function addEstadisticasUsos($estadisticas)
    {
        $this->estadisticasUsos[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticasCaracteristica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticas
     */
    public function removeEstadisticasUsos($estadisticas)
    {
        $this->estadisticasUsos->removeElement($estadisticas);
    }

    /**
     * Get estadisticasCaracteristica
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasUsos()
    {
        return $this->estadisticasUsos;
    }
}
