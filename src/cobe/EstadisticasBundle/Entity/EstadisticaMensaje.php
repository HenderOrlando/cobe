<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_mensaje", columns={"estadistica","mensaje"})})
 */
class EstadisticaMensaje
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaMensaje", mappedBy="estadisticaMensaje")
     */
    private $archivosEstadisticaMensaje;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasMensaje")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", inversedBy="estadisticasMensaje")
     * @ORM\JoinColumn(name="mensaje", referencedColumnName="id", nullable=false)
     */
    private $mensaje;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosEstadisticaMensaje = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadisticaMensaje
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
     * Add archivosEstadisticaMensaje
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaMensaje $archivosEstadisticaMensaje
     * @return EstadisticaMensaje
     */
    public function addArchivosEstadisticaMensaje(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaMensaje $archivosEstadisticaMensaje)
    {
        $this->archivosEstadisticaMensaje[] = $archivosEstadisticaMensaje;

        return $this;
    }

    /**
     * Remove archivosEstadisticaMensaje
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaMensaje $archivosEstadisticaMensaje
     */
    public function removeArchivosEstadisticaMensaje(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaMensaje $archivosEstadisticaMensaje)
    {
        $this->archivosEstadisticaMensaje->removeElement($archivosEstadisticaMensaje);
    }

    /**
     * Get archivosEstadisticaMensaje
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosEstadisticaMensaje()
    {
        return $this->archivosEstadisticaMensaje;
    }

    /**
     * Set estadistica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadistica
     * @return EstadisticaMensaje
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
     * Set mensaje
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensaje
     * @return EstadisticaMensaje
     */
    public function setMensaje(\cobe\MensajesBundle\Entity\Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return \cobe\MensajesBundle\Entity\Mensaje 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }
}
