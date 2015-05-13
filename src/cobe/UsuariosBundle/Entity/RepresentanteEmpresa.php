<?php
namespace cobe\UsuariosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="cobe\UsuariosBundle\Repository\RepresentanteEmpresaRepository")
 * @ORM\Table(
 *     options={"comment":"Representante de Empresa en el sistema"}
 * )
 */
class RepresentanteEmpresa
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $actual;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", inversedBy="representantes")
     * @ORM\JoinColumn(name="empresa", referencedColumnName="id", nullable=false)
     */
    private $empresa;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Persona", inversedBy="empresas")
     * @ORM\JoinColumn(name="persona", referencedColumnName="id", nullable=false)
     */
    private $persona;


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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return RepresentanteEmpresa
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return RepresentanteEmpresa
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
     * Set actual
     *
     * @param boolean $actual
     * @return RepresentanteEmpresa
     */
    public function setActual($actual)
    {
        $this->actual = $actual;

        return $this;
    }

    /**
     * Get actual
     *
     * @return boolean
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * Set empresa
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresa
     * @return RepresentanteEmpresa
     */
    public function setEmpresa(\cobe\UsuariosBundle\Entity\Empresa $empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \cobe\UsuariosBundle\Entity\Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set persona
     *
     * @param \cobe\UsuariosBundle\Entity\Persona $persona
     * @return RepresentanteEmpresa
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
}
