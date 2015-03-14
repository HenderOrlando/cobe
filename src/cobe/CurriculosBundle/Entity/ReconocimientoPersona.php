<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\ReconocimientoPersonaRepository")
 * @ORM\Table(
 *     options={"comment":"Reconocimientos de la Persona"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="persona_reconocimiento", columns={"persona","reconocimiento"})}
 * )
 */
class ReconocimientoPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoReconocimientoPersona",
     *     mappedBy="reconocimientoPersona"
     * )
     */
    private $archivos;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="reconocimientosPersona")
     * @ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\Reconocimiento", inversedBy="reconocimientoPersonas")
     * @ORM\JoinColumn(name="reconocimiento", referencedColumnName="id", nullable=false)
     */
    private $reconocimiento;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoReconocimientoPersona $archivos
     * @return ReconocimientoPersona
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoReconocimientoPersona $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoReconocimientoPersona $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoReconocimientoPersona $archivos)
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
     * Set persona
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $persona
     * @return ReconocimientoPersona
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
     * Set reconocimiento
     *
     * @param \cobe\CurriculosBundle\Entity\Reconocimiento $reconocimiento
     * @return ReconocimientoPersona
     */
    public function setReconocimiento(\cobe\CurriculosBundle\Entity\Reconocimiento $reconocimiento)
    {
        $this->reconocimiento = $reconocimiento;

        return $this;
    }

    /**
     * Get reconocimiento
     *
     * @return \cobe\CurriculosBundle\Entity\Reconocimiento 
     */
    public function getReconocimiento()
    {
        return $this->reconocimiento;
    }
}
