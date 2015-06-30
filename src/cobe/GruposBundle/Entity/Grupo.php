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
    private $personas;

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
    private $estadisticas;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\PlantillaGrupo", inversedBy="grupos")
     * @ORM\JoinColumn(name="plantilla", referencedColumnName="id", nullable=true)
     */
    private $plantilla;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Interes", inversedBy="grupos")
     * @ORM\JoinTable(
     *     name="interes2grupo",
     *     joinColumns={@ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="interes", referencedColumnName="id", nullable=false)}
     * )
     */
    private $intereses;

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
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->intereses = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add personas
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $personas
     * @return Grupo
     */
    public function addPersonas(\cobe\GruposBundle\Entity\GrupoPersona $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \cobe\GruposBundle\Entity\GrupoPersona $personas
     */
    public function removePersonas(\cobe\GruposBundle\Entity\GrupoPersona $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Add comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios
     * @return Grupo
     */
    public function addComentarios(\cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios
     */
    public function removeComentarios(\cobe\MensajesBundle\Entity\ComentarioGrupo $comentarios)
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
    public function addArchivos(\cobe\ColeccionesBundle\Entity\ArchivoGrupo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoGrupo $archivos
     */
    public function removeArchivos(\cobe\ColeccionesBundle\Entity\ArchivoGrupo $archivos)
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
     * Add estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticas
     * @return Grupo
     */
    public function addEstadisticas(\cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticas
     */
    public function removeEstadisticas(\cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
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
     * Add intereses
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $intereses
     * @return Grupo
     */
    public function addIntereses(\cobe\CurriculosBundle\Entity\Interes $intereses)
    {
        $this->intereses[] = $intereses;

        return $this;
    }

    /**
     * Remove intereses
     *
     * @param \cobe\CurriculosBundle\Entity\Interes $intereses
     */
    public function removeIntereses(\cobe\CurriculosBundle\Entity\Interes $intereses)
    {
        $this->intereses->removeElement($intereses);
    }

    /**
     * Get intereses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIntereses()
    {
        return $this->intereses;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return Grupo
     */
    public function addEtiquetas(\cobe\CommonBundle\Entity\Etiqueta $etiquetas)
    {
        $this->etiquetas[] = $etiquetas;

        return $this;
    }

    public function addEtiqueta(\cobe\CommonBundle\Entity\Etiqueta $etiquetas){
        return $this->addEtiquetas($etiquetas);
    }

    /**
     * Remove etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     */
    public function removeEtiquetas(\cobe\CommonBundle\Entity\Etiqueta $etiquetas)
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
