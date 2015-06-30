<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_estudiante", columns={"estadistica","estudiante"})}, options={"comment":"Estadísticas de un Estudiante"})
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
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante", mappedBy="estadisticaEstudiante")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasEstudiante")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Estudiante", inversedBy="estadisticas")
     * @ORM\JoinColumn(name="estudiante", referencedColumnName="id", nullable=false)
     */
    private $estudiante;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivos
     * @return EstadisticaEstudiante
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEstudiante $archivos)
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
