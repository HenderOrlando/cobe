<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de un Mensaje"})
 */
class ArchivoMensaje extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", inversedBy="archivos")
     * @ORM\JoinColumn(name="mensaje", referencedColumnName="id", nullable=false)
     */
    private $mensaje;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set mensaje
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensaje
     * @return ArchivoMensaje
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
}
