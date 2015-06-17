<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\CentroEstudioRepository")
 * @ORM\Table(options={"comment":"Centros de Estudio de las Personas"})
 */
class CentroEstudio extends Objeto
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Estudio", mappedBy="centroEstudio")
     */
    private $estudios;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoCentroEstudio", mappedBy="centroEstudio")
     */
    private $archivos;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Estudiante", mappedBy="centroEstudio")
     */
    private $estudiantes;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estudios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estudios
     *
     * @param \cobe\CurriculosBundle\Entity\Estudio $estudios
     * @return CentroEstudio
     */
    public function addEstudio(\cobe\CurriculosBundle\Entity\Estudio $estudios)
    {
        $this->estudios[] = $estudios;

        return $this;
    }

    /**
     * Remove estudios
     *
     * @param \cobe\CurriculosBundle\Entity\Estudio $estudios
     */
    public function removeEstudio(\cobe\CurriculosBundle\Entity\Estudio $estudios)
    {
        $this->estudios->removeElement($estudios);
    }

    /**
     * Get estudios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudios()
    {
        return $this->estudios;
    }

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoCentroEstudio $archivos
     * @return CentroEstudio
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoCentroEstudio $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoCentroEstudio $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoCentroEstudio $archivos)
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
     * Add estudiantes
     *
     * @param \cobe\UsuariosBundle\Entity\Estudiante $estudiantes
     * @return CentroEstudio
     */
    public function addEstudiante(\cobe\UsuariosBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes[] = $estudiantes;

        return $this;
    }

    /**
     * Remove estudiantes
     *
     * @param \cobe\UsuariosBundle\Entity\Estudiante $estudiantes
     */
    public function removeEstudiante(\cobe\UsuariosBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes->removeElement($estudiantes);
    }
}
