<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Model\Normalizacion;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(options={"comment":"Objeto base, padre de los objetos de la Aplicación."})
 */
class Objeto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", options={"comment":"Identificador del Objeto"})
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, options={"comment":"Nombre del Objeto"})
     */
    private $nombre;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=100,
     *     nullable=false,
     *     options={"comment":"Canonical del nombre del objeto"}
     * )
     */
    private $canonical;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment":"Descripción del Objeto"})
     */
    private $descripcion;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"comment":"Fecha en que se crea el Objeto."})
     */
    private $fechaCreado;

    public function __construct(){
        $this->fechaCreado = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return guid 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;

        return $this;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Objeto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        $this->canonical = Normalizacion::normalizarTexto($nombre);

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set canonical
     *
     * @param string $canonical
     * @return Objeto
     */
    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;

        return $this;
    }

    /**
     * Get canonical
     *
     * @return string 
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Objeto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(){
        return $this->getNombre();
    }
}
