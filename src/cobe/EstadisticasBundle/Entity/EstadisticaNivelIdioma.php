<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Estadísticas de una NivelIdioma"})
 */
class EstadisticaNivelIdioma
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaNivelIdioma", mappedBy="estadisticaNivelIdioma")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasNivelIdioma")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\NivelIdioma", inversedBy="estadisticas")
     * @ORM\JoinColumn(name="aptitud", referencedColumnName="id", nullable=false)
     */
    private $nivelIdioma;
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
     * @return EstadisticaNivelIdioma
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
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaNivelIdioma $archivos
     * @return EstadisticaNivelIdioma
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaNivelIdioma $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaNivelIdioma $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaNivelIdioma $archivos)
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
     * @return EstadisticaNivelIdioma
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
     * @param \cobe\CurriculosBundle\Entity\NivelIdioma $nivelIdioma
     * @return EstadisticaNivelIdioma
     */
    public function setNivelIdioma(\cobe\CurriculosBundle\Entity\NivelIdioma $nivelIdioma)
    {
        $this->nivelIdioma = $nivelIdioma;

        return $this;
    }

    /**
     * Get aptitud
     *
     * @return \cobe\CurriculosBundle\Entity\NivelIdioma 
     */
    public function getNivelIdioma()
    {
        return $this->nivelIdioma;
    }
}
