<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_oferta_laboral", columns={"estadistica","ofertaLaboral"})}, options={"comment":"Estadísticas de una Oferta Laboral"})
 */
class EstadisticaOfertaLaboral
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
     * @ORM\OneToMany(
     *     targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOfertaLaboral",
     *     mappedBy="estadisticaOfertaLaboral"
     * )
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasOfertaLaboral")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", inversedBy="estadisticas")
     * @ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)
     */
    private $ofertaLaboral;
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
     * @return EstadisticaOfertaLaboral
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
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOfertaLaboral $archivos
     * @return EstadisticaOfertaLaboral
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOfertaLaboral $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOfertaLaboral $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOfertaLaboral $archivos)
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
     * @return EstadisticaOfertaLaboral
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
     * Set ofertaLaboral
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertaLaboral
     * @return EstadisticaOfertaLaboral
     */
    public function setOfertaLaboral(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertaLaboral)
    {
        $this->ofertaLaboral = $ofertaLaboral;

        return $this;
    }

    /**
     * Get ofertaLaboral
     *
     * @return \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral 
     */
    public function getOfertaLaboral()
    {
        return $this->ofertaLaboral;
    }
}
