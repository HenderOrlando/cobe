<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\MensajesBundle\Repository\EstadoDestinatarioRepository")
 * @ORM\Table(options={"comment":"Estados de los Mensajes enviados a los Destinatarios"})
 */
class EstadoDestinatario extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\Destinatario", mappedBy="estadoDestinatario")
     */
    private $destinatarios;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->destinatarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadoDestinatario
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
}
