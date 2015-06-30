<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\AptitudRepository")
 * @ORM\Table(options={"comment":"Aptitudes de una Persona"})
 */
class Aptitud extends Etiqueta
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoAptitud", mappedBy="aptitud")
     */
    private $archivos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaAptitud", mappedBy="aptitud")
     */
    private $estadisticas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Persona", mappedBy="aptitudes")
     */
    private $personas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="aptitudes")
     */
    private $ofertasLaboralesAptitud;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertasLaboralesAptitud = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivos
     * @return Aptitud
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * Add estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticas
     * @return Aptitud
     */
    public function addEstadisticas($estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticas
     */
    public function removeEstadisticas($estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticasAptitud
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
    }

    /**
     * Add personas
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $personas
     * @return Aptitud
     */
    public function addPersona(\cobe\UsuariosBundle\Entity\Persona $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $personas
     */
    public function removePersona(\cobe\UsuariosBundle\Entity\Persona $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Add ofertasLaboralesAptitud
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesAptitud
     * @return Aptitud
     */
    public function addOfertasLaboralesAptitud(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesAptitud)
    {
        $this->ofertasLaboralesAptitud[] = $ofertasLaboralesAptitud;

        return $this;
    }

    /**
     * Remove ofertasLaboralesAptitud
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesAptitud
     */
    public function removeOfertasLaboralesAptitud(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaboralesAptitud)
    {
        $this->ofertasLaboralesAptitud->removeElement($ofertasLaboralesAptitud);
    }

    /**
     * Get ofertasLaboralesAptitud
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertasLaboralesAptitud()
    {
        return $this->ofertasLaboralesAptitud;
    }
}
