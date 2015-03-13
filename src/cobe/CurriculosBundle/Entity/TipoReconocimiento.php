<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\TipoReconocimientoRepository")
 */
class TipoReconocimiento extends Tipo
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\Reconocimiento", mappedBy="tipo")
     */
    private $reconocimientos;
    /**
     * Constructor
     */
    public function __construct()
    {
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
    public function addReconocimiento(\cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos)
    {
        $this->reconocimientos[] = $reconocimientos;

        return $this;
    }

    /**
     * Remove reconocimientos
     *
     * @param \cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos
     */
    public function removeReconocimiento(\cobe\CurriculosBundle\Entity\Reconocimiento $reconocimientos)
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
