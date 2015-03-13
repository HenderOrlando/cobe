<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\PaisRepository")
 * @ORM\Table(options={"comment":"Países donde están las Ciudades de las Empresas y las Personas"})
 */
class Pais extends Obj
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\CommonBundle\Entity\Ciudad", mappedBy="pais")
     */
    private $ciudades;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->ciudades = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add ciudades
     *
     * @param \cobe\CommonBundle\Entity\Ciudad $ciudades
     * @return Pais
     */
    public function addCiudade(\cobe\CommonBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades[] = $ciudades;

        return $this;
    }

    /**
     * Remove ciudades
     *
     * @param \cobe\CommonBundle\Entity\Ciudad $ciudades
     */
    public function removeCiudade(\cobe\CommonBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades->removeElement($ciudades);
    }

    /**
     * Get ciudades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCiudades()
    {
        return $this->ciudades;
    }
}
