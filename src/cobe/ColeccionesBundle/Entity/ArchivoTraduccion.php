<?php
namespace cobe\ColeccionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\ColeccionesBundle\Entity\Archivo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Archivo de una Traducción"})
 */
class ArchivoTraduccion extends Archivo
{
    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CommonBundle\Entity\Traduccion", inversedBy="archivos")
     * @ORM\JoinColumn(name="traduccion", referencedColumnName="id", nullable=false)
     */
    private $traduccion;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }

    /**
     * Set traduccion
     *
     * @param \cobe\CommonBundle\Entity\Traduccion $traduccion
     * @return ArchivoTraduccion
     */
    public function setTraduccion(\cobe\CommonBundle\Entity\Traduccion $traduccion)
    {
        $this->traduccion = $traduccion;

        return $this;
    }

    /**
     * Get traduccion
     *
     * @return \cobe\CommonBundle\Entity\Traduccion 
     */
    public function getTraduccion()
    {
        return $this->traduccion;
    }
}
