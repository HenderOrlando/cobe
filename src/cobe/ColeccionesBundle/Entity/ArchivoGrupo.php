<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;

/**
 * @ORM\Entity
 */
class ArchivoGrupo extends Archivo
{
    /**
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\Grupo", inversedBy="archivos")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)
     */
    private $grupo;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set grupo
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupo
     * @return ArchivoGrupo
     */
    public function setGrupo(\cobe\GruposBundle\Entity\Grupo $grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \cobe\GruposBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
}
