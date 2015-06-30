<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Tipo de un Archivo"})
 */
class TipoArchivo extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\Archivo", mappedBy="tipo")
     */
    private $archivos;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivos
     * @return TipoArchivo
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\Archivo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivosTipo
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\Archivo $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivosTipo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivos()
    {
        return $this->archivos;
    }
}
