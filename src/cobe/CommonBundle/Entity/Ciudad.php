<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\CiudadRepository")
 * @ORM\Table(options={"comment":"Ciudades"})
 */
class Ciudad extends Obj
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Persona", mappedBy="ciudad")
     */
    private $personas;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CommonBundle\Entity\Pais", inversedBy="ciudades")
     * @ORM\JoinColumn(name="pais", referencedColumnName="id", nullable=false)
     */
    private $pais;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", mappedBy="ciudades")
     */
    private $empresas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="ciudades")
     */
    private $ofertasLaborales;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add personas
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $personas
     * @return Ciudad
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
     * Set pais
     *
     * @param \cobe\CommonBundle\Entity\Pais $pais
     * @return Ciudad
     */
    public function setPais(\cobe\CommonBundle\Entity\Pais $pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return \cobe\CommonBundle\Entity\Pais 
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Add empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     * @return Ciudad
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
     * Add ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     * @return Ciudad
     */
    public function addOfertasLaborale(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales[] = $ofertasLaborales;

        return $this;
    }

    /**
     * Remove ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     */
    public function removeOfertasLaborale(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales->removeElement($ofertasLaborales);
    }

    /**
     *
     * Get ofertasLaborales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOfertasLaborales()
    {
        return $this->ofertasLaborales;
    }
}
