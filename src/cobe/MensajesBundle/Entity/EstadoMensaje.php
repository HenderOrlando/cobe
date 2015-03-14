<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;

/**
 * @ORM\Entity(repositoryClass="cobe\MensajesBundle\Repository\EstadoMensajeRepository")
 * @ORM\Table(options={"comment":"Estados de los Mensajes enviados"})
 */
class EstadoMensaje extends Estado
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", mappedBy="estadoMensaje")
     */
    private $mensajes;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mensajes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     * @return EstadoMensaje
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
}
