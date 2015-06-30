<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\PaginasBundle\Entity\Plantilla;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\PlantillaMensajeRepository")
 * @ORM\Table(options={"comment":"Plantillas para Publicaciones"})
 */
class PlantillaMensaje extends Plantilla
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", mappedBy="plantilla")
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
     * @return PlantillaMensaje
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
