<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto as Obj;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\OfertasLaboralesBundle\Repository\OfertaLaboralRepository")
 * @ORM\Table(
 *     options={"comment":"Ofertas Laborales de los Usuarios"},
 *     indexes={@ORM\Index(name="usuario", columns={"usuario"})}
 * )
 */
class OfertaLaboral extends Obj
{
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"comment":"Fecha limite de la oferta laboral."})
     */
    private $fechaLimite;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona", mappedBy="ofertaLaboral")
     */
    private $personasOfertaLaboral;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioOfertaLaboral", mappedBy="ofertaLaboral")
     */
    private $comentarios;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoTrabajo", mappedBy="ofertaLaboral")
     */
    private $archivos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral", mappedBy="ofertaLaboral")
     */
    private $estadisticas;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\OfertasLaboralesBundle\Entity\EstadoOfertaLaboral", inversedBy="ofertasLaboralesEstado")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estadoOfertasLaborales;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\OfertasLaboralesBundle\Entity\TipoOfertaLaboral", inversedBy="ofertasLaboralesTipo")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipoOfertasLaborales;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="ofertasLaborales")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuario;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", inversedBy="ofertasLaborales")
     * @ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=true)
     */
    private $publicacion;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Aptitud", inversedBy="ofertasLaboralesAptitud")
     * @ORM\JoinTable(
     *     name="Aptitud2OfertaLaboral",
     *     joinColumns={@ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="aptitud", referencedColumnName="id", nullable=false)}
     * )
     */
    private $aptitudes;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="ofertasLaborales")
     * @ORM\JoinTable(
     *     name="Etiqueta2OfertaLaboral",
     *     joinColumns={@ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    protected $etiquetas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Idioma", inversedBy="ofertasLaborales")
     * @ORM\JoinTable(
     *     name="Idioma2OfertaLaboral",
     *     joinColumns={@ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="idioma", referencedColumnName="id", nullable=false)}
     * )
     */
    private $idiomas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Ciudad", inversedBy="ofertasLaborales")
     * @ORM\JoinTable(
     *     name="Ciudad2OfertaLaboral",
     *     joinColumns={@ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ciudad", referencedColumnName="id", nullable=false)}
     * )
     */
    private $ciudades;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->personasOfertaLaboral = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aptitudes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idiomas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ciudades = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add ofertaLaboralPersonas
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $personasOfertaLaboral
     * @return OfertaLaboral
     */
    public function addOfertaLaboralPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $personasOfertaLaboral)
    {
        $this->personasOfertaLaboral[] = $personasOfertaLaboral;

        return $this;
    }

    /**
     * Remove ofertaLaboralPersonas
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $personasOfertaLaboral
     */
    public function removeOfertaLaboralPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $personasOfertaLaboral)
    {
        $this->personasOfertaLaboral->removeElement($personasOfertaLaboral);
    }

    /**
     * Get ofertaLaboralPersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertaLaboralPersonas()
    {
        return $this->personasOfertaLaboral;
    }

    /**
     * Add comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioOfertaLaboral $comentarios
     * @return OfertaLaboral
     */
    public function addComentario(\cobe\MensajesBundle\Entity\ComentarioOfertaLaboral $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \cobe\MensajesBundle\Entity\ComentarioOfertaLaboral $comentarios
     */
    public function removeComentario(\cobe\MensajesBundle\Entity\ComentarioOfertaLaboral $comentarios)
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
     * @param \cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivos
     * @return OfertaLaboral
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivos)
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
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticas
     * @return OfertaLaboral
     */
    public function addEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticas
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticas
     */
    public function removeEstadistica(\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticas)
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
     * Set estadoOfertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\EstadoOfertaLaboral $estadoOfertasLaborales
     * @return OfertaLaboral
     */
    public function setEstadoOfertasLaborales(\cobe\OfertasLaboralesBundle\Entity\EstadoOfertaLaboral $estadoOfertasLaborales)
    {
        $this->estadoOfertasLaborales = $estadoOfertasLaborales;

        return $this;
    }

    /**
     * Get estadoOfertasLaborales
     *
     * @return \cobe\OfertasLaboralesBundle\Entity\EstadoOfertaLaboral 
     */
    public function getEstadoOfertasLaborales()
    {
        return $this->estadoOfertasLaborales;
    }

    /**
     * Set tipoOfertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\TipoOfertaLaboral $tipoOfertasLaborales
     * @return OfertaLaboral
     */
    public function setTipoOfertasLaborales(\cobe\OfertasLaboralesBundle\Entity\TipoOfertaLaboral $tipoOfertasLaborales)
    {
        $this->tipoOfertasLaborales = $tipoOfertasLaborales;

        return $this;
    }

    /**
     * Get tipoOfertasLaborales
     *
     * @return \cobe\OfertasLaboralesBundle\Entity\TipoOfertaLaboral 
     */
    public function getTipoOfertasLaborales()
    {
        return $this->tipoOfertasLaborales;
    }

    /**
     * Set usuario
     *
     * @param \cobe\UsuariosBundle\Entity\Usuario $usuario
     * @return OfertaLaboral
     */
    public function setUsuario(\cobe\UsuariosBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \cobe\UsuariosBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set publicacion
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacion
     * @return OfertaLaboral
     */
    public function setPublicacion(\cobe\PaginasBundle\Entity\Publicacion $publicacion)
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    /**
     * Get publicacion
     *
     * @return \cobe\PaginasBundle\Entity\Publicacion 
     */
    public function getPublicacion()
    {
        return $this->publicacion;
    }

    /**
     * Add aptitudes
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitudes
     * @return OfertaLaboral
     */
    public function addAptitude(\cobe\CurriculosBundle\Entity\Aptitud $aptitudes)
    {
        $this->aptitudes[] = $aptitudes;

        return $this;
    }

    /**
     * Remove aptitudes
     *
     * @param \cobe\CurriculosBundle\Entity\Aptitud $aptitudes
     */
    public function removeAptitude(\cobe\CurriculosBundle\Entity\Aptitud $aptitudes)
    {
        $this->aptitudes->removeElement($aptitudes);
    }

    /**
     * Get aptitudes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAptitudes()
    {
        return $this->aptitudes;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return OfertaLaboral
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

    /**
     * Add idiomas
     *
     * @param \cobe\CommonBundle\Entity\Idioma $idiomas
     * @return OfertaLaboral
     */
    public function addIdioma(\cobe\CommonBundle\Entity\Idioma $idiomas)
    {
        $this->idiomas[] = $idiomas;

        return $this;
    }

    /**
     * Remove idiomas
     *
     * @param \cobe\CommonBundle\Entity\Idioma $idiomas
     */
    public function removeIdioma(\cobe\CommonBundle\Entity\Idioma $idiomas)
    {
        $this->idiomas->removeElement($idiomas);
    }

    /**
     * Get idiomas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdiomas()
    {
        return $this->idiomas;
    }

    /**
     * Add ciudades
     *
     * @param \cobe\CommonBundle\Entity\Ciudad $ciudades
     * @return OfertaLaboral
     */
    public function addCiudade(\cobe\CommonBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades[] = $ciudades;

        return $this;
    }

    /**
     * Remove ciudades
     *
     * @param \cobe\CommonBundle\Entity\Ciudad $ciudades
     */
    public function removeCiudade(\cobe\CommonBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades->removeElement($ciudades);
    }

    /**
     * Get ciudades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCiudades()
    {
        return $this->ciudades;
    }


    /**
     * Get descripcion
     *
     * @return string
     */
    public function setFechaLimite($fechaLimite)
    {
        if(!is_a($fechaLimite,'DateTime')){
            try{
                $formats = array(
                    'Y-m-d', 'y-m-d', 'y/m/d', 'Y/m/d',
                    'Y-m-d H:i:s', 'y-m-d H:i:s', 'y/m/d H:i:s', 'Y/m/d H:i:s'
                );
                foreach($formats as $format){
                    $fechaLimite = new \DateTime($fechaLimite);
                    if(\DateTime::createFromFormat($format,$fechaLimite)){
                        $fechaLimite = \DateTime::createFromFormat($format,$fechaLimite);
                        break;
                    }
                }
                if(!is_a($fechaLimite,'DateTime')){
                    $fechaLimite = $this->getFechaLimite();
                }
            }catch(\Exception $e){
                $fechaLimite = $this->getFechaLimite();
            }
        }
        $this->fechaLimite = $fechaLimite;
        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime
     */
    public function getFechaLimite()
    {
        return $this->fechaLimite;
    }
}
