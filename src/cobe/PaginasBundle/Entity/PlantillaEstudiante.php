<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\PaginasBundle\Entity\Plantilla;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\PlantillaEstudianteRepository")
 * @ORM\Table(options={"comment":"Plantillas para Estudiantes"})
 */
class PlantillaEstudiante extends Plantilla
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Estudiante", mappedBy="plantillaEstudiante")
     */
    private $estudiantes;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estudiantes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estudiantes
     *
     * @param \cobe\UsuariosBundle\Entity\Estudiante $estudiantes
     * @return PlantillaEstudiante
     */
    public function addEstudiantes(\cobe\UsuariosBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes[] = $estudiantes;

        return $this;
    }

    /**
     * Remove estudiantes
     *
     * @param \cobe\UsuariosBundle\Entity\Estudiante $estudiantes
     */
    public function removeEstudiantes(\cobe\UsuariosBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes->removeElement($estudiantes);
    }

    /**
     * Get estudiantes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudiantes()
    {
        return $this->estudiantes;
    }


}
