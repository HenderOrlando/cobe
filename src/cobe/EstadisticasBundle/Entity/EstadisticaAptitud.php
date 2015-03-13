<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class EstadisticaAptitud
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $fechaCreado;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaAptitud", mappedBy="estadisticaAptitud")
     */
    private $archivosEstadisticaAptitud;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasAptitud")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @ORM\ManyToOne(targetEntity="cobe\CurriculosBundle\Entity\Aptitud", inversedBy="estadisticasAptitud")
     * @ORM\JoinColumn(name="aptitud", referencedColumnName="id", nullable=false)
     */
    private $aptitud;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosEstadisticaAptitud = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return guid 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return EstadisticaAptitud
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Add archivosEstadisticaAptitud
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaAptitud $archivosEstadisticaAptitud
     * @return EstadisticaAptitud
     */
    public function addArchivosEstadisticaAptitud(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaAptitud $archivosEstadisticaAptitud)
    {
        $this->archivosEstadisticaAptitud[] = $archivosEstadisticaAptitud;

        return $this;
    }

    /**
     * Remove archivosEstadisticaAptitud
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaAptitud $archivosEstadisticaAptitud
     */
    public function removeArchivosEstadisticaAptitud(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaAptitud $archivosEstadisticaAptitud)
    {
        $this->archivosEstadisticaAptitud->removeElement($archivosEstadisticaAptitud);
    }

    /**
     * Get archivosEstadisticaAptitud
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosEstadisticaAptitud()
    {
        return $this->archivosEstadisticaAptitud;
    }

    /**
     * Set estadistica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadistica
     * @return EstadisticaAptitud
     */
    public function setEstadistica(\cobe\EstadisticasBundle\Entity\Estadistica $estadistica)
    {
        $this->estadistica = $estadistica;

        return $this;
    }

    /**
     * Get estadistica
     *
     * @return \cobe\EstadisticasBundle\Entity\Estadistica 
     */
    public function getEstadistica()
    {
        return $this->estadistica;
    }

    /**
     * Set aptitud
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitud
     * @return EstadisticaAptitud
     */
    public function setAptitud(\cobe\CurriculosBundle\Entity\Aptitud $aptitud)
    {
        $this->aptitud = $aptitud;

        return $this;
    }

    /**
     * Get aptitud
     *
     * @return \cobe\CurriculosBundle\Entity\Aptitud 
     */
    public function getAptitud()
    {
        return $this->aptitud;
    }
}
