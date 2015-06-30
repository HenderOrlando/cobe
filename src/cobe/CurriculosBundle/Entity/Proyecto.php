<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\ProyectoRepository")
 * @ORM\Table(options={"comment":"Proyectos donde participan las Empresas o las Personas"})
 */
class Proyecto extends Objeto
{
    /**
     * @ORM\Column(type="date", nullable=false, options={"comment":"Fecha de Inicio del Proyecto"})
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha de Fin del Proyecto"})
     */
    private $fechaFin;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\ProyectoPersona", mappedBy="proyecto")
     */
    private $personas;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoProyecto", mappedBy="proyecto")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\TipoProyecto", inversedBy="proyectos")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", mappedBy="proyectos")
     */
    private $empresas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Proyecto
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Proyecto
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Add proyecto
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $personas
     * @return Proyecto
     */
    public function addPersonas(\cobe\CurriculosBundle\Entity\ProyectoPersona $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove proyecto
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $personas
     */
    public function removePersonas(\cobe\CurriculosBundle\Entity\ProyectoPersona $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get proyectoPersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoProyecto $archivos
     * @return Proyecto
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoProyecto $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoProyecto $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoProyecto $archivos)
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
     * Set tipo
     *
     * @param \cobe\CurriculosBundle\Entity\TipoProyecto $tipo
     * @return Proyecto
     */
    public function setTipo(\cobe\CurriculosBundle\Entity\TipoProyecto $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\CurriculosBundle\Entity\TipoProyecto 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     * @return Proyecto
     */
    public function addEmpresas(\cobe\UsuariosBundle\Entity\Empresa $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     */
    public function removeEmpresas(\cobe\UsuariosBundle\Entity\Empresa $empresas)
    {
        $this->empresas->removeElement($empresas);
    }

    /**
     * Get empresas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }
}
