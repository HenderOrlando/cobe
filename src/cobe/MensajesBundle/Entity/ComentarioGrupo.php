<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\MensajesBundle\Entity\Comentario;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Comentarios a un Grupo"})
 */
class ComentarioGrupo extends Comentario
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\Grupo", inversedBy="comentarios")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id")
     */
    private $grupo;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set grupo
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupo
     * @return ComentarioGrupo
     */
    public function setGrupo(\cobe\GruposBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \cobe\GruposBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
}
