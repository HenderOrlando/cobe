<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\PublicacionRepository")
 * @ORM\Table(
 *     options={"comment":"Publicaciones realizadas en el sistema"},
 *     indexes={@ORM\Index(name="categoria", columns={"categoria"}),@ORM\Index(name="grupo_editor", columns={})}
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
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="publicacion")
     */
    private $ofertasLaborales;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioPublicacion", mappedBy="publicacion")
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\VotacionPublicacion", mappedBy="publicacion")
     */
    private $votacion;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoPublicacion", mappedBy="publicacion")
     */
    private $archivosPublicacion;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion", mappedBy="publicacion")
     */
    private $estadisticas;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Categoria", inversedBy="publicacionesCategoria")
     * @ORM\JoinColumn(name="categoria", referencedColumnName="id", nullable=false)
     */
    private $categoriaPublicacion;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\EstadoPublicacion", inversedBy="publicacionesEstado")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estadoPublicacion;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\TipoPublicacion", inversedBy="publicacionesTipo")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipoPublicacion;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaPublicacion", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=false)
     */
    private $plantilla;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="autor", referencedColumnName="id", nullable=false)
     */
    private $autor;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\GrupoEditor", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)
     */
    private $grupoEditor;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="publicaciones")
     * @ORM\JoinTable(
     *     name="Etiqueta2Publicacion",
     *     joinColumns={@ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    private $etiquetas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivosPublicacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \cobe\PaginasBundle\Entity\VotacionPublicacion $votacion
     * @return Publicacion
     */
    public function addVotacion(\cobe\PaginasBundle\Entity\VotacionPublicacion $votacion)
    {
        $this->votacion[] = $votacion;

        return $this;
    }

    /**
     * Remove votacion
     *
     * @param \cobe\PaginasBundle\Entity\VotacionPublicacion $votacion
     */
    public function removeVotacion(\cobe\PaginasBundle\Entity\VotacionPublicacion $votacion)
    {
        $this->votacion->removeElement($votacion);
    }

    /**
     * Get votacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotacion()
    {
        return $this->votacion;
    }

    /**
     * Add archivosPublicacion
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivosPublicacion
     * @return Publicacion
     */
    public function addArchivosPublicacion(\cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivosPublicacion)
    {
        $this->archivosPublicacion[] = $archivosPublicacion;

        return $this;
    }

    /**
     * Remove archivosPublicacion
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivosPublicacion
     */
    public function removeArchivosPublicacion(\cobe\ColeccionesBundle\Entity\ArchivoPublicacion $archivosPublicacion)
    {
        $this->archivosPublicacion->removeElement($archivosPublicacion);
    }

    /**
     * Get archivosPublicacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosPublicacion()
    {
        return $this->archivosPublicacion;
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
     * Set categoriaPublicacion
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $categoriaPublicacion
     * @return Publicacion
     */
    public function setCategoriaPublicacion(\cobe\PaginasBundle\Entity\Categoria $categoriaPublicacion)
    {
        $this->categoriaPublicacion = $categoriaPublicacion;

        return $this;
    }

    /**
     * Get categoriaPublicacion
     *
     * @return \cobe\PaginasBundle\Entity\Categoria 
     */
    public function getCategoriaPublicacion()
    {
        return $this->categoriaPublicacion;
    }

    /**
     * Set estadoPublicacion
     *
     * @param \cobe\PaginasBundle\Entity\EstadoPublicacion $estadoPublicacion
     * @return Publicacion
     */
    public function setEstadoPublicacion(\cobe\PaginasBundle\Entity\EstadoPublicacion $estadoPublicacion)
    {
        $this->estadoPublicacion = $estadoPublicacion;

        return $this;
    }

    /**
     * Get estadoPublicacion
     *
     * @return \cobe\PaginasBundle\Entity\EstadoPublicacion 
     */
    public function getEstadoPublicacion()
    {
        return $this->estadoPublicacion;
    }

    /**
     * Set tipoPublicacion
     *
     * @param \cobe\PaginasBundle\Entity\TipoPublicacion $tipoPublicacion
     * @return Publicacion
     */
    public function setTipoPublicacion(\cobe\PaginasBundle\Entity\TipoPublicacion $tipoPublicacion)
    {
        $this->tipoPublicacion = $tipoPublicacion;

        return $this;
    }

    /**
     * Get tipoPublicacion
     *
     * @return \cobe\PaginasBundle\Entity\TipoPublicacion 
     */
    public function getTipoPublicacion()
    {
        return $this->tipoPublicacion;
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
