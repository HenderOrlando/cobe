<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\InteresRepository")
 * @ORM\Table(options={"comment":"Intereses en el sistema"})
 */
class Interes extends Etiqueta
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaInteres", mappedBy="interes")
     */
    private $estadisticas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", mappedBy="intereses")
     */
    private $empresas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Persona", mappedBy="intereses")
     */
    private $personas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\GruposBundle\Entity\Grupo", mappedBy="intereses")
     */
    private $grupos;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estadisticasInteres
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticas
     * @return Interes
     */
    public function addEstadisticas($estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticasInteres
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticas
     */
    public function removeEstadisticas($estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticasInteres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
    }

    /**
     * Add empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     * @return Interes
     */
    public function addEmpresa(\cobe\UsuariosBundle\Entity\Empresa $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     */
    public function removeEmpresa(\cobe\UsuariosBundle\Entity\Empresa $empresas)
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

    /**
     * Add personas
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $personas
     * @return Interes
     */
    public function addPersona(\cobe\UsuariosBundle\Entity\Persona $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $personas
     */
    public function removePersona(\cobe\UsuariosBundle\Entity\Persona $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Add gruposInteres
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupos
     * @return Interes
     */
    public function addGrupos(\cobe\GruposBundle\Entity\Grupo $grupos)
    {
        $this->grupos[] = $grupos;

        return $this;
    }

    /**
     * Remove gruposInteres
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupos
     */
    public function removeGrupos(\cobe\GruposBundle\Entity\Grupo $grupos)
    {
        $this->grupos->removeElement($grupos);
    }

    /**
     * Get gruposInteres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrupos()
    {
        return $this->grupos;
    }
}
