<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Estado de un Archivo"})
 */
class EstadoArchivo extends Estado
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\Archivo", mappedBy="estado")
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
     * Add archivosEstado
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivos
     * @return EstadoArchivo
     */
    public function addArchivos(\cobe\ColeccionesBundle\Entity\Archivo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivosEstado
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\Archivo $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivosEstado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivos()
    {
        return $this->archivos;
    }
}
