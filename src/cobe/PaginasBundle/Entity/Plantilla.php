<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaPlantilla", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "Plantilla"="\cobe\PaginasBundle\Entity\Plantilla",
 *     "Usuario"="\cobe\PaginasBundle\Entity\PlantillaUsuario",
 *     "Empresa"="\cobe\PaginasBundle\Entity\PlantillaEmpresa",
 *     "Grupo"="\cobe\PaginasBundle\Entity\PlantillaGrupo",
 *     "Mensaje"="\cobe\PaginasBundle\Entity\PlantillaMensaje",
 *     "Publicacion"="\cobe\PaginasBundle\Entity\PlantillaPublicacion"
 * }
 * )
 */
class Plantilla extends Objeto
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Plantilla", mappedBy="plantilla")
     */
    private $subplantillas;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoPlantilla", mappedBy="plantilla")
     */
    private $archivos;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Plantilla", inversedBy="subplantillas")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=false)
     */
    private $plantilla;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\TipoPlantilla", inversedBy="plantillas")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\EstadoPlantilla", inversedBy="plantillas")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subplantillas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add subplantillas
     *
     * @param \cobe\PaginasBundle\Entity\Plantilla $subplantillas
     * @return Plantilla
     */
    public function addSubplantilla(\cobe\PaginasBundle\Entity\Plantilla $subplantillas)
    {
        $this->subplantillas[] = $subplantillas;

        return $this;
    }

    /**
     * Remove subplantillas
     *
     * @param \cobe\PaginasBundle\Entity\Plantilla $subplantillas
     */
    public function removeSubplantilla(\cobe\PaginasBundle\Entity\Plantilla $subplantillas)
    {
        $this->subplantillas->removeElement($subplantillas);
    }

    /**
     * Get subplantillas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubplantillas()
    {
        return $this->subplantillas;
    }

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoPlantilla $archivos
     * @return Plantilla
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoPlantilla $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoPlantilla $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoPlantilla $archivos)
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
     * Set plantilla
     *
     * @param \cobe\PaginasBundle\Entity\Plantilla $plantilla
     * @return Plantilla
     */
    public function setPlantilla(\cobe\PaginasBundle\Entity\Plantilla $plantilla)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \cobe\PaginasBundle\Entity\Plantilla 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * Set tipo
     *
     * @param \cobe\PaginasBundle\Entity\TipoPlantilla $tipo
     * @return Plantilla
     */
    public function setTipo(\cobe\PaginasBundle\Entity\TipoPlantilla $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\PaginasBundle\Entity\TipoPlantilla 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set estado
     *
     * @param \cobe\PaginasBundle\Entity\EstadoPlantilla $estado
     * @return Plantilla
     */
    public function setEstado(\cobe\PaginasBundle\Entity\EstadoPlantilla $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\PaginasBundle\Entity\EstadoPlantilla 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
