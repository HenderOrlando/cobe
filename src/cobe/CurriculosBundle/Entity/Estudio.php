<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\EstudioRepository")
 * @ORM\Table(
 *     options={"comment":"Estudios de un Centro de Estudio"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="titulacion_centro_estudio", columns={"centroEstudio","titulacion"})}
 * )
 */
class Estudio extends Objeto
{
    /**
     * @ORM\Column(
     *     type="string",
     *     length=140,
     *     nullable=false,
     *     options={"comment":"Nombre del Título obtenido al terminar el Estudio"}
     * )
     */
    private $titulacion;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\EstudioPersona", mappedBy="estudio")
     */
    private $personas;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoEstudio", mappedBy="estudio")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\CentroEstudio", inversedBy="estudios")
     * @ORM\JoinColumn(name="centroEstudio", referencedColumnName="id", nullable=false)
     */
    private $centroEstudio;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\TipoEstudio", inversedBy="estudios")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titulacion
     *
     * @param string $titulacion
     * @return Estudio
     */
    public function setTitulacion($titulacion)
    {
        $this->titulacion = $titulacion;

        return $this;
    }

    /**
     * Get titulacion
     *
     * @return string 
     */
    public function getTitulacion()
    {
        return $this->titulacion;
    }

    /**
     * Add estudioPersonas
     *
     * @param \cobe\CurriculosBundle\Entity\EstudioPersona $personas
     * @return Estudio
     */
    public function addPersonas(\cobe\CurriculosBundle\Entity\EstudioPersona $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove estudioPersonas
     *
     * @param \cobe\CurriculosBundle\Entity\EstudioPersona $personas
     */
    public function removePersonas(\cobe\CurriculosBundle\Entity\EstudioPersona $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get estudioPersonas
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
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstudio $archivos
     * @return Estudio
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoEstudio $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoEstudio $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoEstudio $archivos)
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
     * Set centroEstudio
     *
     * @param \cobe\CurriculosBundle\Entity\CentroEstudio $centroEstudio
     * @return Estudio
     */
    public function setCentroEstudio(\cobe\CurriculosBundle\Entity\CentroEstudio $centroEstudio)
    {
        $this->centroEstudio = $centroEstudio;

        return $this;
    }

    /**
     * Get centroEstudio
     *
     * @return \cobe\CurriculosBundle\Entity\CentroEstudio 
     */
    public function getCentroEstudio()
    {
        return $this->centroEstudio;
    }

    /**
     * Set tipo
     *
     * @param \cobe\CurriculosBundle\Entity\TipoEstudio $tipo
     * @return Estudio
     */
    public function setTipo(\cobe\CurriculosBundle\Entity\TipoEstudio $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\CurriculosBundle\Entity\TipoEstudio 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
