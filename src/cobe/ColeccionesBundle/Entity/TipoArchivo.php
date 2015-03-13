<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;

/**
 * @ORM\Entity
 */
class TipoArchivo extends Tipo
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\Archivo", mappedBy="tipo")
     */
    private $archivosTipo;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosTipo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add archivosTipo
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivosTipo
     * @return TipoArchivo
     */
    public function addArchivosTipo(\cobe\ColeccionesBundle\Entity\Archivo $archivosTipo)
    {
        $this->archivosTipo[] = $archivosTipo;

        return $this;
    }

    /**
     * Remove archivosTipo
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivosTipo
     */
    public function removeArchivosTipo(\cobe\ColeccionesBundle\Entity\Archivo $archivosTipo)
    {
        $this->archivosTipo->removeElement($archivosTipo);
    }

    /**
     * Get archivosTipo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosTipo()
    {
        return $this->archivosTipo;
    }
}
