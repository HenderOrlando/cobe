<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto AS Obj;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\EtiquetaRepository")
 * @ORM\Table(options={"comment":"Etiquetas de los Objetos del sistema."})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="herencia", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "Etiqueta"="\cobe\CommonBundle\Entity\Etiqueta",
 *     "Interes"="\cobe\CurriculosBundle\Entity\Interes",
 *     "Aptitud"="\cobe\CurriculosBundle\Entity\Aptitud",
 *     "NivelIdioma"="\cobe\CurriculosBundle\Entity\NivelIdioma",
 *     "Categoria"="\cobe\PaginasBundle\Entity\Categoria",
 *     "Opcion"="\cobe\GruposBundle\Entity\Opcion",
 *     "Caracteristica"="\cobe\EstadisticasBundle\Entity\Caracteristica"
 * }
 * )
 */
class Etiqueta extends Obj
{
    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Estudiante", mappedBy="etiquetas")
     */
    private $estudiantes;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\UsuariosBundle\Entity\Empresa", mappedBy="etiquetas")
     */
    private $empresas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="cobe\GruposBundle\Entity\Grupo", mappedBy="etiquetas")
     */
    private $grupos;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\OfertasLaboralesBundle\Entity\OfertaLaboral", mappedBy="etiquetas")
     */
    private $ofertasLaborales;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\PaginasBundle\Entity\Publicacion", mappedBy="etiquetas")
     */
    private $publicaciones;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\ColeccionesBundle\Entity\Archivo", mappedBy="etiquetas")
     */
    private $archivos;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEtiqueta", mappedBy="etiqueta")
     */
    private $estadisticas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estudiantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertasLaborales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estudiantes
     *
     * @param \cobe\UsuariosBundle\Entity\Estudiante $estudiantes
     * @return Etiqueta
     */
    public function addEstudiante(\cobe\UsuariosBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes[] = $estudiantes;

        return $this;
    }

    /**
     * Remove estudiantes
     *
     * @param \cobe\UsuariosBundle\Entity\Estudiante $estudiantes
     */
    public function removeEstudiante(\cobe\UsuariosBundle\Entity\Estudiante $estudiantes)
    {
        $this->estudiantes->removeElement($estudiantes);
    }

    /**
     * Get estudiantes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudiantes()
    {
        return $this->estudiantes;
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
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEtiqueta $estadisticas
     * @return Etiqueta
     */
    public function addEstadisticas($estadisticas)
    {
        $this->estadisticas[] = $estadisticas;

        return $this;
    }

    /**
     * Remove estadisticasEtiqueta
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEtiqueta $estadisticas
     */
    public function removeEstadisticas($estadisticas)
    {
        $this->estadisticas->removeElement($estadisticas);
    }

    /**
     * Get estadisticasEtiqueta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticas()
    {
        return $this->estadisticas;
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
