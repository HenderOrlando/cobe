<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\InteresRepository")
 * @ORM\Table(options={"comment":"Intereses en el sistema"})
 */
class Interes extends Etiqueta
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaInteres", mappedBy="interes")
     */
    private $estadisticasInteres;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", mappedBy="intereses")
     */
    private $empresas;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Persona", mappedBy="intereses", cascade={"all"})
     */
    private $personas;

    /**
     * @ORM\ManyToMany(targetEntity="cobe\GruposBundle\Entity\Grupo", mappedBy="interesesGrupo")
     */
    private $gruposInteres;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estadisticasInteres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gruposInteres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres
     * @return Interes
     */
    public function addEstadisticasIntere(\cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres)
    {
        $this->estadisticasInteres[] = $estadisticasInteres;

        return $this;
    }

    /**
     * Remove estadisticasInteres
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres
     */
    public function removeEstadisticasIntere(\cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres)
    {
        $this->estadisticasInteres->removeElement($estadisticasInteres);
    }

    /**
     * Get estadisticasInteres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasInteres()
    {
        return $this->estadisticasInteres;
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
     * @param \cobe\GruposBundle\Entity\Grupo $gruposInteres
     * @return Interes
     */
    public function addGruposIntere(\cobe\GruposBundle\Entity\Grupo $gruposInteres)
    {
        $this->gruposInteres[] = $gruposInteres;

        return $this;
    }

    /**
     * Remove gruposInteres
     *
     * @param \cobe\GruposBundle\Entity\Grupo $gruposInteres
     */
    public function removeGruposIntere(\cobe\GruposBundle\Entity\Grupo $gruposInteres)
    {
        $this->gruposInteres->removeElement($gruposInteres);
    }

    /**
     * Get gruposInteres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGruposInteres()
    {
        return $this->gruposInteres;
    }
}
