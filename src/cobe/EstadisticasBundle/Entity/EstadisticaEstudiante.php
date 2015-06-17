<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_estudiante", columns={"estadistica","estudiante"})})
 */
class EstadisticaEstudiante
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante", mappedBy="estadisticaEstudiante")
     */
    private $archivosEstadisticaEstudiante;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasEstudiante")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Estudiante", inversedBy="estadisticasEstudiante")
     * @ORM\JoinColumn(name="estudiante", referencedColumnName="id", nullable=false)
     */
    private $estudiante;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosEstadisticaEstudiante = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadisticaEstudiante
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
     * Add archivosEstadisticaEstudiante
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivosEstadisticaEstudiante
     * @return EstadisticaEstudiante
     */
    public function addArchivosEstadisticaEstudiante(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivosEstadisticaEstudiante)
    {
        $this->archivosEstadisticaEstudiante[] = $archivosEstadisticaEstudiante;

        return $this;
    }

    /**
     * Remove archivosEstadisticaEstudiante
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivosEstadisticaEstudiante
     */
    public function removeArchivosEstadisticaEstudiante(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivosEstadisticaEstudiante)
    {
        $this->archivosEstadisticaEstudiante->removeElement($archivosEstadisticaEstudiante);
    }

    /**
     * Get archivosEstadisticaEstudiante
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosEstadisticaEstudiante()
    {
        return $this->archivosEstadisticaEstudiante;
    }

    /**
     * Set estadistica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadistica
     * @return EstadisticaEstudiante
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
     * Set estudiante
     *
     * @param \cobe\UsuariosBundle\Entity\Estudiante $estudiante
     * @return EstadisticaEstudiante
     */
    public function setEstudiante(\cobe\UsuariosBundle\Entity\Estudiante $estudiante)
    {
        $this->estudiante = $estudiante;

        return $this;
    }

    /**
     * Get estudiante
     *
     * @return \cobe\UsuariosBundle\Entity\Estudiante 
     */
    public function getEstudiante()
    {
        return $this->estudiante;
    }
}
