<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\UsuariosBundle\Entity\Persona;
use JMS\Serializer\Annotation\MaxDepth;
use cobe\CurriculosBundle\Entity\CentroEstudio;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\EstudianteRepository")
 * @ORM\Table(options={"comment":"Estudiantes en el sistema"})
 */
class Estudiante extends Persona
{
    /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=false, options={"comment":"Código del estudiante"})
     */
    private $codigo;

    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha del grado"})
     */
    private $fechaGrado;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\CentroEstudio", inversedBy="estudiantes")
     * @ORM\JoinColumn(name="centroEstudio", referencedColumnName="id", nullable=true)
     */
    private $centroEstudio;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante", mappedBy="estudiante")
     */
    private $estadisticas;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaEstudiante", inversedBy="estudiantes")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantillaEstudiante;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="estudiantes")
     * @ORM\JoinTable(
     *     name="etiqueta2estudiante",
     *     joinColumns={@ORM\JoinColumn(name="estudiante", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    protected $etiquetas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigo
     *
     * @param string $codigo
     * @return Estudiante
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set fechaGrado
     *
     * @param datetime $fechaGrado
     * @return Estudiante
     */
    public function setFechaGrado($fechaGrado)
    {
        $this->fechaGrado = $fechaGrado;

        return $this;
    }

    /**
     * Get fechaGrado
     *
     * @return datetime
     */
    public function getFechaGrado()
    {
        return $this->fechaGrado;
    }

    /**
     * Set centroEstudio
     *
     * @param datetime $centroEstudio
     * @return Estudiante
     */
    public function setCentroEstudio($centroEstudio)
    {
        $this->centroEstudio = $centroEstudio;

        return $this;
    }

    /**
     * Get centroEstudio
     *
     * @return /cobe/CentroEstudio
     */
    public function getCentroEstudio()
    {
        return $this->centroEstudio;
    }

    /**
     * Add estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticas
     * @return Estudiante
     */
    public function addEstadisticas(\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticas
     */
    public function removeEstadisticas(\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticasE
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
    }

    /**
     * Set plantillaEstudiante
     *
     * @param \cobe\PaginasBundle\Entity\PlantillaEstudiante $plantillaEstudiante
     * @return Estudiante
     */
    public function setPlantillaEstudiante(\cobe\PaginasBundle\Entity\PlantillaEstudiante $plantillaEstudiante)
    {
        $this->plantillaEstudiante = $plantillaEstudiante;

        return $this;
    }

    /**
     * Get plantillaEstudiante
     *
     * @return \cobe\PaginasBundle\Entity\PlantillaEstudiante
     */
    public function getPlantillaEstudiante()
    {
        return $this->plantillaEstudiante;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return Estudiante
     */
    public function addEtiqueta(\cobe\CommonBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas[] = $etiquetas;

        return $this;
    }

    /**
     * Remove etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     */
    public function removeEtiqueta(\cobe\CommonBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas->removeElement($etiquetas);
    }

    /**
     * Get etiquetas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtiquetas()
    {
        return $this->etiquetas;
    }

}
