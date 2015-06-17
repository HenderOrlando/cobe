<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\UsuariosBundle\Entity\Persona;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\EstudianteRepository")
 * @ORM\Table(options={"comment":"Estudiantes en el sistema"})
 */
class Estudiante extends Persona
{
    /**
     * @ORM\Column(type="string", length=20, nullable=false, options={"comment":"Código del estudiante"})
     */
    private $codigo;

    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha del grado"})
     */
    private $fechaGrado;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\CentroEstudio", inversedBy="estudiantes")
     * @ORM\JoinColumn(name="centroEstudio", referencedColumnName="id", nullable=true)
     */
    private $centroEstudio;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante", mappedBy="estudiante")
     */
    private $estadisticasEstudiante;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaEstudiante", inversedBy="estudiantes")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantillaEstudiante;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="estudiantes")
     * @ORM\JoinTable(
     *     name="Etiqueta2Estudiante",
     *     joinColumns={@ORM\JoinColumn(name="estudiante", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    private $etiquetas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->representantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasEstudiante = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estadisticasEstudiante
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante
     * @return Estudiante
     */
    public function addEstadisticasEstudiante(\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante)
    {
        $this->estadisticasEstudiante[] = $estadisticasEstudiante;

        return $this;
    }

    /**
     * Remove estadisticasEstudiante
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante
     */
    public function removeEstadisticasEstudiante(\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante)
    {
        $this->estadisticasEstudiante->removeElement($estadisticasEstudiante);
    }

    /**
     * Get estadisticasEstudiante
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstadisticasEstudiante()
    {
        return $this->estadisticasEstudiante;
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
