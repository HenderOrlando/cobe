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
    private $votaciones;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\Grupo", inversedBy="personas")
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
    private $rol;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="cobe\GruposBundle\Entity\EstadoGrupoPersona", inversedBy="grupoPersona")
     * @ORM\JoinColumn(name="estadoPersona", referencedColumnName="id", nullable=false)
     */
    private $estado;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add votaciones
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $votaciones
     * @return GrupoPersona
     */
    public function addVotaciones(\cobe\GruposBundle\Entity\VotacionGrupoPersona $votaciones)
    {
        $this->votaciones[] = $votaciones;

        return $this;
    }

    /**
     * Remove votacion
     *
     * @param \cobe\GruposBundle\Entity\VotacionGrupoPersona $votaciones
     */
    public function removeVotaciones(\cobe\GruposBundle\Entity\VotacionGrupoPersona $votaciones)
    {
        $this->votaciones->removeElement($votaciones);
    }

    /**
     * Get votacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotaciones()
    {
        return $this->votaciones;
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
     * Set rol
     *
     * @param \cobe\GruposBundle\Entity\RolGrupoPersona $rol
     * @return GrupoPersona
     */
    public function setRol(\cobe\GruposBundle\Entity\RolGrupoPersona $rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \cobe\GruposBundle\Entity\RolGrupoPersona 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set estado
     *
     * @param \cobe\GruposBundle\Entity\EstadoGrupoPersona $estado
     * @return GrupoPersona
     */
    public function setEstado(\cobe\GruposBundle\Entity\EstadoGrupoPersona $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\GruposBundle\Entity\EstadoGrupoPersona 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
