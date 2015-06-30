<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\UsuariosBundle\Entity\Usuario;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\PersonaRepository")
 * @ORM\Table(
 *     options={"comment":"Personas en el sistema"},
 *     indexes={@ORM\Index(name="direccionCiudad", columns={"direccion","ciudad"})}
 * )
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="herencia", length=10, type="string")
 * @ORM\DiscriminatorMap({
 *  "Persona"="\cobe\UsuariosBundle\Entity\Persona",
 *  "Empresa"="\cobe\UsuariosBundle\Entity\Empresa",
 *  "Estudiante"="\cobe\UsuariosBundle\Entity\Estudiante"
 * })
 */
class Persona extends Usuario
{
    /**
     * @ORM\Column(type="date", nullable=true, options={"comment":"Fecha en que nace la Persona"})
     */
    private $fechaNace;
    /**
     * @ORM\Column(
     *     type="bigint",
     *     length=12,
     *     nullable=true,
     *     options={"comment":"Identificador de la Persona","unsigned":true}
     * )
     */
    private $doc_id;

    /**
     * @ORM\Column(type="string", length=140, nullable=true, options={"comment":"Dirección de la Persona"})
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=12, nullable=true, options={"comment":"Teléfono de la Persona","unsigned":true})
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"comment":"Nombres de la Persona"})
     */
    private $nombres;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"comment":"Apellidos de la Persona"})
     */
    private $apellidos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\RepresentanteEmpresa", mappedBy="persona")
     */
    private $empresas;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\EstudioPersona", mappedBy="persona")
     */
    private $estudios;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Recomendacion", mappedBy="recomienda")
     */
    private $recomendados;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Recomendacion", mappedBy="recomendado")
     */
    private $recomendaciones;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\IdiomaPersona", mappedBy="persona")
     */
    private $idiomas;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\ProyectoPersona", mappedBy="persona")
     */
    private $proyectos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\ReconocimientoPersona", mappedBy="persona")
     */
    private $reconocimientos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\GrupoPersona", mappedBy="persona")
     */
    private $gruposPersona;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona", mappedBy="persona")
     */
    private $ofertasLaboralesPersona;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="autor")
     */
    private $publicaciones;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CommonBundle\Entity\Ciudad", inversedBy="personas")
     * @ORM\JoinColumn(name="ciudad", referencedColumnName="id")
     */
    private $ciudad;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Interes", inversedBy="personas")
     * @ORM\JoinTable(
     *     name="Interes2Persona",
     *     joinColumns={@ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="interes", referencedColumnName="id", nullable=false)}
     * )
     */
    private $intereses;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Aptitud", inversedBy="personas")
     * @ORM\JoinTable(
     *     name="aptitud2persona",
     *     joinColumns={@ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="aptitud", referencedColumnName="id", nullable=false)}
     * )
     */
    private $aptitudes;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estudios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recomendados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recomendaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idiomas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reconocimientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gruposPersona = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertasLaboralesPersona = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->intereses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aptitudes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    public function setId($id){
        parent::setId($id);

        return $this;
    }

    /**
     * Set fechaNace
     *
     * @param \DateTime $fechaNace
     * @return Empresa
     */
    public function setFechaNace($fechaNace)
    {
        $this->fechaNace = $fechaNace;

        return $this;
    }

    /**
     * Get fechaNace
     *
     * @return \DateTime
     */
    public function getFechaNace()
    {
        return $this->fechaNace;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return Persona
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Persona
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set doc_id
     *
     * @param integer $docId
     * @return Persona
     */
    public function setDocId($docId)
    {
        $this->doc_id = $docId;

        return $this;
    }

    /**
     * Get doc_id
     *
     * @return integer 
     */
    public function getDocId()
    {
        return $this->doc_id;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Persona
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     * @return Persona
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return integer 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Add empresas
     *
     * @param \cobe\UsuariosBundle\Entity\RepresentanteEmpresa $empresas
     * @return Persona
     */
    public function addEmpresas(\cobe\UsuariosBundle\Entity\RepresentanteEmpresa $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \cobe\UsuariosBundle\Entity\RepresentanteEmpresa $empresas
     */
    public function removeEmpresas(\cobe\UsuariosBundle\Entity\RepresentanteEmpresa $empresas)
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
     * set empresas
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return Persona
     */
    public function setEmpresas($empresas)
    {
        if(is_array($empresas)){
            $this->removeAllEmpresas();
            foreach($empresas as $e){
                $this->addEmpresas($e);
            }
        }

        return $this;
    }

    /**
     * Remove All empresas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllEmpresas()
    {
        /*foreach($this->getEmpresas() as $et){
            $this->empresas->removeElement($et);
        }*/
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getEmpresas();
    }

    /**
     * Add estudios
     *
     * @param \cobe\CurriculosBundle\Entity\EstudioPersona $estudios
     * @return Persona
     */
    public function addEstudios(\cobe\CurriculosBundle\Entity\EstudioPersona $estudios)
    {
        $this->estudios[] = $estudios;

        return $this;
    }

    /**
     * Remove estudios
     *
     * @param \cobe\CurriculosBundle\Entity\EstudioPersona $estudios
     */
    public function removeEstudios(\cobe\CurriculosBundle\Entity\EstudioPersona $estudios)
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
     * set estudios
     *
     * @param \Doctrine\Common\Collections\Collection
     * @param \Doctrine\Common\Collections\Collection
     * @return Persona
     */
    public function setEstudios($estudios)
    {
        if(is_array($estudios)){
            $this->removeAllEstudios();
            foreach($estudios as $e){
                $this->addEstudios($e);
            }
        }

        return $this;
    }

    /**
     * Remove All estudios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllEstudios()
    {
        /*foreach($this->getEstudios() as $et){
            $this->estudios->removeElement($et);
        }*/
        $this->estudios = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getEstudios();
    }

    /**
     * Add recomendados
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendados
     * @return Persona
     */
    public function addRecomendados(\cobe\CurriculosBundle\Entity\Recomendacion $recomendados)
    {
        $this->recomendados[] = $recomendados;

        return $this;
    }

    /**
     * Remove recomendados
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendados
     */
    public function removeRecomendados(\cobe\CurriculosBundle\Entity\Recomendacion $recomendados)
    {
        $this->recomendados->removeElement($recomendados);
    }

    /**
     * Get recomendados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecomendados()
    {
        return $this->recomendados;
    }

    /**
     * Add recomendaciones
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones
     * @return Persona
     */
    public function addRecomendaciones(\cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones)
    {
        $this->recomendaciones[] = $recomendaciones;

        return $this;
    }

    /**
     * Remove recomendaciones
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones
     */
    public function removeRecomendaciones(\cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones)
    {
        $this->recomendaciones->removeElement($recomendaciones);
    }

    /**
     * Get recomendaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecomendaciones()
    {
        return $this->recomendaciones;
    }

    /**
     * Add idiomas
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomas
     * @return Persona
     */
    public function addIdiomas(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomas)
    {
        $this->idiomas[] = $idiomas;

        return $this;
    }

    /**
     * Remove idiomas
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomas
     */
    public function removeIdiomas(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomas)
    {
        $this->idiomas->removeElement($idiomas);
    }

    /**
     * Get idiomas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdiomas()
    {
        return $this->idiomas;
    }

    /**
     * set idiomas
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return Persona
     */
    public function setIdiomas($idiomas)
    {
        if(is_array($idiomas)){
            $this->removeAllIdiomas();
            foreach($idiomas as $e){
                $this->addIdiomas($e);
            }
        }

        return $this;
    }

    /**
     * Remove All idiomas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllIdiomas()
    {
        /*foreach($this->getIdiomas() as $et){
            $this->idiomas->removeElement($et);
        }*/
        $this->idiomas = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getIdiomas();
    }

    /**
     * Add proyectosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $proyectos
     * @return Persona
     */
    public function addProyectos(\cobe\CurriculosBundle\Entity\ProyectoPersona $proyectos)
    {
        $this->proyectos[] = $proyectos;

        return $this;
    }

    /**
     * Remove proyectosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $proyectos
     */
    public function removeProyectos(\cobe\CurriculosBundle\Entity\ProyectoPersona $proyectos)
    {
        $this->proyectos->removeElement($proyectos);
    }

    /**
     * Get proyectosPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }

    /**
     * set proyectos
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return Persona
     */
    public function setProyectos($proyectos)
    {
        if(is_array($proyectos)){
            $this->removeAllProyectos();
            foreach($proyectos as $e){
                $this->addProyectos($e);
            }
        }
    
        return $this;
    }
    
    /**
     * Remove All proyectos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllProyectos()
    {
        /*foreach($this->getProyectos() as $et){
            $this->proyectos->removeElement($et);
        }*/
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getProyectos();
    }

    /**
     * Add reconocimientos
     *
     * @param \cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientos
     * @return Persona
     */
    public function addReconocimientos(\cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientos)
    {
        $this->reconocimientos[] = $reconocimientos;

        return $this;
    }

    /**
     * Remove reconocimientos
     *
     * @param \cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientos
     */
    public function removeReconocimientos(\cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientos)
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

    /**
     * set reconocimientos
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return Persona
     */
    public function setReconocimientos($reconocimientos)
    {
        if(is_array($reconocimientos)){
            $this->removeAllReconocimientos();
            foreach($reconocimientos as $e){
                $this->addReconocimientos($e);
            }
        }

        return $this;
    }

    /**
     * Remove All reconocimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllReconocimientos()
    {
        /*foreach($this->getReconocimientos() as $et){
            $this->reconocimientos->removeElement($et);
        }*/
        $this->reconocimientos = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getReconocimientos();
    }

    /**
     * Add gruposPersona
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $gruposPersona
     * @return Persona
     */
    public function addGruposPersona(\cobe\GruposBundle\Entity\GrupoPersona $gruposPersona)
    {
        $this->gruposPersona[] = $gruposPersona;

        return $this;
    }

    /**
     * Remove gruposPersona
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $gruposPersona
     */
    public function removeGruposPersona(\cobe\GruposBundle\Entity\GrupoPersona $gruposPersona)
    {
        $this->gruposPersona->removeElement($gruposPersona);
    }

    /**
     * Get gruposPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGruposPersona()
    {
        return $this->gruposPersona;
    }

    /**
     * Add ofertasLaboralesPersona
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertasLaboralesPersona
     * @return Persona
     */
    public function addOfertasLaboralesPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertasLaboralesPersona)
    {
        $this->ofertasLaboralesPersona[] = $ofertasLaboralesPersona;

        return $this;
    }

    /**
     * Remove ofertasLaboralesPersona
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertasLaboralesPersona
     */
    public function removeOfertasLaboralesPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertasLaboralesPersona)
    {
        $this->ofertasLaboralesPersona->removeElement($ofertasLaboralesPersona);
    }

    /**
     * Get ofertasLaboralesPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertasLaboralesPersona()
    {
        return $this->ofertasLaboralesPersona;
    }

    /**
     * Add publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     * @return Persona
     */
    public function addPublicaciones(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones[] = $publicaciones;

        return $this;
    }

    /**
     * Remove publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     */
    public function removePublicaciones(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones->removeElement($publicaciones);
    }

    /**
     * Get publicaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublicaciones()
    {
        return $this->publicaciones;
    }

    /**
     * Set ciudad
     *
     * @param \cobe\CommonBundle\Entity\Ciudad $ciudad
     * @return Persona
     */
    public function setCiudad(\cobe\CommonBundle\Entity\Ciudad $ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \cobe\CommonBundle\Entity\Ciudad 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Add intereses
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $intereses
     * @return Persona
     */
    public function addIntereses(\cobe\CurriculosBundle\Entity\Interes $intereses)
    {
        $this->intereses[] = $intereses;

        return $this;
    }

    /**
     * Remove intereses
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $intereses
     */
    public function removeIntereses(\cobe\CurriculosBundle\Entity\Interes $intereses)
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
     * set intereses
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return Persona
     */
    public function setIntereses($intereses)
    {
        if(is_array($intereses)){
            $this->removeAllInteres();
            foreach($intereses as $e){
                $this->addIntereses($e);
            }
        }

        return $this;
    }

    /**
     * Remove All intereses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllInteres()
    {
        /*foreach($this->getIntereses() as $et){
            $this->intereses->removeElement($et);
        }*/
        $this->intereses = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getIntereses();
    }

    /**
     * Add aptitudes
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitudes
     * @return Persona
     */
    public function addAptitudes(\cobe\CurriculosBundle\Entity\Aptitud $aptitudes)
    {
        $this->aptitudes[] = $aptitudes;

        return $this;
    }

    /**
     * Remove aptitudes
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitudes
     */
    public function removeAptitudes(\cobe\CurriculosBundle\Entity\Aptitud $aptitudes)
    {
        $this->aptitudes->removeElement($aptitudes);
    }

    /**
     * Get aptitudes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAptitudes()
    {
        return $this->aptitudes;
    }

    /**
     * set aptitudes
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return Persona
     */
    public function setAptitudes($aptitudes)
    {
        if(is_array($aptitudes)){
            $this->removeAllAptitudes();
            foreach($aptitudes as $e){
                $this->addAptitudes($e);
            }
        }

        return $this;
    }

    /**
     * Remove All aptitudes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllAptitudes()
    {
        /*foreach($this->getAptitudes() as $et){
            $this->aptitudes->removeElement($et);
        }*/
        $this->aptitudes = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getAptitudes();
    }

}
