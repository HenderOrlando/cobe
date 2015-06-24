<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\GruposBundle\Repository\GrupoPersonaRepository")
 * @ORM\Table(
 *     options={"comment":"Personas que pertenecen al Grupo"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="grupo_persona", columns={"grupo","persona"})}
 * )
 */
class GrupoPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\VotacionGrupoPersona", mappedBy="grupoPersona")
     */
    private $votacionGrupoPersona;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\Grupo", inversedBy="grupoPersonas")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)
     */
    private $grupo;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="gruposPersona")
     * @ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)
     */
    private $persona;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\RolGrupoPersona", inversedBy="grupoPersona")
     * @ORM\JoinColumn(name="rolPersona", referencedColumnName="id", nullable=false)
     */
    private $rolPersona;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\EstadoGrupoPersona", inversedBy="grupoPersona")
     * @ORM\JoinColumn(name="estadoPersona", referencedColumnName="id", nullable=false)
     */
    private $estadoPersona;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votacionGrupoPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add votacionGrupoPersona
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionGrupoPersona
     * @return GrupoPersona
     */
    public function addVotacionGrupoPersona(\cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionGrupoPersona)
    {
        $this->votacionGrupoPersona[] = $votacionGrupoPersona;

        return $this;
    }

    /**
     * Remove votacionGrupoPersona
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionGrupoPersona
     */
    public function removeVotacionGrupoPersona(\cobe\GruposBundle\Entity\VotacionGrupoPersona $votacionGrupoPersona)
    {
        $this->votacionGrupoPersona->removeElement($votacionGrupoPersona);
    }

    /**
     * Get votacionGrupoPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotacionGrupoPersona()
    {
        return $this->votacionGrupoPersona;
    }

    /**
     * Set grupo
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupo
     * @return GrupoPersona
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

    /**
     * Set persona
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $persona
     * @return GrupoPersona
     */
    public function setPersona(\cobe\UsuariosBundle\Entity\Persona $persona)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \cobe\UsuariosBundle\Entity\Persona 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set rolPersona
     *
     * @param \cobe\GruposBundle\Entity\RolGrupoPersona $rolPersona
     * @return GrupoPersona
     */
    public function setRolPersona(\cobe\GruposBundle\Entity\RolGrupoPersona $rolPersona)
    {
        $this->rolPersona = $rolPersona;

        return $this;
    }

    /**
     * Get rolPersona
     *
     * @return \cobe\GruposBundle\Entity\RolGrupoPersona 
     */
    public function getRolPersona()
    {
        return $this->rolPersona;
    }

    /**
     * Set estadoPersona
     *
     * @param \cobe\GruposBundle\Entity\EstadoGrupoPersona $estadoPersona
     * @return GrupoPersona
     */
    public function setEstadoPersona(\cobe\GruposBundle\Entity\EstadoGrupoPersona $estadoPersona)
    {
        $this->estadoPersona = $estadoPersona;

        return $this;
    }

    /**
     * Get estadoPersona
     *
     * @return \cobe\GruposBundle\Entity\EstadoGrupoPersona 
     */
    public function getEstadoPersona()
    {
        return $this->estadoPersona;
    }
}
