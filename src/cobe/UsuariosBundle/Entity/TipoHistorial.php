<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Tipo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\TipoHistorialRepository")
 * @ORM\Table(options={"comment":"Tipos de Historiales"})
 */
class TipoHistorial extends Tipo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\UsuariosBundle\Entity\Historial", mappedBy="accion")
     */
    private $historiales;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->historiales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add historiales
     *
     * @param \cobe\UsuariosBundle\Entity\Historial $historiales
     * @return TipoHistorial
     */
    public function addHistoriale(\cobe\UsuariosBundle\Entity\Historial $historiales)
    {
        $this->historiales[] = $historiales;

        return $this;
    }

    /**
     * Remove historiales
     *
     * @param \cobe\UsuariosBundle\Entity\Historial $historiales
     */
    public function removeHistoriale(\cobe\UsuariosBundle\Entity\Historial $historiales)
    {
        $this->historiales->removeElement($historiales);
    }

    /**
     * Get historiales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistoriales()
    {
        return $this->historiales;
    }


}
