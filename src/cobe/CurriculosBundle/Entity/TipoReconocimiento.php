<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\TipoReconocimientoRepository")
 * @ORM\Table(options={"comment":"Tipos de Reconocimientos"})
 */
class TipoReconocimiento extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Reconocimiento", mappedBy="tipo")
     */
    private $reconocimientos;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->reconocimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add reconocimientos
     *
     * @param \cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos
     * @return TipoReconocimiento
     */
    public function addReconocimientos(\cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos)
    {
        $this->reconocimientos[] = $reconocimientos;

        return $this;
    }

    /**
     * Remove reconocimientos
     *
     * @param \cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos
     */
    public function removeReconocimientos(\cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos)
    {
        $this->reconocimientos->removeElement($reconocimientos);
    }

    /**
     * Get reconocimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReconocimientos()
    {
        return $this->reconocimientos;
    }
}
