<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;

/**
 * @ORM\Entity
 */
class Historial extends Objeto
{
    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\TipoHistorial", inversedBy="historiales")
     * @ORM\JoinColumn(name="accion", referencedColumnName="id", nullable=false)
     */
    private $accion;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $navegador;

    /**
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $entityName;

    /**
     * @ORM\Column(type="guid", nullable=false)
     */
    private $entityId;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $ipv4;

    /**
     * @ORM\Column(type="string", length=39, nullable=true)
     */
    private $ipv6;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="historiales")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuario;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set navegador
     *
     * @param string $navegador
     * @return Historial
     */
    public function setNavegador($navegador)
    {
        $this->navegador = $navegador;

        return $this;
    }

    /**
     * Get navegador
     *
     * @return string 
     */
    public function getNavegador()
    {
        return $this->navegador;
    }

    /**
     * Set entityName
     *
     * @param string $entityName
     * @return Historial
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName
     *
     * @return string 
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set entityId
     *
     * @param guid $entityId
     * @return Historial
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return guid 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set ipv4
     *
     * @param string $ipv4
     * @return Historial
     */
    public function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;

        return $this;
    }

    /**
     * Get ipv4
     *
     * @return string 
     */
    public function getIpv4()
    {
        return $this->ipv4;
    }

    /**
     * Set ipv6
     *
     * @param string $ipv6
     * @return Historial
     */
    public function setIpv6($ipv6)
    {
        $this->ipv6 = $ipv6;

        return $this;
    }

    /**
     * Get ipv6
     *
     * @return string 
     */
    public function getIpv6()
    {
        return $this->ipv6;
    }

    /**
     * Set accion
     *
     * @param \cobe\UsuariosBundle\Entity\TipoHistorial $accion
     * @return Historial
     */
    public function setAccion(\cobe\UsuariosBundle\Entity\TipoHistorial $accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return \cobe\UsuariosBundle\Entity\TipoHistorial 
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set usuario
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuario
     * @return Historial
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
}
