<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;

/**
 * @ORM\Entity(repositoryClass="cobe\MensajesBundle\Repository\MensajeRepository")
 * @ORM\Table(options={"comment":"Mensajes en el sistema"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="herenciaMensaje", length=25, type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *      "Mensaje"="\cobe\MensajesBundle\Entity\Mensaje",
 *      "Comentario"="\cobe\MensajesBundle\Entity\Comentario",
 *     }
 * )
 */
class Mensaje extends Obj
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Destinatario", mappedBy="mensaje")
     */
    private $destinatarios;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoMensaje", mappedBy="mensaje")
     */
    private $archivos;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaMensaje", mappedBy="mensaje")
     */
    private $estadisticasMensaje;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="mensajesUsuario")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuarioMensaje;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\MensajesBundle\Entity\EstadoMensaje", inversedBy="mensajes")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estadoMensaje;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaMensaje", inversedBy="mensajes")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantilla;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", inversedBy="mensajesRespuesta")
     * @ORM\JoinTable(
     *     name="Mensaje2Mensaje",
     *     joinColumns={@ORM\JoinColumn(name="mensajeRespuesta", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mensaje", referencedColumnName="id", nullable=false)}
     * )
     */
    private $mensajes;

    /**
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
        $this->estadisticasMensaje = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function addDestinatario(\cobe\MensajesBundle\Entity\Destinatario $destinatarios)
    {
        $this->destinatarios[] = $destinatarios;

        return $this;
    }

    /**
     * Remove destinatarios
     *
     * @param \cobe\MensajesBundle\Entity\Destinatario $destinatarios
     */
    public function removeDestinatario(\cobe\MensajesBundle\Entity\Destinatario $destinatarios)
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
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos
     * @return Mensaje
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoMensaje $archivos)
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
     * Add estadisticasMensaje
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje
     * @return Mensaje
     */
    public function addEstadisticasMensaje(\cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje)
    {
        $this->estadisticasMensaje[] = $estadisticasMensaje;

        return $this;
    }

    /**
     * Remove estadisticasMensaje
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje
     */
    public function removeEstadisticasMensaje(\cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje)
    {
        $this->estadisticasMensaje->removeElement($estadisticasMensaje);
    }

    /**
     * Get estadisticasMensaje
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasMensaje()
    {
        return $this->estadisticasMensaje;
    }

    /**
     * Set usuarioMensaje
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuarioMensaje
     * @return Mensaje
     */
    public function setUsuarioMensaje(\cobe\UsuariosBundle\Entity\Usuario $usuarioMensaje)
    {
        $this->usuarioMensaje = $usuarioMensaje;

        return $this;
    }

    /**
     * Get usuarioMensaje
     *
     * @return \cobe\UsuariosBundle\Entity\Usuario 
     */
    public function getUsuarioMensaje()
    {
        return $this->usuarioMensaje;
    }

    /**
     * Set estadoMensaje
     *
     * @param \cobe\MensajesBundle\Entity\EstadoMensaje $estadoMensaje
     * @return Mensaje
     */
    public function setEstadoMensaje(\cobe\MensajesBundle\Entity\EstadoMensaje $estadoMensaje)
    {
        $this->estadoMensaje = $estadoMensaje;

        return $this;
    }

    /**
     * Get estadoMensaje
     *
     * @return \cobe\MensajesBundle\Entity\EstadoMensaje 
     */
    public function getEstadoMensaje()
    {
        return $this->estadoMensaje;
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
     * Add mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     * @return Mensaje
     */
    public function addMensaje(\cobe\MensajesBundle\Entity\Mensaje $mensajes)
    {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     */
    public function removeMensaje(\cobe\MensajesBundle\Entity\Mensaje $mensajes)
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
}
