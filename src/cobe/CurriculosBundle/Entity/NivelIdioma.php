<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\NivelIdiomaRepository")
 * @ORM\Table(options={"comment":"Nivel de idioma de una Persona"})
 */
class NivelIdioma extends Etiqueta
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\IdiomaPersona", mappedBy="nivelIdioma")
     */
    private $idiomaPersona;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaInteres", mappedBy="interes")
     */
    private $estadisticas;

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
     * Add estadisticasNivelIdioma
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaNivelIdioma $estadisticas
     * @return Interes
     */
    public function addEstadisticas($estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticasNivelIdioma
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaNivelIdioma $estadisticas
     */
    public function removeEstadisticas($estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticasNivelIdioma
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
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
