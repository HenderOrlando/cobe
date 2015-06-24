<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_interes", columns={"estadistica","interes"})})
 */
class EstadisticaInteres
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaInteres", mappedBy="estadisticaInteres")
     */
    private $archivosEstadisticaInteres;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasInteres")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Interes", inversedBy="estadisticasInteres")
     * @ORM\JoinColumn(name="interes", referencedColumnName="id", nullable=false)
     */
    private $interes;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosEstadisticaInteres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadisticaInteres
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
     * Add archivosEstadisticaInteres
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaInteres $archivosEstadisticaInteres
     * @return EstadisticaInteres
     */
    public function addArchivosEstadisticaIntere(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaInteres $archivosEstadisticaInteres)
    {
        $this->archivosEstadisticaInteres[] = $archivosEstadisticaInteres;

        return $this;
    }

    /**
     * Remove archivosEstadisticaInteres
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaInteres $archivosEstadisticaInteres
     */
    public function removeArchivosEstadisticaIntere(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaInteres $archivosEstadisticaInteres)
    {
        $this->archivosEstadisticaInteres->removeElement($archivosEstadisticaInteres);
    }

    /**
     * Get archivosEstadisticaInteres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosEstadisticaInteres()
    {
        return $this->archivosEstadisticaInteres;
    }

    /**
     * Set estadistica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadistica
     * @return EstadisticaInteres
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
     * Set interes
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $interes
     * @return EstadisticaInteres
     */
    public function setInteres(\cobe\CurriculosBundle\Entity\Interes $interes)
    {
        $this->interes = $interes;

        return $this;
    }

    /**
     * Get interes
     *
     * @return \cobe\CurriculosBundle\Entity\Interes 
     */
    public function getInteres()
    {
        return $this->interes;
    }
}
