<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\MensajesBundle\Repository\EstadoMensajeRepository")
 * @ORM\Table(options={"comment":"Estado de un Mensajes"})
 */
class EstadoMensaje extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", mappedBy="estado")
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
}
