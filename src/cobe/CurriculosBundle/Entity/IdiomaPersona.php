<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\IdiomaPersonaRepository")
 * @ORM\Table(
 *     options={"comment":"Idioma usado por la Persona"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idioma_persona", columns={"idioma","persona"})}
 * )
 */
class IdiomaPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CommonBundle\Entity\Idioma", inversedBy="idiomaPersonas")
     * @ORM\JoinColumn(name="idioma", referencedColumnName="id", nullable=false)
     */
    private $idioma;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="idiomasPersona")
     * @ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)
     */
    private $persona;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\NivelIdioma", inversedBy="idiomaPersona")
     * @ORM\JoinColumn(name="nivelIdioma", referencedColumnName="id", nullable=false)
     */
    private $nivelIdioma;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idioma
     *
     * @param \cobe\CommonBundle\Entity\Idioma $idioma
     * @return IdiomaPersona
     */
    public function setIdioma(\cobe\CommonBundle\Entity\Idioma $idioma)
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * Get idioma
     *
     * @return \cobe\CommonBundle\Entity\Idioma 
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set persona
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $persona
     * @return IdiomaPersona
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
     * Set nivelIdioma
     *
     * @param \cobe\CurriculosBundle\Entity\NivelIdioma $nivelIdioma
     * @return IdiomaPersona
     */
    public function setNivelIdioma(\cobe\CurriculosBundle\Entity\NivelIdioma $nivelIdioma)
    {
        $this->nivelIdioma = $nivelIdioma;

        return $this;
    }

    /**
     * Get nivelIdioma
     *
     * @return \cobe\CurriculosBundle\Entity\NivelIdioma 
     */
    public function getNivelIdioma()
    {
        return $this->nivelIdioma;
    }
}
