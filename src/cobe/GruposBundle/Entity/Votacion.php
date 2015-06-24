<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\GruposBundle\Repository\VotacionRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaVotacion", type="string")
 * @ORM\DiscriminatorMap({
 *      "Votacion"="cobe\GruposBundle\Entity\Votacion",
 *      "Publicacion"="\cobe\PaginasBundle\Entity\VotacionPublicacion"
 * })
 */
class Votacion extends Obj
{
    /**
     * @ORM\Column(type="json_array", nullable=false)
     */
    private $opciones;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="smallint", length=2, nullable=true, options={"unsigned":true})
     */
    private $opcionSeleccionada;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\VotacionGrupoPersona", mappedBy="votacion")
     */
    private $votacionesGrupoPersona;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\EstadoVotacion", inversedBy="votaciones")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->votacionesGrupoPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set opciones
     *
     * @param array $opciones
     * @return Votacion
     */
    public function setOpciones($opciones)
    {
        $this->opciones = $opciones;

        return $this;
    }

    /**
     * Get opciones
     *
     * @return array 
     */
    public function getOpciones()
    {
        return $this->opciones;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Votacion
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set opcionSeleccionada
     *
     * @param integer $opcionSeleccionada
     * @return Votacion
     */
    public function setOpcionSeleccionada($opcionSeleccionada)
    {
        $this->opcionSeleccionada = $opcionSeleccionada;

        return $this;
    }

    /**
     * Get opcionSeleccionada
     *
     * @return integer 
     */
    public function getOpcionSeleccionada()
    {
        return $this->opcionSeleccionada;
    }

    /**
     * Add votacionesGrupoPersona
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionesGrupoPersona
     * @return Votacion
     */
    public function addVotacionesGrupoPersona(\cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionesGrupoPersona)
    {
        $this->votacionesGrupoPersona[] = $votacionesGrupoPersona;

        return $this;
    }

    /**
     * Remove votacionesGrupoPersona
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionesGrupoPersona
     */
    public function removeVotacionesGrupoPersona(\cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionesGrupoPersona)
    {
        $this->votacionesGrupoPersona->removeElement($votacionesGrupoPersona);
    }

    /**
     * Get votacionesGrupoPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotacionesGrupoPersona()
    {
        return $this->votacionesGrupoPersona;
    }

    /**
     * Set estado
     *
     * @param \cobe\GruposBundle\Entity\EstadoVotacion $estado
     * @return Votacion
     */
    public function setEstado(\cobe\GruposBundle\Entity\EstadoVotacion $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\GruposBundle\Entity\EstadoVotacion 
     */
    public function getEstado()
    {
        return $this->estado;
    }
    
    public function getHerencias(){
        return array(
            'Votacion'=>'cobe\GruposBundle\Entity\Votacion',
            'Publicacion'=>'\cobe\PaginasBundle\Entity\VotacionPublicacion'
        );
    }
}
