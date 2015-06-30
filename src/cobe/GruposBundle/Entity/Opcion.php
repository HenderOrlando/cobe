<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\EstadisticasBundle\Repository\OpcionRepository")
 * @ORM\Table(options={"comment":"Opciones para las votaciones"})
 */
class Opcion extends Etiqueta
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaOpcion", mappedBy="opcion")
     */
    private $estadisticas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\GruposBundle\Entity\Votacion", mappedBy="opciones")
     */
    private $votaciones;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\GruposBundle\Entity\VotacionGrupoPersona", mappedBy="seleccionados")
     */
    private $selecciones;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->selecciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOpcion $estadisticas
     * @return Opcion
     */
    public function addEstadisticas($estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOpcion $estadisticas
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
     * Add votaciones
     *
     * @param \cobe\GruposBundle\Entity\Votacion $votaciones
     * @return Opcion
     */
    public function addVotaciones(\cobe\GruposBundle\Entity\Votacion $votaciones)
    {
        $this->votaciones[] = $votaciones;
    
        return $this;
    }
    
    /**
     * Remove votaciones
     *
     * @param \cobe\GruposBundle\Entity\Votacion $votaciones
     */
    public function removeVotaciones(\cobe\GruposBundle\Entity\Votacion $votaciones)
    {
        $this->votaciones->removeElement($votaciones);
    }
    
    /**
     * Get votaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotaciones()
    {
        return $this->votaciones;
    }
    
    /**
     * Add selecciones
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $selecciones
     * @return Opcion
     */
    public function addSelecciones(\cobe\GruposBundle\Entity\VotacionGrupoPersona $selecciones)
    {
        $this->selecciones[] = $selecciones;
    
        return $this;
    }
    
    /**
     * Remove selecciones
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $selecciones
     */
    public function removeSelecciones(\cobe\GruposBundle\Entity\VotacionGrupoPersona $selecciones)
    {
        $this->selecciones->removeElement($selecciones);
    }
    
    /**
     * Get selecciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSelecciones()
    {
        return $this->selecciones;
    }
}
