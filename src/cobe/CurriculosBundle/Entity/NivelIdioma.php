<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\NivelIdiomaRepository")
 */
class NivelIdioma extends Etiqueta
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\IdiomaPersona", mappedBy="nivelIdioma")
     */
    private $idiomaPersona;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->idiomaPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add idiomaPersona
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersona
     * @return NivelIdioma
     */
    public function addIdiomaPersona(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersona)
    {
        $this->idiomaPersona[] = $idiomaPersona;

        return $this;
    }

    /**
     * Remove idiomaPersona
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersona
     */
    public function removeIdiomaPersona(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersona)
    {
        $this->idiomaPersona->removeElement($idiomaPersona);
    }

    /**
     * Get idiomaPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdiomaPersona()
    {
        return $this->idiomaPersona;
    }
}
