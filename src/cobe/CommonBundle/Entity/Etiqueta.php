<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\EtiquetaRepository")
 * @ORM\Table(options={"comment":"Etiquetas de los Objetos del sistema."})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herencia", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "Etiqueta"="\cobe\CommonBundle\Entity\Etiqueta",
 *     "Interes"="\cobe\CurriculosBundle\Entity\Interes",
 *     "Aptitud"="\cobe\CurriculosBundle\Entity\Aptitud",
 *     "NivelIdioma"="\cobe\CurriculosBundle\Entity\NivelIdioma",
 *     "Categoria"="\cobe\PaginasBundle\Entity\Categoria",
 *     "Caracteristica"="\cobe\EstadisticasBundle\Entity\Caracteristica"
 * }
 * )
 */
class Etiqueta extends Obj
{
    /**
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", mappedBy="etiquetas")
     */
    private $empresas;

    /**
     * @ORM\ManyToMany(targetEntity="cobe\GruposBundle\Entity\Grupo", mappedBy="etiquetas")
     */
    private $grupos;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="etiquetas")
     */
    private $ofertasLaborales;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="etiquetas")
     */
    private $publicaciones;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\ColeccionesBundle\Entity\Archivo", mappedBy="etiquetas")
     */
    private $archivos;

    /**
     * @ORM\ManyToMany(targetEntity="\cobe\EstadisticasBundle\Entity\Estadistica", mappedBy="etiquetas")
     */
    private $estadisticasEtiqueta;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasEtiqueta = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     * @return Etiqueta
     */
    public function addEmpresa(\cobe\UsuariosBundle\Entity\Empresa $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \cobe\UsuariosBundle\Entity\Empresa $empresas
     */
    public function removeEmpresa(\cobe\UsuariosBundle\Entity\Empresa $empresas)
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

    /**
     * Add grupos
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupos
     * @return Etiqueta
     */
    public function addGrupo(\cobe\GruposBundle\Entity\Grupo $grupos)
    {
        $this->grupos[] = $grupos;

        return $this;
    }

    /**
     * Remove grupos
     *
     * @param \cobe\GruposBundle\Entity\Grupo $grupos
     */
    public function removeGrupo(\cobe\GruposBundle\Entity\Grupo $grupos)
    {
        $this->grupos->removeElement($grupos);
    }

    /**
     * Get grupos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    /**
     * Add ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     * @return Etiqueta
     */
    public function addOfertasLaborale(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales[] = $ofertasLaborales;

        return $this;
    }

    /**
     * Remove ofertasLaborales
     *
     * @param \cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales
     */
    public function removeOfertasLaborale(\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral $ofertasLaborales)
    {
        $this->ofertasLaborales->removeElement($ofertasLaborales);
    }

    /**
     * Get ofertasLaborales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertasLaborales()
    {
        return $this->ofertasLaborales;
    }

    /**
     * Add publicaciones
     *
     * @param \cobe\PaginasBundle\Entity\Publicacion $publicaciones
     * @return Etiqueta
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

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivos
     * @return Etiqueta
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\Archivo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\Archivo $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\Archivo $archivos)
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
     * Add estadisticasEtiqueta
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEtiqueta
     * @return Etiqueta
     */
    public function addEstadisticasEtiquetum(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEtiqueta)
    {
        $this->estadisticasEtiqueta[] = $estadisticasEtiqueta;

        return $this;
    }

    /**
     * Remove estadisticasEtiqueta
     *
     * @param \cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEtiqueta
     */
    public function removeEstadisticasEtiquetum(\cobe\EstadisticasBundle\Entity\Estadistica $estadisticasEtiqueta)
    {
        $this->estadisticasEtiqueta->removeElement($estadisticasEtiqueta);
    }

    /**
     * Get estadisticasEtiqueta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasEtiqueta()
    {
        return $this->estadisticasEtiqueta;
    }
    
    public function getHerencias(){
        return array(
            'Etiqueta'=>'\cobe\CommonBundle\Entity\Etiqueta',
            'Interes'=>'\cobe\CurriculosBundle\Entity\Interes',
            'Aptitud'=>'\cobe\CurriculosBundle\Entity\Aptitud',
            'NivelIdioma'=>'\cobe\CurriculosBundle\Entity\NivelIdioma',
            'Categoria'=>'\cobe\PaginasBundle\Entity\Categoria',
            'Caracteristica'=>'\cobe\EstadisticasBundle\Entity\Caracteristica'
        );
    }
}
