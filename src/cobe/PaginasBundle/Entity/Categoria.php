<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\CategoriaRepository")
 * @ORM\Table(options={"comment":"Categorías de las publicaciones"})
 */
class Categoria extends Etiqueta
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Categoria", mappedBy="categoria")
     * @ORM\JoinColumn(name="subcategorias", referencedColumnName="id", nullable=true)
     */
    private $subcategorias;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="categorias")
     */
    private $publicaciones;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\MensajesBundle\Entity\Mensaje", mappedBy="categorias")
     */
    private $mensajes;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Categoria", inversedBy="subcategorias")
     * @ORM\JoinColumn(name="categoria", referencedColumnName="id", nullable=true)
     */
    private $categoria;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaCategoria", mappedBy="etiqueta")
     */
    private $estadisticas;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->subcategorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add subcategorias
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $subcategorias
     * @return Categoria
     */
    public function addSubcategorias(\cobe\PaginasBundle\Entity\Categoria $subcategorias)
    {
        $this->subcategorias[] = $subcategorias;

        return $this;
    }

    /**
     * Remove subcategorias
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $subcategorias
     */
    public function removeSubcategorias(\cobe\PaginasBundle\Entity\Categoria $subcategorias)
    {
        $this->subcategorias->removeElement($subcategorias);
    }

    /**
     * Get subcategorias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubcategorias()
    {
        return $this->subcategorias;
    }

    /**
     * Add publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     * @return Categoria
     */
    public function addPublicaciones(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones[] = $publicaciones;

        return $this;
    }

    /**
     * Remove publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     */
    public function removePublicaciones(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones->removeElement($publicaciones);
    }

    /**
     * Get publicaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublicaciones()
    {
        return $this->publicaciones;
    }

    /**
     * Add mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     * @return Categoria
     */
    public function addMensajes(\cobe\MensajesBundle\Entity\Mensaje $mensajes)
    {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \cobe\MensajesBundle\Entity\Mensaje $mensajes
     */
    public function removeMensajes(\cobe\MensajesBundle\Entity\Mensaje $mensajes)
    {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Get mensajes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajes()
    {
        return $this->mensajes;
    }

    /**
     * Set categoria
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $categoria
     * @return Categoria
     */
    public function setCategoria(\cobe\PaginasBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \cobe\PaginasBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Add estadisticasCategoria
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaCategoria $estadisticas
     * @return Categoria
     */
    public function addEstadisticas($estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticasCategoria
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaCategoria $estadisticas
     */
    public function removeEstadisticas($estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticasCategoria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
    }

}
