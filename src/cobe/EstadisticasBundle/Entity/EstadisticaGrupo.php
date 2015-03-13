<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_grupo", columns={"estadistica","grupo"})})
 */
class EstadisticaGrupo
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaGrupo", mappedBy="estadisticaGrupo")
     */
    private $archivosEstadisticaGrupo;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasGrupo")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\Grupo", inversedBy="estadisticasGrupo")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)
     */
    private $grupo;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosEstadisticaGrupo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadisticaGrupo
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
     * Add archivosEstadisticaGrupo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaGrupo $archivosEstadisticaGrupo
     * @return EstadisticaGrupo
     */
    public function addArchivosEstadisticaGrupo(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaGrupo $archivosEstadisticaGrupo)
    {
        $this->archivosEstadisticaGrupo[] = $archivosEstadisticaGrupo;

        return $this;
    }

    /**
     * Remove archivosEstadisticaGrupo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaGrupo $archivosEstadisticaGrupo
     */
    public function removeArchivosEstadisticaGrupo(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaGrupo $archivosEstadisticaGrupo)
    {
        $this->archivosEstadisticaGrupo->removeElement($archivosEstadisticaGrupo);
    }

    /**
     * Get archivosEstadisticaGrupo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosEstadisticaGrupo()
    {
        return $this->archivosEstadisticaGrupo;
    }

    /**
     * Set estadistica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadistica
     * @return EstadisticaGrupo
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
     * Set grupo
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupo
     * @return EstadisticaGrupo
     */
    public function setGrupo(\cobe\GruposBundle\Entity\Grupo $grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \cobe\GruposBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
}
