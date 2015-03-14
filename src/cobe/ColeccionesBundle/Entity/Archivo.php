<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="ext", columns={"ext"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaArchivo", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "Archivo"="\cobe\ColeccionesBundle\Entity\Archivo",
 *     "Plantilla"="\cobe\ColeccionesBundle\Entity\ArchivoPlantilla",
 *     "Usuario"="\cobe\ColeccionesBundle\Entity\ArchivoUsuario",
 *     "ReconocimientoPersona"="\cobe\ColeccionesBundle\Entity\ArchivoReconocimientoPersona",
 *     "Proyecto"="\cobe\ColeccionesBundle\Entity\ArchivoProyecto",
 *     "Mensaje"="\cobe\ColeccionesBundle\Entity\ArchivoMensaje",
 *     "Trabajo"="\cobe\ColeccionesBundle\Entity\ArchivoTrabajo",
 *     "Estudio"="\cobe\ColeccionesBundle\Entity\ArchivoEstudio",
 *     "EstudioPersona"="\cobe\ColeccionesBundle\Entity\ArchivoEstudioPersona",
 *     "CentroEstudio"="\cobe\ColeccionesBundle\Entity\ArchivoCentroEstudio",
 *     "Aptitud"="\cobe\ColeccionesBundle\Entity\ArchivoAptitud",
 *     "Grupo"="\cobe\ColeccionesBundle\Entity\ArchivoGrupo",
 *     "Recomendacion"="\cobe\ColeccionesBundle\Entity\ArchivoRecomendacion",
 *     "EstadisticaUsuario"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaUsuario",
 *     "EstadisticaPublicacion"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaPublicacion",
 *     "EstadisticaOfertaLaboral"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaOfertaLaboral",
 *     "EstadisticaGrupo"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaGrupo",
 *     "EstadisticaEmpresa"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaEmpresa",
 *     "EstadisticaMensaje"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaMensaje",
 *     "Publicacion"="\cobe\ColeccionesBundle\Entity\ArchivoPublicacion",
 *     "EstadisticaAptitud"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaAptitud",
 *     "EstadisticaInteres"="\cobe\ColeccionesBundle\Entity\ArchivoEstadisticaInteres",
 *     "Traduccion"="\cobe\ColeccionesBundle\Entity\ArchivoTraduccion"
 * }
 * )
 */
class Archivo extends Objeto
{
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $url;

    /**
     * @ORM\Column(type="time", unique=true, nullable=false)
     */
    private $fullUrl;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true})
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    private $ext;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioArchivo", mappedBy="archivo")
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\ColeccionesBundle\Entity\EstadoArchivo", inversedBy="archivosEstado")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\ColeccionesBundle\Entity\TipoArchivo", inversedBy="archivosTipo")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="archivos")
     * @ORM\JoinTable(
     *     name="Etiqueta2Archivo",
     *     joinColumns={@ORM\JoinColumn(name="archivo", referencedColumnName="id", nullable=false)},
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
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set url
     *
     * @param string $url
     * @return Archivo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fullUrl
     *
     * @param \DateTime $fullUrl
     * @return Archivo
     */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;

        return $this;
    }

    /**
     * Get fullUrl
     *
     * @return \DateTime 
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Archivo
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set ext
     *
     * @param string $ext
     * @return Archivo
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext
     *
     * @return string 
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Add comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioArchivo $comentarios
     * @return Archivo
     */
    public function addComentario(\cobe\MensajesBundle\Entity\ComentarioArchivo $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioArchivo $comentarios
     */
    public function removeComentario(\cobe\MensajesBundle\Entity\ComentarioArchivo $comentarios)
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
     * Set estado
     *
     * @param \cobe\ColeccionesBundle\Entity\EstadoArchivo $estado
     * @return Archivo
     */
    public function setEstado(\cobe\ColeccionesBundle\Entity\EstadoArchivo $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\ColeccionesBundle\Entity\EstadoArchivo 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set tipo
     *
     * @param \cobe\ColeccionesBundle\Entity\TipoArchivo $tipo
     * @return Archivo
     */
    public function setTipo(\cobe\ColeccionesBundle\Entity\TipoArchivo $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\ColeccionesBundle\Entity\TipoArchivo 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return Archivo
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
