<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;

/**
 * @ORM\Entity(repositoryClass="\cobe\CommonBundle\Repository\IdiomaRepository")
 * @ORM\Table(options={"comment":"Idiomas de los Usuarios y necesarios para la Oferta de trabajo"})
 */
class Idioma extends Obj
{
    /**
     * @ORM\OneToMany(targetEntity="cobe\CommonBundle\Entity\Traduccion", mappedBy="idiomaTraductor")
     */
    private $traduccionesTraductor;

    /**
     * @ORM\OneToMany(targetEntity="cobe\CommonBundle\Entity\Traduccion", mappedBy="idiomaTraducido")
     */
    private $traduccionesTraducido;
    /**
     * @ORM\OneToMany(targetEntity="cobe\CurriculosBundle\Entity\IdiomaPersona", mappedBy="idioma")
     */
    private $idiomaPersonas;

    /**
     * @ORM\ManyToMany(targetEntity="cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="idiomas")
     */
    private $ofertasLaborales;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->traduccionesTraductor = new \Doctrine\Common\Collections\ArrayCollection();
        $this->traduccionesTraducido = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idiomaPersonas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add traduccionesTraductor
     *
     * @param \cobe\CommonBundle\Entity\Traduccion $traduccionesTraductor
     * @return Idioma
     */
    public function addTraduccionesTraductor(\cobe\CommonBundle\Entity\Traduccion $traduccionesTraductor)
    {
        $this->traduccionesTraductor[] = $traduccionesTraductor;

        return $this;
    }

    /**
     * Remove traduccionesTraductor
     *
     * @param \cobe\CommonBundle\Entity\Traduccion $traduccionesTraductor
     */
    public function removeTraduccionesTraductor(\cobe\CommonBundle\Entity\Traduccion $traduccionesTraductor)
    {
        $this->traduccionesTraductor->removeElement($traduccionesTraductor);
    }

    /**
     * Get traduccionesTraductor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTraduccionesTraductor()
    {
        return $this->traduccionesTraductor;
    }

    /**
     * Add traduccionesTraducido
     *
     * @param \cobe\CommonBundle\Entity\Traduccion $traduccionesTraducido
     * @return Idioma
     */
    public function addTraduccionesTraducido(\cobe\CommonBundle\Entity\Traduccion $traduccionesTraducido)
    {
        $this->traduccionesTraducido[] = $traduccionesTraducido;

        return $this;
    }

    /**
     * Remove traduccionesTraducido
     *
     * @param \cobe\CommonBundle\Entity\Traduccion $traduccionesTraducido
     */
    public function removeTraduccionesTraducido(\cobe\CommonBundle\Entity\Traduccion $traduccionesTraducido)
    {
        $this->traduccionesTraducido->removeElement($traduccionesTraducido);
    }

    /**
     * Get traduccionesTraducido
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTraduccionesTraducido()
    {
        return $this->traduccionesTraducido;
    }

    /**
     * Add idiomaPersonas
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersonas
     * @return Idioma
     */
    public function addIdiomaPersona(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersonas)
    {
        $this->idiomaPersonas[] = $idiomaPersonas;

        return $this;
    }

    /**
     * Remove idiomaPersonas
     *
     * @param \cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersonas
     */
    public function removeIdiomaPersona(\cobe\CurriculosBundle\Entity\IdiomaPersona $idiomaPersonas)
    {
        $this->idiomaPersonas->removeElement($idiomaPersonas);
    }

    /**
     * Get idiomaPersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdiomaPersonas()
    {
        return $this->idiomaPersonas;
    }

    /**
     * Add ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     * @return Idioma
     */
    public function addOfertasLaborale(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales[] = $ofertasLaborales;

        return $this;
    }

    /**
     * Remove ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     */
    public function removeOfertasLaborale(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales->removeElement($ofertasLaborales);
    }

    /**
     * Get ofertasLaborales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertasLaborales()
    {
        return $this->ofertasLaborales;
    }
}
