<?php
namespace cobe\OfertasLaboralesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto as Obj;

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
     * 
     */
    private $fechaLimite;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona", mappedBy="ofertaLaboral")
     */
    private $ofertaLaboralPersonas;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\MensajesBundle\Entity\ComentarioOfertaLaboral", mappedBy="ofertaLaboral")
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoTrabajo", mappedBy="ofertaLaboral")
     */
    private $archivo;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral", mappedBy="ofertaLaboral")
     */
    private $estadisticas;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\OfertasLaboralesBundle\Entity\EstadoOfertaLaboral", inversedBy="ofertasLaboralesEstado")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estadoOfertasLaborales;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\OfertasLaboralesBundle\Entity\TipoOfertaLaboral", inversedBy="ofertasLaboralesTipo")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipoOfertasLaborales;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\UsuariosBundle\Entity\Usuario", inversedBy="ofertasLaborales")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", inversedBy="ofertasLaborales")
     * @ORM\JoinColumn(name="publicacion", referencedColumnName="id", nullable=false)
     */
    private $publicacion;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\CurriculosBundle\Entity\Aptitud", inversedBy="ofertasLaboralesAptitud")
     * @ORM\JoinTable(
     *     name="Aptitud2OfertaLaboral",
     *     joinColumns={@ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="aptitud", referencedColumnName="id", nullable=false)}
     * )
     */
    private $aptitudes;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="ofertasLaborales")
     * @ORM\JoinTable(
     *     name="Etiqueta2OfertaLaboral",
     *     joinColumns={@ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    private $etiquetas;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Idioma", inversedBy="ofertasLaborales")
     * @ORM\JoinTable(
     *     name="Idioma2OfertaLaboral",
     *     joinColumns={@ORM\JoinColumn(name="ofertaLaboral", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="idioma", referencedColumnName="id", nullable=false)}
     * )
     */
    private $idiomas;

    /**
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
        $this->ofertaLaboralPersonas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersonas
     * @return OfertaLaboral
     */
    public function addOfertaLaboralPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersonas)
    {
        $this->ofertaLaboralPersonas[] = $ofertaLaboralPersonas;

        return $this;
    }

    /**
     * Remove ofertaLaboralPersonas
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersonas
     */
    public function removeOfertaLaboralPersona(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboralPersona $ofertaLaboralPersonas)
    {
        $this->ofertaLaboralPersonas->removeElement($ofertaLaboralPersonas);
    }

    /**
     * Get ofertaLaboralPersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertaLaboralPersonas()
    {
        return $this->ofertaLaboralPersonas;
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
     * Add archivo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivo
     * @return OfertaLaboral
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivo)
    {
        $this->archivo[] = $archivo;

        return $this;
    }

    /**
     * Remove archivo
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivo
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoTrabajo $archivo)
    {
        $this->archivo->removeElement($archivo);
    }

    /**
     * Get archivo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivo()
    {
        return $this->archivo;
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
}
