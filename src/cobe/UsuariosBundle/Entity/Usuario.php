<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\UsuarioRepository")
 * @ORM\Table(options={"comment":"Usuarios del sistema"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="herencia", length=10, type="string")
 * @ORM\DiscriminatorMap({"Usuario"="\cobe\UsuariosBundle\Entity\Usuario","Persona"="\cobe\UsuariosBundle\Entity\Persona"})
 */
class Usuario extends Objeto
{
    /**
     * @ORM\Column(
     *     type="string",
     *     length=50,
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
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Historial", mappedBy="usuario")
     */
    private $historiales;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="usuario")
     */
    private $ofertasLaborales;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", mappedBy="usuarioMensaje")
     */
    private $mensajesUsuario;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Destinatario", mappedBy="usuario")
     */
    private $destinatarios;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioUsuario", mappedBy="usuario")
     */
    private $comentariosUsuario;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoUsuario", mappedBy="usuario")
     */
    private $archivo;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaUsuario", mappedBy="usuario")
     */
    private $estadisticas;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\RolUsuario", inversedBy="usuarios")
     * @ORM\JoinColumn(name="rol", referencedColumnName="id", nullable=false)
     */
    private $rol;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\EstadoUsuario", inversedBy="usuarios")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaUsuario", inversedBy="usuarios")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantilla;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="solicitados")
     * @ORM\JoinTable(
     *     name="Amistad",
     *     joinColumns={@ORM\JoinColumn(name="solicitado", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="solicitante", referencedColumnName="id", nullable=false)}
     * )
     */
    private $solicitantes;

    /**
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
        $this->mensajesUsuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->destinatarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentariosUsuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivo = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set clave
     *
     * @param string $clave
     * @return Usuario
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

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
     * Add mensajesUsuario
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajesUsuario
     * @return Usuario
     */
    public function addMensajesUsuario(\cobe\MensajesBundle\Entity\Mensaje $mensajesUsuario)
    {
        $this->mensajesUsuario[] = $mensajesUsuario;

        return $this;
    }

    /**
     * Remove mensajesUsuario
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajesUsuario
     */
    public function removeMensajesUsuario(\cobe\MensajesBundle\Entity\Mensaje $mensajesUsuario)
    {
        $this->mensajesUsuario->removeElement($mensajesUsuario);
    }

    /**
     * Get mensajesUsuario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajesUsuario()
    {
        return $this->mensajesUsuario;
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
     * Add comentariosUsuario
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioUsuario $comentariosUsuario
     * @return Usuario
     */
    public function addComentariosUsuario(\cobe\MensajesBundle\Entity\ComentarioUsuario $comentariosUsuario)
    {
        $this->comentariosUsuario[] = $comentariosUsuario;

        return $this;
    }

    /**
     * Remove comentariosUsuario
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioUsuario $comentariosUsuario
     */
    public function removeComentariosUsuario(\cobe\MensajesBundle\Entity\ComentarioUsuario $comentariosUsuario)
    {
        $this->comentariosUsuario->removeElement($comentariosUsuario);
    }

    /**
     * Get comentariosUsuario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentariosUsuario()
    {
        return $this->comentariosUsuario;
    }

    /**
     * Add archivo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivo
     * @return Usuario
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivo)
    {
        $this->archivo[] = $archivo;

        return $this;
    }

    /**
     * Remove archivo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivo
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoUsuario $archivo)
    {
        $this->archivo->removeElement($archivo);
    }

    /**
     * Get archivo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivo()
    {
        return $this->archivo;
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
