<?php
// GrupoEditor no registra PrimaryKey, por tal razón se agrega la condición de
// agregar índice solo si existen columns en /vendor/doctrine/orm/lib/Doctrine/ORM/Tools/SchemaTools.php
// Line 268
namespace cobe\PaginasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\GruposBundle\Entity\Grupo;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\PaginasBundle\Repository\GrupoEditorRepository")
 * @ORM\Table(options={"comment":"Grupos Editores de las Publicaciones"})
 */
class GrupoEditor extends Grupo
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="grupoEditor")
     */
    private $publicaciones;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
     * Add publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     * @return GrupoEditor
     */
    public function addPublicacione(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
    {
        $this->publicaciones[] = $publicaciones;

        return $this;
    }

    /**
     * Remove publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     */
    public function removePublicacione(\cobe\PaginasBundle\Entity\Publicacion $publicaciones)
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


}
