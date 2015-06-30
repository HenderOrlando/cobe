<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment":"Opción seleccionada de una Votacion por una Persona de un Grupo"})
 */
class VotacionGrupoPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\GrupoPersona", inversedBy="votaciones")
     * @ORM\JoinColumn(name="grupoPersona", referencedColumnName="id", nullable=false)
     */
    private $grupoPersona;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\Votacion", inversedBy="votacionesGrupoPersona")
     * @ORM\JoinColumn(name="votacion", referencedColumnName="id", nullable=false)
     */
    private $votacion;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\GruposBundle\Entity\Opcion", inversedBy="selecciones")
     * @ORM\JoinTable(
     *     name="opcion2votacionGrupoPersona",
     *     joinColumns={@ORM\JoinColumn(name="votacionGrupoPersona", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="opcion", referencedColumnName="id", nullable=false)}
     * )
     */
    private $seleccionados;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->seleccionados = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return guid 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set grupoPersona
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $grupoPersona
     * @return VotacionGrupoPersona
     */
    public function setGrupoPersona(\cobe\GruposBundle\Entity\GrupoPersona $grupoPersona)
    {
        $this->grupoPersona = $grupoPersona;

        return $this;
    }

    /**
     * Get grupoPersona
     *
     * @return \cobe\GruposBundle\Entity\GrupoPersona 
     */
    public function getGrupoPersona()
    {
        return $this->grupoPersona;
    }

    /**
     * Set votacion
     *
     * @param \cobe\GruposBundle\Entity\Votacion $votacion
     * @return VotacionGrupoPersona
     */
    public function setVotacion(\cobe\GruposBundle\Entity\Votacion $votacion)
    {
        $this->votacion = $votacion;

        return $this;
    }

    /**
     * Get votacion
     *
     * @return \cobe\GruposBundle\Entity\Votacion 
     */
    public function getVotacion()
    {
        return $this->votacion;
    }

    /**
     * Add votacionesGrupoPersona
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $seleccionados
     * @return Votacion
     */
    public function addSeleccionados(\cobe\GruposBundle\Entity\Opcion $seleccionados)
    {
        $this->seleccionados[] = $seleccionados;

        return $this;
    }

    /**
     * Remove seleccionados
     *
     * @param \cobe\GruposBundle\Entity\Opcion $seleccionados
     */
    public function removeSeleccionados(\cobe\GruposBundle\Entity\Opcion $seleccionados)
    {
        $this->seleccionados->removeElement($seleccionados);
    }

    /**
     * Get seleccionadoa
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionados()
    {
        return $this->seleccionados;
    }
}
