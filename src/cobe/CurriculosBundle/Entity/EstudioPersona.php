<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\EstudioPersonaRepository")
 * @ORM\Table(
 *     options={"comment":"Estudios realizados por la Persona"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="estudio_persona", columns={"estudio","persona"})}
 * )
 */
class EstudioPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"comment":"Fecha en que se asocia el Estudio a la Persona"})
     */
    private $fechaCreado;

    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha en que la Persona inicia el Estudio"})
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha en que la Persona termina el Estudio"})
     */
    private $fechaFinal;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstudioPersona", mappedBy="estudioPersona")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Estudio", inversedBy="personas")
     * @ORM\JoinColumn(name="estudio", referencedColumnName="id", nullable=false)
     */
    private $estudio;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="estudios")
     * @ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)
     */
    private $persona;
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
     * @return EstudioPersona
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return EstudioPersona
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
     * Set fechaFinal
     *
     * @param \DateTime $fechaFinal
     * @return EstudioPersona
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal
     *
     * @return \DateTime 
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstudioPersona $archivos
     * @return EstudioPersona
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstudioPersona $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstudioPersona $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoEstudioPersona $archivos)
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
     * Set estudio
     *
     * @param \cobe\CurriculosBundle\Entity\Estudio $estudio
     * @return EstudioPersona
     */
    public function setEstudio(\cobe\CurriculosBundle\Entity\Estudio $estudio)
    {
        $this->estudio = $estudio;

        return $this;
    }

    /**
     * Get estudio
     *
     * @return \cobe\CurriculosBundle\Entity\Estudio 
     */
    public function getEstudio()
    {
        return $this->estudio;
    }

    /**
     * Set persona
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $persona
     * @return EstudioPersona
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
}
