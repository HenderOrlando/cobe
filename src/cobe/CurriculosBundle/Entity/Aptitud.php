<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\AptitudRepository")
 * @ORM\Table(options={"comment":"Aptitudes de una Persona"})
 */
class Aptitud extends Etiqueta
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoAptitud", mappedBy="aptitud")
     */
    private $archivosAptitud;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaAptitud", mappedBy="aptitud")
     */
    private $estadisticasAptitud;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Persona", mappedBy="aptitudes")
     */
    private $personas;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="aptitudes")
     */
    private $ofertasLaboralesAptitud;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->archivosAptitud = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasAptitud = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add archivosAptitud
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivosAptitud
     * @return Aptitud
     */
    public function addArchivosAptitud(\cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivosAptitud)
    {
        $this->archivosAptitud[] = $archivosAptitud;

        return $this;
    }

    /**
     * Remove archivosAptitud
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivosAptitud
     */
    public function removeArchivosAptitud(\cobe\ColeccionesBundle\Entity\ArchivoAptitud $archivosAptitud)
    {
        $this->archivosAptitud->removeElement($archivosAptitud);
    }

    /**
     * Get archivosAptitud
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosAptitud()
    {
        return $this->archivosAptitud;
    }

    /**
     * Add estadisticasAptitud
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud
     * @return Aptitud
     */
    public function addEstadisticasAptitud(\cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud)
    {
        $this->estadisticasAptitud[] = $estadisticasAptitud;

        return $this;
    }

    /**
     * Remove estadisticasAptitud
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud
     */
    public function removeEstadisticasAptitud(\cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud)
    {
        $this->estadisticasAptitud->removeElement($estadisticasAptitud);
    }

    /**
     * Get estadisticasAptitud
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasAptitud()
    {
        return $this->estadisticasAptitud;
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
