<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\MensajesBundle\Repository\MensajeRepository")
 * @ORM\Table(options={"comment":"Mensajes en el sistema"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="herenciaMensaje", length=25, type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *      "Mensaje"="\cobe\MensajesBundle\Entity\Mensaje",
 *      "Comentario"="\cobe\MensajesBundle\Entity\Comentario",
 *      "ComentarioUsuario"="\cobe\MensajesBundle\Entity\ComentarioUsuario",
 *      "ComentarioGrupo"="\cobe\MensajesBundle\Entity\ComentarioGrupo",
 *      "ComentarioArchivo"="\cobe\MensajesBundle\Entity\ComentarioArchivo",
 *      "ComentarioPublicacion"="\cobe\MensajesBundle\Entity\ComentarioPublicacion",
 *      "ComentarioOfertaLaboral"="\cobe\MensajesBundle\Entity\ComentarioOfertaLaboral"
 *     }
 * )
 */
class Mensaje extends Obj
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Destinatario", mappedBy="mensaje")
     */
    private $destinatarios;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoMensaje", mappedBy="mensaje")
     */
    private $archivos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaMensaje", mappedBy="mensaje")
     */
    private $estadisticas;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="mensajes")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuario;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\MensajesBundle\Entity\EstadoMensaje", inversedBy="mensajes")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaMensaje", inversedBy="mensajes")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantilla;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\PaginasBundle\Entity\Categoria", inversedBy="mensajes")
     * @ORM\JoinTable(
     *     name="categoria2mensaje",
     *     joinColumns={@ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="categoria", referencedColumnName="id", nullable=false)}
     * )
     */
    protected $categorias;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", inversedBy="mensajesRespuesta")
     * @ORM\JoinTable(
     *     name="mensaje2mensaje",
     *     joinColumns={@ORM\JoinColumn(name="mensajeRespuesta", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mensaje", referencedColumnName="id", nullable=false)}
     * )
     */
    private $mensajes;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", mappedBy="mensajes")
     */
    private $mensajesRespuesta;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->destinatarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mensajes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mensajesRespuesta = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add destinatarios
     *
     * @param \cobe\MensajesBundle\Entity\Destinatario $destinatarios
     * @return Mensaje
     */
    public function addDestinatarios(\cobe\MensajesBundle\Entity\Destinatario $destinatarios)
    {
        $this->destinatarios[] = $destinatarios;

        return $this;
    }

    /**
     * Remove destinatarios
     *
     * @param \cobe\MensajesBundle\Entity\Destinatario $destinatarios
     */
    public function removeDestinatarios(\cobe\MensajesBundle\Entity\Destinatario $destinatarios)
    {
        $this->destinatarios->removeElement($destinatarios);
    }

    /**
     * Get destinatarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDestinatarios()
    {
        return $this->destinatarios;
    }

    /**
     * set destinatarios
     *
     * @param \Doctrine\Common\Collections\Collection
     * @param \Doctrine\Common\Collections\Collection
     * @return Objeto
     */
    public function setDestinatarios($destinatarios)
    {
        if(is_array($destinatarios)){
            $this->removeAllDestinatarios();
            foreach($destinatarios as $e){
                $this->addDestinatarios($e);
            }
        }

        return $this;
    }

    /**
     * Remove All destinatarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeAllDestinatarios()
    {
        /*foreach($this->getDestinatarios() as $et){
            $this->destinatarios->removeElement($et);
        }*/
        $this->destinatarios = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->getDestinatarios();
    }

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos
     * @return Mensaje
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivos
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
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticas
     * @return Mensaje
     */
    public function addEstadisticas(\cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticas
     */
    public function removeEstadisticas(\cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasMensaje()
    {
        return $this->estadisticas;
    }

    /**
     * Set usuario
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuario
     * @return Mensaje
     */
    public function setUsuario(\cobe\UsuariosBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \cobe\UsuariosBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set estado
     *
     * @param \cobe\MensajesBundle\Entity\EstadoMensaje $estado
     * @return Mensaje
     */
    public function setEstado(\cobe\MensajesBundle\Entity\EstadoMensaje $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\MensajesBundle\Entity\EstadoMensaje 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set plantilla
     *
     * @param \cobe\PaginasBundle\Entity\PlantillaMensaje $plantilla
     * @return Mensaje
     */
    public function setPlantilla(\cobe\PaginasBundle\Entity\PlantillaMensaje $plantilla)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \cobe\PaginasBundle\Entity\PlantillaMensaje 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * Add categorias
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $categorias
     * @return Mensaje
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
     * Add mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     * @return Mensaje
     */
    public function addMensajes(\cobe\MensajesBundle\Entity\Mensaje $mensajes)
    {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     */
    public function removeMensajes(\cobe\MensajesBundle\Entity\Mensaje $mensajes)
    {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Get mensajes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajes()
    {
        return $this->mensajes;
    }

    /**
     * Add mensajesRespuesta
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajesRespuesta
     * @return Mensaje
     */
    public function addMensajesRespuestum(\cobe\MensajesBundle\Entity\Mensaje $mensajesRespuesta)
    {
        $this->mensajesRespuesta[] = $mensajesRespuesta;

        return $this;
    }

    /**
     * Remove mensajesRespuesta
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajesRespuesta
     */
    public function removeMensajesRespuestum(\cobe\MensajesBundle\Entity\Mensaje $mensajesRespuesta)
    {
        $this->mensajesRespuesta->removeElement($mensajesRespuesta);
    }

    /**
     * Get mensajesRespuesta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajesRespuesta()
    {
        return $this->mensajesRespuesta;
    }
    
    public function getHerencias(){
        return array(
            'Mensaje'                   =>'\cobe\MensajesBundle\Entity\Mensaje',
            'Comentario'                =>'\cobe\MensajesBundle\Entity\Comentario',
            'ComentarioGrupo'           =>'\cobe\MensajesBundle\Entity\ComentarioGrupo',
            'ComentarioUsuario'         =>'\cobe\MensajesBundle\Entity\ComentarioUsuario',
            'ComentarioArchivo'         =>'\cobe\MensajesBundle\Entity\ComentarioArchivo',
            'ComentarioPublicacion'     =>'\cobe\MensajesBundle\Entity\ComentarioPublicacion',
            'ComentarioOfertaLaboral'   =>'\cobe\MensajesBundle\Entity\ComentarioOfertaLaboral'
        );
    }
}
