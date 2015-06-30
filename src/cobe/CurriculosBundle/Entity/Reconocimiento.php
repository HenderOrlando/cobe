<?php
namespace cobe\CurriculosBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj ;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CurriculosBundle\Repository\ReconocimientoRepository")
 * @ORM\Table(options={"comment":"Reconocimientos hechas a Personas"})
 */
class Reconocimiento extends Obj
{
    /**
     * @ORM\Column(type="date", nullable=false, options={"comment":"Fecha en que es dado el reconocimiento"})
     */
    private $fechaOtorgado;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\CurriculosBundle\Entity\ReconocimientoPersona", mappedBy="reconocimiento")
     */
    private $reconocimientoPersonas;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CurriculosBundle\Entity\TipoReconocimiento", inversedBy="reconocimientos")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", inversedBy="reconocimientos")
     * @ORM\JoinTable(
     *     name="empresa2reconocimiento",
     *     joinColumns={@ORM\JoinColumn(name="reconocimiento", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="empresa", referencedColumnName="id", nullable=false)}
     * )
     */
    private $empresas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->reconocimientoPersonas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaOtorgado
     *
     * @param \DateTime $fechaOtorgado
     * @return Reconocimiento
     */
    public function setFechaOtorgado($fechaOtorgado)
    {
        $this->fechaOtorgado = $fechaOtorgado;

        return $this;
    }

    /**
     * Get fechaOtorgado
     *
     * @return \DateTime 
     */
    public function getFechaOtorgado()
    {
        return $this->fechaOtorgado;
    }

    /**
     * Add reconocimientoPersonas
     *
     * @param \cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientoPersonas
     * @return Reconocimiento
     */
    public function addReconocimientoPersona(\cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientoPersonas)
    {
        $this->reconocimientoPersonas[] = $reconocimientoPersonas;

        return $this;
    }

    /**
     * Remove reconocimientoPersonas
     *
     * @param \cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientoPersonas
     */
    public function removeReconocimientoPersona(\cobe\CurriculosBundle\Entity\ReconocimientoPersona $reconocimientoPersonas)
    {
        $this->reconocimientoPersonas->removeElement($reconocimientoPersonas);
    }

    /**
     * Get reconocimientoPersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReconocimientoPersonas()
    {
        return $this->reconocimientoPersonas;
    }

    /**
     * Set tipo
     *
     * @param \cobe\CurriculosBundle\Entity\TipoReconocimiento $tipo
     * @return Reconocimiento
     */
    public function setTipo(\cobe\CurriculosBundle\Entity\TipoReconocimiento $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\CurriculosBundle\Entity\TipoReconocimiento 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     * @return Reconocimiento
     */
    public function addEmpresas(\cobe\UsuariosBundle\Entity\Empresa $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     */
    public function removeEmpresas(\cobe\UsuariosBundle\Entity\Empresa $empresas)
    {
        $this->empresas->removeElement($empresas);
    }

    /**
     * Get empresas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }
}
