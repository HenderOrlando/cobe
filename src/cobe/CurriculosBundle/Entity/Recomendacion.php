<?php
namespace cobe\CurriculosBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\RecomendacionRepository")
 * @ORM\Table(
 *     options={"comment":"Recomendaciones e el sistema"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="recomienda_recomendado", columns={"recomienda","recomendado"})}
 * )
 */
class Recomendacion extends Objeto
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoRecomendacion", mappedBy="recomendacion")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="recomendados")
     * @ORM\JoinColumn(name="recomienda", referencedColumnName="id", nullable=false)
     */
    private $recomienda;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="recomendaciones")
     * @ORM\JoinColumn(name="recomendado", referencedColumnName="id", nullable=false)
     */
    private $recomendado;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\TipoRecomendacion", inversedBy="recomendaciones")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoRecomendacion $archivos
     * @return Recomendacion
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoRecomendacion $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoRecomendacion $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoRecomendacion $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * Set recomienda
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $recomienda
     * @return Recomendacion
     */
    public function setRecomienda(\cobe\UsuariosBundle\Entity\Persona $recomienda)
    {
        $this->recomienda = $recomienda;

        return $this;
    }

    /**
     * Get recomienda
     *
     * @return \cobe\UsuariosBundle\Entity\Persona 
     */
    public function getRecomienda()
    {
        return $this->recomienda;
    }

    /**
     * Set recomendado
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $recomendado
     * @return Recomendacion
     */
    public function setRecomendado(\cobe\UsuariosBundle\Entity\Persona $recomendado)
    {
        $this->recomendado = $recomendado;

        return $this;
    }

    /**
     * Get recomendado
     *
     * @return \cobe\UsuariosBundle\Entity\Persona 
     */
    public function getRecomendado()
    {
        return $this->recomendado;
    }

    /**
     * Set tipo
     *
     * @param \cobe\CurriculosBundle\Entity\TipoRecomendacion $tipo
     * @return Recomendacion
     */
    public function setTipo(\cobe\CurriculosBundle\Entity\TipoRecomendacion $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\CurriculosBundle\Entity\TipoRecomendacion 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
