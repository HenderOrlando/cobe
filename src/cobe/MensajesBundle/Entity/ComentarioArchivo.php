<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\MensajesBundle\Entity\Comentario;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Comentarios a un Archivo"})
 */
class ComentarioArchivo extends Comentario
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\ColeccionesBundle\Entity\Archivo", inversedBy="comentarios")
     * @ORM\JoinColumn(name="archivo", referencedColumnName="id")
     */
    private $archivo;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set archivo
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivo
     * @return ComentarioArchivo
     */
    public function setArchivo(\cobe\ColeccionesBundle\Entity\Archivo $archivo = null)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return \cobe\ColeccionesBundle\Entity\Archivo 
     */
    public function getArchivo()
    {
        return $this->archivo;
    }
}
