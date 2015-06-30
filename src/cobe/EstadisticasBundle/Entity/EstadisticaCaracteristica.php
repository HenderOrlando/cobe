<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Estadísticas de una Caracteristica"})
 */
class EstadisticaCaracteristica
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaCaracteristica", mappedBy="estadisticaCaracteristica")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasCaracteristica")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Caracteristica", inversedBy="estadisticas")
     * @ORM\JoinColumn(name="aptitud", referencedColumnName="id", nullable=false)
     */
    private $caracteristica;
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
     * @return EstadisticaCaracteristica
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
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaCaracteristica $archivos
     * @return EstadisticaCaracteristica
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaCaracteristica $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaCaracteristica $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaCaracteristica $archivos)
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
     * @return EstadisticaCaracteristica
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
     * @param \cobe\EstadisticasBundle\Entity\Caracteristica $caracteristica
     * @return EstadisticaCaracteristica
     */
    public function setCaracteristica(\cobe\EstadisticasBundle\Entity\Caracteristica $caracteristica)
    {
        $this->caracteristica = $caracteristica;

        return $this;
    }

    /**
     * Get aptitud
     *
     * @return \cobe\EstadisticasBundle\Entity\Caracteristica 
     */
    public function getCaracteristica()
    {
        return $this->caracteristica;
    }
}
