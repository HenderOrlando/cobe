<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\TipoVotacionRepository")
 * @ORM\Table(options={"comment":"Tipos de Votaciones"})
 */
class TipoVotacionPublicacion extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\VotacionPublicacion", mappedBy="tipo")
     */
    private $votaciones;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->votaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add votaciones
     *
     * @param \cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones
     * @return TipoVotacionPublicacion
     */
    public function addVotacione(\cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones)
    {
        $this->votaciones[] = $votaciones;

        return $this;
    }

    /**
     * Remove votaciones
     *
     * @param \cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones
     */
    public function removeVotacione(\cobe\PaginasBundle\Entity\VotacionPublicacion $votaciones)
    {
        $this->votaciones->removeElement($votaciones);
    }

    /**
     * Get votaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotaciones()
    {
        return $this->votaciones;
    }

}
