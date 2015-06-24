<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
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
     * @ORM\Column(type="smallint", length=2, nullable=false, options={"unsigned":true})
     */
    private $opcion;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\GrupoPersona", inversedBy="votacionGrupoPersona")
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
     * Get id
     *
     * @return guid 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set opcion
     *
     * @param integer $opcion
     * @return VotacionGrupoPersona
     */
    public function setOpcion($opcion)
    {
        $this->opcion = $opcion;

        return $this;
    }

    /**
     * Get opcion
     *
     * @return integer 
     */
    public function getOpcion()
    {
        return $this->opcion;
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
}
