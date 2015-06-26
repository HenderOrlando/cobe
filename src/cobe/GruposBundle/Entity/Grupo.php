<?php
namespace cobe\GruposBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\GruposBundle\Repository\GrupoRepository")
 * @ORM\Table(options={"comment":"Grupos en el sistema"})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaGrupo", type="string")
 * @ORM\DiscriminatorMap({
 *      "Grupo"="cobe\GruposBundle\Entity\Grupo",
 *      "Editor"="\cobe\PaginasBundle\Entity\GrupoEditor"
 * })
 */
class Grupo extends Obj
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="cobe\GruposBundle\Entity\GrupoPersona", mappedBy="grupo")
     */
    private $personasGrupo;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioGrupo", mappedBy="grupo")
     */
    private $comentarios;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoGrupo", mappedBy="grupo")
     */
    private $archivos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaGrupo", mappedBy="grupo")
     */
    private $estadisticasGrupo;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaGrupo", inversedBy="grupos")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantilla;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Interes", inversedBy="gruposInteres")
     * @ORM\JoinTable(
     *     name="interes2grupo",
     *     joinColumns={@ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="interes", referencedColumnName="id", nullable=false)}
     * )
     */
    private $interesesGrupo;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="grupos")
     * @ORM\JoinTable(
     *     name="etiqueta2grupo",
     *     joinColumns={@ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    protected $etiquetas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->personasGrupo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasGrupo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->interesesGrupo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add grupoPersonas
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $personasGrupo
     * @return Grupo
     */
    public function addPersonasGrupo(\cobe\GruposBundle\Entity\GrupoPersona $personasGrupo)
    {
        $this->personasGrupo[] = $personasGrupo;

        return $this;
    }

    /**
     * Remove grupoPersonas
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $personasGrupo
     */
    public function removePersonasGrupo(\cobe\GruposBundle\Entity\GrupoPersona $personasGrupo)
    {
        $this->personasGrupo->removeElement($personasGrupo);
    }

    /**
     * Get grupoPersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonasGrupo()
    {
        return $this->personasGrupo;
    }

    /**
     * Add comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios
     * @return Grupo
     */
    public function addComentario(\cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios
     */
    public function removeComentario(\cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoGrupo $archivos
     * @return Grupo
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoGrupo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoGrupo $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoGrupo $archivos)
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
     * Add estadisticasGrupo
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticasGrupo
     * @return Grupo
     */
    public function addEstadisticasGrupo(\cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticasGrupo)
    {
        $this->estadisticasGrupo[] = $estadisticasGrupo;

        return $this;
    }

    /**
     * Remove estadisticasGrupo
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticasGrupo
     */
    public function removeEstadisticasGrupo(\cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticasGrupo)
    {
        $this->estadisticasGrupo->removeElement($estadisticasGrupo);
    }

    /**
     * Get estadisticasGrupo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasGrupo()
    {
        return $this->estadisticasGrupo;
    }

    /**
     * Set plantilla
     *
     * @param \cobe\PaginasBundle\Entity\PlantillaGrupo $plantilla
     * @return Grupo
     */
    public function setPlantilla(\cobe\PaginasBundle\Entity\PlantillaGrupo $plantilla)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \cobe\PaginasBundle\Entity\PlantillaGrupo 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * Add interesesGrupo
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $interesesGrupo
     * @return Grupo
     */
    public function addInteresesGrupo(\cobe\CurriculosBundle\Entity\Interes $interesesGrupo)
    {
        $this->interesesGrupo[] = $interesesGrupo;

        return $this;
    }

    /**
     * Remove interesesGrupo
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $interesesGrupo
     */
    public function removeInteresesGrupo(\cobe\CurriculosBundle\Entity\Interes $interesesGrupo)
    {
        $this->interesesGrupo->removeElement($interesesGrupo);
    }

    /**
     * Get interesesGrupo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInteresesGrupo()
    {
        return $this->interesesGrupo;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return Grupo
     */
    public function addEtiqueta(\cobe\CommonBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas[] = $etiquetas;

        return $this;
    }

    /**
     * Remove etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     */
    public function removeEtiqueta(\cobe\CommonBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas->removeElement($etiquetas);
    }

    /**
     * Get etiquetas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEtiquetas()
    {
        return $this->etiquetas;
    }
    
    public function getHerencias(){
        return array(
            'Grupo' =>'cobe\GruposBundle\Entity\Grupo',
            'Editor'=>'\cobe\PaginasBundle\Entity\GrupoEditor'
        );
    }
}
