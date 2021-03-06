<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\PublicacionRepository")
 * @ORM\Table(
 *     options={"comment":"Publicaciones realizadas en el sistema"},
 *     indexes={@ORM\Index(name="grupo_editor", columns={})}
 * )
 */
class Publicacion extends Obj
{
    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $metadatos;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaArchiva;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $idexada;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="publicacion")
     */
    private $ofertasLaborales;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioPublicacion", mappedBy="publicacion")
     */
    private $comentarios;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\VotacionPublicacion", mappedBy="publicacion")
     */
    private $votaciones;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoPublicacion", mappedBy="publicacion")
     */
    private $archivos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion", mappedBy="publicacion")
     */
    private $estadisticas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\PaginasBundle\Entity\Categoria", inversedBy="publicaciones")
     * @ORM\JoinTable(
     *     name="categoria2publicacion",
     *     joinColumns={@ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="categoria", referencedColumnName="id", nullable=false)}
     * )
     */
    protected $categorias;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\EstadoPublicacion", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\TipoPublicacion", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaPublicacion", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantilla;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="autor", referencedColumnName="id", nullable=false)
     */
    private $autor;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\GrupoEditor", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=true)
     */
    private $grupoEditor;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="publicaciones")
     * @ORM\JoinTable(
     *     name="etiqueta2publicacion",
     *     joinColumns={@ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    protected $etiquetas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->indexada = false;
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set metadatos
     *
     * @param array $metadatos
     * @return Publicacion
     */
    public function setMetadatos($metadatos)
    {
        $this->metadatos = $metadatos;

        return $this;
    }

    /**
     * Get metadatos
     *
     * @return array
     */
    public function getMetadatos()
    {
        return $this->metadatos;
    }

    /**
     * Set fechaArchiva
     *
     * @param \DateTime $fechaArchiva
     * @return Publicacion
     */
    public function setFechaArchiva($fechaArchiva)
    {
        $this->fechaArchiva = $fechaArchiva;

        return $this;
    }

    /**
     * Get fechaArchiva
     *
     * @return \DateTime
     */
    public function getFechaArchiva()
    {
        return $this->fechaArchiva;
    }

    /**
     * Set idexada
     *
     * @param boolean $idexada
     * @return Publicacion
     */
    public function setIdexada($idexada)
    {
        $this->idexada = $idexada;

        return $this;
    }

    /**
     * Get idexada
     *
     * @return boolean
     */
    public function getIdexada()
    {
        return $this->idexada;
    }

    /**
     * Add ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     * @return Publicacion
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
     * Get ofertasLaborales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOfertasLaborales()
    {
        return $this->ofertasLaborales;
    }

    /**
     * Add comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioPublicacion $comentarios
     * @return Publicacion
     */
    public function addComentario(\cobe\MensajesBundle\Entity\ComentarioPublicacion $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioPublicacion $comentarios
     */
    public function removeComentario(\cobe\MensajesBundle\Entity\ComentarioPublicacion $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add votacion
     *
     * @param \cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones
     * @return Publicacion
     */
    public function addVotaciones(\cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones)
    {
        $this->votaciones[] = $votaciones;

        return $this;
    }

    /**
     * Remove votacion
     *
     * @param \cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones
     */
    public function removeVotaciones(\cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones)
    {
        $this->votaciones->removeElement($votaciones);
    }

    /**
     * Get votacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotaciones()
    {
        return $this->votaciones;
    }

    /**
     * Add archivosPublicacion
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivos
     * @return Publicacion
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivosPublicacion
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivosPublicacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * Add estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticas
     * @return Publicacion
     */
    public function addEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticas
     */
    public function removeEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
    }

    /**
     * Add categorias
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $categorias
     * @return Publicacion
     */
    public function addCategorias(\cobe\PaginasBundle\Entity\Categoria $categorias)
    {
        $this->categorias[] = $categorias;

        return $this;
    }

    /**
     * Remove categorias
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $categorias
     */
    public function removeCategorias(\cobe\PaginasBundle\Entity\Categoria $categorias)
    {
        $this->categorias->removeElement($categorias);
    }

    /**
     * Get categorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

    /**
     * set categorias
     *
     * @param \Doctrine\Common\Collections\Collection
     * @param \Doctrine\Common\Collections\Collection
     * @return Publicacion
     */
    public function setCategorias($categorias)
    {
        if(is_array($categorias)){
            $this->removeAllCategorias();
            foreach($categorias as $e){
                $this->addCategorias($e);
            }
        }

        return $this;
    }

    /**
     * Remove All categorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllCategorias()
    {
        /*foreach($this->getCategorias() as $et){
            $this->categorias->removeElement($et);
        }*/
        $this->categorias = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getCategorias();
    }

    /**
     * Set estado
     *
     * @param \cobe\PaginasBundle\Entity\EstadoPublicacion $estado
     * @return Publicacion
     */
    public function setEstado(\cobe\PaginasBundle\Entity\EstadoPublicacion $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\PaginasBundle\Entity\EstadoPublicacion 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set tipo
     *
     * @param \cobe\PaginasBundle\Entity\TipoPublicacion $tipo
     * @return Publicacion
     */
    public function setTipo(\cobe\PaginasBundle\Entity\TipoPublicacion $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\PaginasBundle\Entity\TipoPublicacion 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set plantilla
     *
     * @param \cobe\PaginasBundle\Entity\PlantillaPublicacion $plantilla
     * @return Publicacion
     */
    public function setPlantilla(\cobe\PaginasBundle\Entity\PlantillaPublicacion $plantilla)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \cobe\PaginasBundle\Entity\PlantillaPublicacion 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * Set autor
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $autor
     * @return Publicacion
     */
    public function setAutor(\cobe\UsuariosBundle\Entity\Persona $autor)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get autor
     *
     * @return \cobe\UsuariosBundle\Entity\Persona 
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Set grupoEditor
     *
     * @param \cobe\PaginasBundle\Entity\GrupoEditor $grupoEditor
     * @return Publicacion
     */
    public function setGrupoEditor(\cobe\PaginasBundle\Entity\GrupoEditor $grupoEditor)
    {
        $this->grupoEditor = $grupoEditor;

        return $this;
    }

    /**
     * Get grupoEditor
     *
     * @return \cobe\PaginasBundle\Entity\GrupoEditor 
     */
    public function getGrupoEditor()
    {
        return $this->grupoEditor;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return Publicacion
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
