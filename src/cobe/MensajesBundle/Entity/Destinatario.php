<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\MensajesBundle\Repository\DestinatarioRepository")
 * @ORM\Table(
 *     options={"comment":"Usuarios Destinatarios del Mensaje"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="mensaje_usuario", columns={"mensaje","usuario"})}
 * )
 */
class Destinatario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $fechaCreado;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", inversedBy="destinatarios")
     * @ORM\JoinColumn(name="mensaje", referencedColumnName="id", nullable=false)
     */
    private $mensaje;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="destinatarios")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuario;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\MensajesBundle\Entity\EstadoDestinatario", inversedBy="destinatarios")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;

    /**
     * Get id
     *
     * @return guid 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Destinatario
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set mensaje
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensaje
     * @return Destinatario
     */
    public function setMensaje(\cobe\MensajesBundle\Entity\Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return \cobe\MensajesBundle\Entity\Mensaje 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set usuario
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuario
     * @return Destinatario
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
     * @param \cobe\MensajesBundle\Entity\EstadoDestinatario $estado
     * @return Destinatario
     */
    public function setEstado(\cobe\MensajesBundle\Entity\EstadoDestinatario $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\MensajesBundle\Entity\EstadoDestinatario 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
