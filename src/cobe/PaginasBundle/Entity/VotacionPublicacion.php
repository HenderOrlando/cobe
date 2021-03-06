<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\GruposBundle\Entity\Votacion;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\VotacionPublicacionRepository")
 * @ORM\Table(options={"comment":"Votaciones para Publicaciones"})
 */
class VotacionPublicacion extends Votacion
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\TipoVotacionPublicacion", inversedBy="votaciones")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", inversedBy="votaciones")
     * @ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=false)
     */
    private $publicacion;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set tipo
     *
     * @param \cobe\PaginasBundle\Entity\TipoVotacionPublicacion $tipo
     * @return VotacionPublicacion
     */
    public function setTipo(\cobe\PaginasBundle\Entity\TipoVotacionPublicacion $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\PaginasBundle\Entity\TipoVotacionPublicacion 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set publicacion
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacion
     * @return VotacionPublicacion
     */
    public function setPublicacion(\cobe\PaginasBundle\Entity\Publicacion $publicacion)
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    /**
     * Get publicacion
     *
     * @return \cobe\PaginasBundle\Entity\Publicacion 
     */
    public function getPublicacion()
    {
        return $this->publicacion;
    }

}
