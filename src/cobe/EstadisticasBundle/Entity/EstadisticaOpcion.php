<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_opcion", columns={"estadistica","opcion"})}, options={"comment":"Estadísticas de una Opción"})
 */
class EstadisticaOpcion
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOpcion", mappedBy="estadisticaOpcion")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasOpcion")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\GruposBundle\Entity\Opcion", inversedBy="estadisticas")
     * @ORM\JoinColumn(name="opcion", referencedColumnName="id", nullable=false)
     */
    private $opcion;
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
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOpcion $archivos
     * @return EstadisticaGrupo
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOpcion $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOpcion $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOpcion $archivos)
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
     * @param \cobe\GruposBundle\Entity\Opcion $opcion
     * @return EstadisticaOpcion
     */
    public function setopcion(\cobe\GruposBundle\Entity\Opcion $opcion)
    {
        $this->opcion = $opcion;

        return $this;
    }

    /**
     * Get opcion
     *
     * @return \cobe\GruposBundle\Entity\Opcion
     */
    public function getOpcion()
    {
        return $this->opcion;
    }
}
