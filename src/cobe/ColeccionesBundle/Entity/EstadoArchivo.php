<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Estado;

/**
 * @ORM\Entity
 */
class EstadoArchivo extends Estado
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\Archivo", mappedBy="estado")
     */
    private $archivosEstado;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosEstado = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivosEstado
     * @return EstadoArchivo
     */
    public function addArchivosEstado(\cobe\ColeccionesBundle\Entity\Archivo $archivosEstado)
    {
        $this->archivosEstado[] = $archivosEstado;

        return $this;
    }

    /**
     * Remove archivosEstado
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivosEstado
     */
    public function removeArchivosEstado(\cobe\ColeccionesBundle\Entity\Archivo $archivosEstado)
    {
        $this->archivosEstado->removeElement($archivosEstado);
    }

    /**
     * Get archivosEstado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivosEstado()
    {
        return $this->archivosEstado;
    }
}
