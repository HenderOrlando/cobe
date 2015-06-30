<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\UsuarioRepository")
 * @ORM\Table(options={"comment":"Usuarios del sistema"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="herencia", length=10, type="string")
 * @ORM\DiscriminatorMap({
 *  "Usuario"="\cobe\UsuariosBundle\Entity\Usuario",
 *  "Persona"="\cobe\UsuariosBundle\Entity\Persona",
 *  "Empresa"="\cobe\UsuariosBundle\Entity\Empresa",
 *  "Estudiante"="\cobe\UsuariosBundle\Entity\Estudiante"
 * })
 */
class Usuario extends Objeto
{
    /**
     * @ORM\Column(
     *     type="text",
     *     nullable=false,
     *     options={"comment":"Clave del Usuario para entrar al sistema"}
     * )
     */
    private $clave;

    /**
     * @ORM\Column(
     *     type="guid",
     *     unique=true,
     *     nullable=false,
     *     options={"comment":"Código para encriptar la información del usuario"}
     * )
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $salt;

    /**
     * @ORM\Column(
     *     type="string",
     *     unique=true,
     *     length=100,
     *     nullable=false,
     *     options={"comment":"Email para contactar con el Usuario"}
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="guid", unique=true, nullable=false)
     */
    private $token;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Historial", mappedBy="usuario")
     */
    private $historiales;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="usuario")
     */
    private $ofertasLaborales;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", mappedBy="usuario")
     */
    private $mensajes;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Destinatario", mappedBy="usuario")
     */
    private $destinatarios;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioUsuario", mappedBy="usuario")
     */
    private $comentarios;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoUsuario", mappedBy="usuario")
     */
    private $archivos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaUsuario", mappedBy="usuario")
     */
    private $estadisticas;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\RolUsuario", inversedBy="usuarios")
     * @ORM\JoinColumn(name="rol", referencedColumnName="id", nullable=false)
     */
    private $rol;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\EstadoUsuario", inversedBy="usuarios")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaUsuario", inversedBy="usuarios")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantilla;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="solicitados")
     * @ORM\JoinTable(
     *     name="amistad",
     *     joinColumns={@ORM\JoinColumn(name="solicitado", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="solicitante", referencedColumnName="id", nullable=false)}
     * )
     */
    private $solicitantes;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", mappedBy="solicitantes")
     */
    private $solicitados;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->historiales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mensajes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->destinatarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->solicitantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->solicitados = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set clave
     *
     * @param string $clave
     * @return Usuario
     */
    public function setClave($clave)
    {
        if(!$this->getSalt()){
            $this->setSalt(sha1(uniqid(mt_rand())));
        }
        $this->clave = hash('sha256',$clave.$this->getSalt());

        return $this;
    }

    /**
     * Get clave
     *
     * @return string
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set salt
     *
     * @param guid $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return guid
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->setToken(sha1($email));

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     *
     * @param guid $token
     * @return Usuario
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return guid
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Add historiales
     *
     * @param \cobe\UsuariosBundle\Entity\Historial $historiales
     * @return Usuario
     */
    public function addHistoriale(\cobe\UsuariosBundle\Entity\Historial $historiales)
    {
        $this->historiales[] = $historiales;

        return $this;
    }

    /**
     * Remove historiales
     *
     * @param \cobe\UsuariosBundle\Entity\Historial $historiales
     */
    public function removeHistoriale(\cobe\UsuariosBundle\Entity\Historial $historiales)
    {
        $this->historiales->removeElement($historiales);
    }

    /**
     * Get historiales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoriales()
    {
        return $this->historiales;
    }

    /**
     * Add ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     * @return Usuario
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
     * Add mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     * @return Usuario
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
     * Add destinatarios
     *
     * @param \cobe\MensajesBundle\Entity\Destinatario $destinatarios
     * @return Usuario
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
     * Add comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioUsuario $comentarios
     * @return Usuario
     */
    public function addComentarios(\cobe\MensajesBundle\Entity\ComentarioUsuario $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioUsuario $comentarios
     */
    public function removeComentarios(\cobe\MensajesBundle\Entity\ComentarioUsuario $comentarios)
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
     * Add archivo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivos
     * @return Usuario
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivo
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
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticas
     * @return Usuario
     */
    public function addEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticas
     */
    public function removeEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticas)
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
     * Set rol
     *
     * @param \cobe\UsuariosBundle\Entity\RolUsuario $rol
     * @return Usuario
     */
    public function setRol(\cobe\UsuariosBundle\Entity\RolUsuario $rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \cobe\UsuariosBundle\Entity\RolUsuario 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set estado
     *
     * @param \cobe\UsuariosBundle\Entity\EstadoUsuario $estado
     * @return Usuario
     */
    public function setEstado(\cobe\UsuariosBundle\Entity\EstadoUsuario $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\UsuariosBundle\Entity\EstadoUsuario 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set plantilla
     *
     * @param \cobe\PaginasBundle\Entity\PlantillaUsuario $plantilla
     * @return Usuario
     */
    public function setPlantilla(\cobe\PaginasBundle\Entity\PlantillaUsuario $plantilla)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \cobe\PaginasBundle\Entity\PlantillaUsuario 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * Add solicitantes
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $solicitantes
     * @return Usuario
     */
    public function addSolicitante(\cobe\UsuariosBundle\Entity\Usuario $solicitantes)
    {
        $this->solicitantes[] = $solicitantes;

        return $this;
    }

    /**
     * Remove solicitantes
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $solicitantes
     */
    public function removeSolicitante(\cobe\UsuariosBundle\Entity\Usuario $solicitantes)
    {
        $this->solicitantes->removeElement($solicitantes);
    }

    /**
     * Get solicitantes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSolicitantes()
    {
        return $this->solicitantes;
    }

    /**
     * Add solicitados
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $solicitados
     * @return Usuario
     */
    public function addSolicitado(\cobe\UsuariosBundle\Entity\Usuario $solicitados)
    {
        $this->solicitados[] = $solicitados;

        return $this;
    }

    /**
     * Remove solicitados
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $solicitados
     */
    public function removeSolicitado(\cobe\UsuariosBundle\Entity\Usuario $solicitados)
    {
        $this->solicitados->removeElement($solicitados);
    }

    /**
     * Get solicitados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSolicitados()
    {
        return $this->solicitados;
    }


}
