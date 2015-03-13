<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\OfertaLaboralPersonaRepository")
 * @ORM\Table(
 *     options={"comment":"Personas asociadas a la Oferta Laboral"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="oferta_laboral_persona", columns={"persona","ofertaLaboral"})}
 * )
 */
class OfertaLaboralPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="ofertaLaboralesPersona")
     * @ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", inversedBy="ofertaLaboralPersonas")
     * @ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)
     */
    private $ofertaLaboral;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="\cobe\OfertasLaboralesBundle\Entity\RolOfertaLaboralPersona",
     *     inversedBy="ofertaLaboralPersona"
     * )
     * @ORM\JoinColumn(name="rolPersona", referencedColumnName="id", nullable=false)
     */
    private $rolOfertaLaboralPersona;

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
     * Set persona
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $persona
     * @return OfertaLaboralPersona
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
     * Set ofertaLaboral
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertaLaboral
     * @return OfertaLaboralPersona
     */
    public function setOfertaLaboral(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertaLaboral)
    {
        $this->ofertaLaboral = $ofertaLaboral;

        return $this;
    }

    /**
     * Get ofertaLaboral
     *
     * @return \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral 
     */
    public function getOfertaLaboral()
    {
        return $this->ofertaLaboral;
    }

    /**
     * Set rolOfertaLaboralPersona
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\RolOfertaLaboralPersona $rolOfertaLaboralPersona
     * @return OfertaLaboralPersona
     */
    public function setRolOfertaLaboralPersona(\cobe\OfertasLaboralesBundle\Entity\RolOfertaLaboralPersona $rolOfertaLaboralPersona)
    {
        $this->rolOfertaLaboralPersona = $rolOfertaLaboralPersona;

        return $this;
    }

    /**
     * Get rolOfertaLaboralPersona
     *
     * @return \cobe\OfertasLaboralesBundle\Entity\RolOfertaLaboralPersona 
     */
    public function getRolOfertaLaboralPersona()
    {
        return $this->rolOfertaLaboralPersona;
    }
}
