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
    private $estudiosPersona;

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
    private $idiomasPersona;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\ProyectoPersona", mappedBy="persona")
     */
    private $proyectosPersona;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\ReconocimientoPersona", mappedBy="persona")
     */
    private $reconocimientosPersona;

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
     *     name="Aptitud2Persona",
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
        $this->estudiosPersona = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recomendados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recomendaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idiomasPersona = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectosPersona = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reconocimientosPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function addEmpresa(\cobe\UsuariosBundle\Entity\RepresentanteEmpresa $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \cobe\UsuariosBundle\Entity\RepresentanteEmpresa $empresas
     */
    public function removeEmpresa(\cobe\UsuariosBundle\Entity\RepresentanteEmpresa $empresas)
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
     * Add estudiosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\EstudioPersona $estudiosPersona
     * @return Persona
     */
    public function addEstudiosPersona(\cobe\CurriculosBundle\Entity\EstudioPersona $estudiosPersona)
    {
        $this->estudiosPersona[] = $estudiosPersona;

        return $this;
    }

    /**
     * Remove estudiosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\EstudioPersona $estudiosPersona
     */
    public function removeEstudiosPersona(\cobe\CurriculosBundle\Entity\EstudioPersona $estudiosPersona)
    {
        $this->estudiosPersona->removeElement($estudiosPersona);
    }

    /**
     * Get estudiosPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudiosPersona()
    {
        return $this->estudiosPersona;
    }

    /**
     * Add recomendados
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendados
     * @return Persona
     */
    public function addRecomendado(\cobe\CurriculosBundle\Entity\Recomendacion $recomendados)
    {
        $this->recomendados[] = $recomendados;

        return $this;
    }

    /**
     * Remove recomendados
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendados
     */
    public function removeRecomendado(\cobe\CurriculosBundle\Entity\Recomendacion $recomendados)
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
    public function addRecomendacione(\cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones)
    {
        $this->recomendaciones[] = $recomendaciones;

        return $this;
    }

    /**
     * Remove recomendaciones
     *
     * @param \cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones
     */
    public function removeRecomendacione(\cobe\CurriculosBundle\Entity\Recomendacion $recomendaciones)
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
     * Add idiomasPersona
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomasPersona
     * @return Persona
     */
    public function addIdiomasPersona(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomasPersona)
    {
        $this->idiomasPersona[] = $idiomasPersona;

        return $this;
    }

    /**
     * Remove idiomasPersona
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomasPersona
     */
    public function removeIdiomasPersona(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomasPersona)
    {
        $this->idiomasPersona->removeElement($idiomasPersona);
    }

    /**
     * Get idiomasPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdiomasPersona()
    {
        return $this->idiomasPersona;
    }

    /**
     * Add proyectosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $proyectosPersona
     * @return Persona
     */
    public function addProyectosPersona(\cobe\CurriculosBundle\Entity\ProyectoPersona $proyectosPersona)
    {
        $this->proyectosPersona[] = $proyectosPersona;

        return $this;
    }

    /**
     * Remove proyectosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ProyectoPersona $proyectosPersona
     */
    public function removeProyectosPersona(\cobe\CurriculosBundle\Entity\ProyectoPersona $proyectosPersona)
    {
        $this->proyectosPersona->removeElement($proyectosPersona);
    }

    /**
     * Get proyectosPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectosPersona()
    {
        return $this->proyectosPersona;
    }

    /**
     * Add reconocimientosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientosPersona
     * @return Persona
     */
    public function addReconocimientosPersona(\cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientosPersona)
    {
        $this->reconocimientosPersona[] = $reconocimientosPersona;

        return $this;
    }

    /**
     * Remove reconocimientosPersona
     *
     * @param \cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientosPersona
     */
    public function removeReconocimientosPersona(\cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientosPersona)
    {
        $this->reconocimientosPersona->removeElement($reconocimientosPersona);
    }

    /**
     * Get reconocimientosPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReconocimientosPersona()
    {
        return $this->reconocimientosPersona;
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
    public function addPublicacione(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones[] = $publicaciones;

        return $this;
    }

    /**
     * Remove publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     */
    public function removePublicacione(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
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
     * Add aptitudes
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitudes
     * @return Persona
     */
    public function addAptitude(\cobe\CurriculosBundle\Entity\Aptitud $aptitudes)
    {
        $this->aptitudes[] = $aptitudes;

        return $this;
    }

    /**
     * Remove aptitudes
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitudes
     */
    public function removeAptitude(\cobe\CurriculosBundle\Entity\Aptitud $aptitudes)
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

}
