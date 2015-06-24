<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="estadistica_empresa", columns={"estadistica","empresa"})})
 */
class EstadisticaEmpresa
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
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEmpresa", mappedBy="estadisticaEmpresa")
     */
    private $archivosEstadisticaEmpresa;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", inversedBy="estadisticasEmpresa")
     * @ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)
     */
    private $estadistica;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", inversedBy="estadisticasEmpresa")
     * @ORM\JoinColumn(name="empresa", referencedColumnName="id", nullable=false)
     */
    private $empresa;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosEstadisticaEmpresa = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadisticaEmpresa
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
     * Add archivosEstadisticaEmpresa
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEmpresa $archivosEstadisticaEmpresa
     * @return EstadisticaEmpresa
     */
    public function addArchivosEstadisticaEmpresa(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEmpresa $archivosEstadisticaEmpresa)
    {
        $this->archivosEstadisticaEmpresa[] = $archivosEstadisticaEmpresa;

        return $this;
    }

    /**
     * Remove archivosEstadisticaEmpresa
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEmpresa $archivosEstadisticaEmpresa
     */
    public function removeArchivosEstadisticaEmpresa(\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEmpresa $archivosEstadisticaEmpresa)
    {
        $this->archivosEstadisticaEmpresa->removeElement($archivosEstadisticaEmpresa);
    }

    /**
     * Get archivosEstadisticaEmpresa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosEstadisticaEmpresa()
    {
        return $this->archivosEstadisticaEmpresa;
    }

    /**
     * Set estadistica
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadistica
     * @return EstadisticaEmpresa
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
     * Set empresa
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresa
     * @return EstadisticaEmpresa
     */
    public function setEmpresa(\cobe\UsuariosBundle\Entity\Empresa $empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \cobe\UsuariosBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
}
