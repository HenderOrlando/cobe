<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\ProyectoPersonaRepository")
 * @ORM\Table(
 *     options={"comment":"Proyectos donde la Persona ha participado"},
 *     indexes={@ORM\Index(name="proyecto_persona", columns={"proyecto","persona"})}
 * )
 */
class ProyectoPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha en que entró al proyecto"})
     */
    private $fechaEntra;

    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha en que sale del proyecto"})
     */
    private $fechaSale;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Proyecto", inversedBy="proyectoPersonas")
     * @ORM\JoinColumn(name="proyecto", referencedColumnName="id", nullable=false)
     */
    private $proyecto;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="proyectosPersona")
     * @ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\RolProyectoPersona", inversedBy="proyectoPersona")
     * @ORM\JoinColumn(name="rolPersona", referencedColumnName="id")
     */
    private $rolProyectoPersona;

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
     * Set descripcion
     *
     * @param string $descripcion
     * @return ProyectoPersona
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaEntra
     *
     * @param \DateTime $fechaEntra
     * @return ProyectoPersona
     */
    public function setFechaEntra($fechaEntra)
    {
        $this->fechaEntra = $fechaEntra;

        return $this;
    }

    /**
     * Get fechaEntra
     *
     * @return \DateTime 
     */
    public function getFechaEntra()
    {
        return $this->fechaEntra;
    }

    /**
     * Set fechaSale
     *
     * @param \DateTime $fechaSale
     * @return ProyectoPersona
     */
    public function setFechaSale($fechaSale)
    {
        $this->fechaSale = $fechaSale;

        return $this;
    }

    /**
     * Get fechaSale
     *
     * @return \DateTime 
     */
    public function getFechaSale()
    {
        return $this->fechaSale;
    }

    /**
     * Set proyecto
     *
     * @param \cobe\CurriculosBundle\Entity\Proyecto $proyecto
     * @return ProyectoPersona
     */
    public function setProyecto(\cobe\CurriculosBundle\Entity\Proyecto $proyecto)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \cobe\CurriculosBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * Set persona
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $persona
     * @return ProyectoPersona
     */
    public function setPersona(\cobe\UsuariosBundle\Entity\Persona $persona)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \cobe\UsuariosBundle\Entity\Persona 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set rolProyectoPersona
     *
     * @param \cobe\CurriculosBundle\Entity\RolProyectoPersona $rolProyectoPersona
     * @return ProyectoPersona
     */
    public function setRolProyectoPersona(\cobe\CurriculosBundle\Entity\RolProyectoPersona $rolProyectoPersona = null)
    {
        $this->rolProyectoPersona = $rolProyectoPersona;

        return $this;
    }

    /**
     * Get rolProyectoPersona
     *
     * @return \cobe\CurriculosBundle\Entity\RolProyectoPersona 
     */
    public function getRolProyectoPersona()
    {
        return $this->rolProyectoPersona;
    }
}
