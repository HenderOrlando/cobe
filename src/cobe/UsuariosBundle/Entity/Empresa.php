<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\UsuariosBundle\Entity\Persona;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\EmpresaRepository")
 * @ORM\Table(options={"comment":"Empresas en el sistema"})
 */
class Empresa extends Persona
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\RepresentanteEmpresa", mappedBy="empresa")
     */
    private $representantes;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa", mappedBy="empresa")
     */
    private $estadisticasEmpresa;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaEmpresa", inversedBy="empresas")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantillaEmpresa;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="empresas")
     * @ORM\JoinTable(
     *     name="Etiqueta2Empresa",
     *     joinColumns={@ORM\JoinColumn(name="empresa", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    protected $etiquetas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Ciudad", inversedBy="empresas")
     * @ORM\JoinTable(
     *     name="Ciudad2Empresa",
     *     joinColumns={@ORM\JoinColumn(name="empresa", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ciudad", referencedColumnName="id", nullable=false)}
     * )
     */
    private $ciudades;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Interes", inversedBy="empresas")
     * @ORM\JoinTable(
     *     name="Interes2Empresa",
     *     joinColumns={@ORM\JoinColumn(name="empresa", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="interes", referencedColumnName="id", nullable=false)}
     * )
     */
    private $intereses;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Proyecto", inversedBy="empresas")
     * @ORM\JoinTable(
     *     name="Proyecto2Empresa",
     *     joinColumns={@ORM\JoinColumn(name="empresa", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="proyecto", referencedColumnName="id", nullable=false)}
     * )
     */
    private $proyectos;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Reconocimiento", mappedBy="empresas")
     */
    private $reconocimientos;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->representantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasEmpresa = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ciudades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->intereses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reconocimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add representantes
     *
     * @param \cobe\UsuariosBundle\Entity\RepresentanteEmpresa $representantes
     * @return Empresa
     */
    public function addRepresentante(\cobe\UsuariosBundle\Entity\RepresentanteEmpresa $representantes)
    {
        $this->representantes[] = $representantes;

        return $this;
    }

    /**
     * Remove representantes
     *
     * @param \cobe\UsuariosBundle\Entity\RepresentanteEmpresa $representantes
     */
    public function removeRepresentante(\cobe\UsuariosBundle\Entity\RepresentanteEmpresa $representantes)
    {
        $this->representantes->removeElement($representantes);
    }

    /**
     * Get representantes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRepresentantes()
    {
        return $this->representantes;
    }

    /**
     * Add estadisticasEmpresa
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa
     * @return Empresa
     */
    public function addEstadisticasEmpresa(\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa)
    {
        $this->estadisticasEmpresa[] = $estadisticasEmpresa;

        return $this;
    }

    /**
     * Remove estadisticasEmpresa
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa
     */
    public function removeEstadisticasEmpresa(\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa)
    {
        $this->estadisticasEmpresa->removeElement($estadisticasEmpresa);
    }

    /**
     * Get estadisticasEmpresa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasEmpresa()
    {
        return $this->estadisticasEmpresa;
    }

    /**
     * Set plantillaEmpresa
     *
     * @param \cobe\PaginasBundle\Entity\PlantillaEmpresa $plantillaEmpresa
     * @return Empresa
     */
    public function setPlantillaEmpresa(\cobe\PaginasBundle\Entity\PlantillaEmpresa $plantillaEmpresa)
    {
        $this->plantillaEmpresa = $plantillaEmpresa;

        return $this;
    }

    /**
     * Get plantillaEmpresa
     *
     * @return \cobe\PaginasBundle\Entity\PlantillaEmpresa 
     */
    public function getPlantillaEmpresa()
    {
        return $this->plantillaEmpresa;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return Empresa
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

    /**
     * Add ciudades
     *
     * @param \cobe\CommonBundle\Entity\Ciudad $ciudades
     * @return Empresa
     */
    public function addCiudade(\cobe\CommonBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades[] = $ciudades;

        return $this;
    }

    /**
     * Remove ciudades
     *
     * @param \cobe\CommonBundle\Entity\Ciudad $ciudades
     */
    public function removeCiudade(\cobe\CommonBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades->removeElement($ciudades);
    }

    /**
     * Get ciudades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCiudades()
    {
        return $this->ciudades;
    }

    /**
     * Add intereses
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $intereses
     * @return Empresa
     */
    public function addInterese(\cobe\CurriculosBundle\Entity\Interes $intereses)
    {
        $this->intereses[] = $intereses;

        return $this;
    }

    /**
     * Remove intereses
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $intereses
     */
    public function removeInterese(\cobe\CurriculosBundle\Entity\Interes $intereses)
    {
        $this->intereses->removeElement($intereses);
    }

    /**
     * Get intereses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIntereses()
    {
        return $this->intereses;
    }

    /**
     * Add proyectos
     *
     * @param \cobe\CurriculosBundle\Entity\Proyecto $proyectos
     * @return Empresa
     */
    public function addProyecto(\cobe\CurriculosBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos[] = $proyectos;

        return $this;
    }

    /**
     * Remove proyectos
     *
     * @param \cobe\CurriculosBundle\Entity\Proyecto $proyectos
     */
    public function removeProyecto(\cobe\CurriculosBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos->removeElement($proyectos);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }

    /**
     * Add reconocimientos
     *
     * @param \cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos
     * @return Empresa
     */
    public function addReconocimiento(\cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos)
    {
        $this->reconocimientos[] = $reconocimientos;

        return $this;
    }

    /**
     * Remove reconocimientos
     *
     * @param \cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos
     */
    public function removeReconocimiento(\cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos)
    {
        $this->reconocimientos->removeElement($reconocimientos);
    }

    /**
     * Get reconocimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReconocimientos()
    {
        return $this->reconocimientos;
    }

}
