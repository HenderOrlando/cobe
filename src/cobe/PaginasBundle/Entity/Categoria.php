<?php
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Etiqueta;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\CategoriaRepository")
 */
class Categoria extends Etiqueta
{
    /**
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Categoria", mappedBy="categoria")
     */
    private $subcategorias;

    /**
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="categoriaPublicacion")
     */
    private $publicacionesCategoria;

    /**
     * @ORM\ManyToOne(targetEntity="\cobe\PaginasBundle\Entity\Categoria", inversedBy="subcategorias")
     * @ORM\JoinColumn(name="categoria", referencedColumnName="id")
     */
    private $categoria;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->subcategorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicacionesCategoria = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function addSubcategoria(\cobe\PaginasBundle\Entity\Categoria $subcategorias)
    {
        $this->subcategorias[] = $subcategorias;

        return $this;
    }

    /**
     * Remove subcategorias
     *
     * @param \cobe\PaginasBundle\Entity\Categoria $subcategorias
     */
    public function removeSubcategoria(\cobe\PaginasBundle\Entity\Categoria $subcategorias)
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
     * Add publicacionesCategoria
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacionesCategoria
     * @return Categoria
     */
    public function addPublicacionesCategorium(\cobe\PaginasBundle\Entity\Publicacion $publicacionesCategoria)
    {
        $this->publicacionesCategoria[] = $publicacionesCategoria;

        return $this;
    }

    /**
     * Remove publicacionesCategoria
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicacionesCategoria
     */
    public function removePublicacionesCategorium(\cobe\PaginasBundle\Entity\Publicacion $publicacionesCategoria)
    {
        $this->publicacionesCategoria->removeElement($publicacionesCategoria);
    }

    /**
     * Get publicacionesCategoria
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublicacionesCategoria()
    {
        return $this->publicacionesCategoria;
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
}
