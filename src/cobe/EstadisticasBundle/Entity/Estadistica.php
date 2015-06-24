<?php
namespace cobe\EstadisticasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\EstadisticasBundle\Repository\EstadisticaRepository")
 */
class Estadistica extends Objeto
{
    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion", mappedBy="estadistica")
     */
    private $estadisticasPublicacion;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral", mappedBy="estadistica")
     */
    private $estadisticasOfertaLaboral;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaUsuario", mappedBy="estadistica")
     */
    private $estadisticasUsuario;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaMensaje", mappedBy="estadistica")
     */
    private $estadisticasMensaje;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa", mappedBy="estadistica")
     */
    private $estadisticasEmpresa;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante", mappedBy="estadistica")
     */
    private $estadisticasEstudiante;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaGrupo", mappedBy="estadistica")
     */
    private $estadisticasGrupo;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaInteres", mappedBy="estadistica")
     */
    private $estadisticasInteres;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\EstadisticasBundle\Entity\EstadisticaAptitud", mappedBy="estadistica")
     */
    private $estadisticasAptitud;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\TipoEstadistica", inversedBy="estadisticasTipo")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     */
    private $tipo;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\EstadisticasBundle\Entity\EstadoEstadistica", inversedBy="estadisticasEstado")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id", nullable=false)
     */
    private $estado;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\CommonBundle\Entity\Etiqueta", inversedBy="estadisticasEtiqueta")
     * @ORM\JoinTable(
     *     name="Etiqueta2Estadistica",
     *     joinColumns={@ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="etiqueta", referencedColumnName="id", nullable=false)}
     * )
     */
    private $etiquetas;

    /**
     * @MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="\cobe\EstadisticasBundle\Entity\Caracteristica", inversedBy="estadisticasCaracteristica")
     * @ORM\JoinTable(
     *     name="Caracteristica2Estadistica",
     *     joinColumns={@ORM\JoinColumn(name="estadistica", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="caracteristica", referencedColumnName="id", nullable=false)}
     * )
     */
    private $caracteristicas;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->estadisticasPublicacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasOfertaLaboral = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasUsuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasMensaje = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasEmpresa = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasGrupo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasInteres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadisticasAptitud = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etiquetas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->caracteristicas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add estadisticasPublicacion
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticasPublicacion
     * @return Estadistica
     */
    public function addEstadisticasPublicacion(\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticasPublicacion)
    {
        $this->estadisticasPublicacion[] = $estadisticasPublicacion;

        return $this;
    }

    /**
     * Remove estadisticasPublicacion
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticasPublicacion
     */
    public function removeEstadisticasPublicacion(\cobe\EstadisticasBundle\Entity\EstadisticaPublicacion $estadisticasPublicacion)
    {
        $this->estadisticasPublicacion->removeElement($estadisticasPublicacion);
    }

    /**
     * Get estadisticasPublicacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasPublicacion()
    {
        return $this->estadisticasPublicacion;
    }

    /**
     * Add estadisticasOfertaLaboral
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticasOfertaLaboral
     * @return Estadistica
     */
    public function addEstadisticasOfertaLaboral(\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticasOfertaLaboral)
    {
        $this->estadisticasOfertaLaboral[] = $estadisticasOfertaLaboral;

        return $this;
    }

    /**
     * Remove estadisticasOfertaLaboral
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticasOfertaLaboral
     */
    public function removeEstadisticasOfertaLaboral(\cobe\EstadisticasBundle\Entity\EstadisticaOfertaLaboral $estadisticasOfertaLaboral)
    {
        $this->estadisticasOfertaLaboral->removeElement($estadisticasOfertaLaboral);
    }

    /**
     * Get estadisticasOfertaLaboral
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasOfertaLaboral()
    {
        return $this->estadisticasOfertaLaboral;
    }

    /**
     * Add estadisticasUsuario
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticasUsuario
     * @return Estadistica
     */
    public function addEstadisticasUsuario(\cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticasUsuario)
    {
        $this->estadisticasUsuario[] = $estadisticasUsuario;

        return $this;
    }

    /**
     * Remove estadisticasUsuario
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticasUsuario
     */
    public function removeEstadisticasUsuario(\cobe\EstadisticasBundle\Entity\EstadisticaUsuario $estadisticasUsuario)
    {
        $this->estadisticasUsuario->removeElement($estadisticasUsuario);
    }

    /**
     * Get estadisticasUsuario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasUsuario()
    {
        return $this->estadisticasUsuario;
    }

    /**
     * Add estadisticasMensaje
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje
     * @return Estadistica
     */
    public function addEstadisticasMensaje(\cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje)
    {
        $this->estadisticasMensaje[] = $estadisticasMensaje;

        return $this;
    }

    /**
     * Remove estadisticasMensaje
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje
     */
    public function removeEstadisticasMensaje(\cobe\EstadisticasBundle\Entity\EstadisticaMensaje $estadisticasMensaje)
    {
        $this->estadisticasMensaje->removeElement($estadisticasMensaje);
    }

    /**
     * Get estadisticasMensaje
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasMensaje()
    {
        return $this->estadisticasMensaje;
    }

    /**
     * Add estadisticasEmpresa
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa
     * @return Estadistica
     */
    public function addEstadisticasEmpresa(\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa)
    {
        $this->estadisticasEmpresa[] = $estadisticasEmpresa;

        return $this;
    }

    /**
     * Remove estadisticasEmpresa
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa
     */
    public function removeEstadisticasEmpresa(\cobe\EstadisticasBundle\Entity\EstadisticaEmpresa $estadisticasEmpresa)
    {
        $this->estadisticasEmpresa->removeElement($estadisticasEmpresa);
    }

    /**
     * Get estadisticasEmpresa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasEmpresa()
    {
        return $this->estadisticasEmpresa;
    }

    /**
     * Add estadisticasEstudiante
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante
     * @return Estadistica
     */
    public function addEstadisticasEstudiante(\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante)
    {
        $this->estadisticasEstudiante[] = $estadisticasEstudiante;

        return $this;
    }

    /**
     * Remove estadisticasEstudiante
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante
     */
    public function removeEstadisticasEstudiante(\cobe\EstadisticasBundle\Entity\EstadisticaEstudiante $estadisticasEstudiante)
    {
        $this->estadisticasEstudiante->removeElement($estadisticasEstudiante);
    }

    /**
     * Get estadisticasEstudiante
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasEstudiante()
    {
        return $this->estadisticasEstudiante;
    }

    /**
     * Add estadisticasGrupo
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaGrupo $estadisticasGrupo
     * @return Estadistica
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
     * Add estadisticasInteres
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres
     * @return Estadistica
     */
    public function addEstadisticasIntere(\cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres)
    {
        $this->estadisticasInteres[] = $estadisticasInteres;

        return $this;
    }

    /**
     * Remove estadisticasInteres
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres
     */
    public function removeEstadisticasIntere(\cobe\EstadisticasBundle\Entity\EstadisticaInteres $estadisticasInteres)
    {
        $this->estadisticasInteres->removeElement($estadisticasInteres);
    }

    /**
     * Get estadisticasInteres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasInteres()
    {
        return $this->estadisticasInteres;
    }

    /**
     * Add estadisticasAptitud
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud
     * @return Estadistica
     */
    public function addEstadisticasAptitud(\cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud)
    {
        $this->estadisticasAptitud[] = $estadisticasAptitud;

        return $this;
    }

    /**
     * Remove estadisticasAptitud
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud
     */
    public function removeEstadisticasAptitud(\cobe\EstadisticasBundle\Entity\EstadisticaAptitud $estadisticasAptitud)
    {
        $this->estadisticasAptitud->removeElement($estadisticasAptitud);
    }

    /**
     * Get estadisticasAptitud
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadisticasAptitud()
    {
        return $this->estadisticasAptitud;
    }

    /**
     * Set tipo
     *
     * @param \cobe\EstadisticasBundle\Entity\TipoEstadistica $tipo
     * @return Estadistica
     */
    public function setTipo(\cobe\EstadisticasBundle\Entity\TipoEstadistica $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \cobe\EstadisticasBundle\Entity\TipoEstadistica 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set estado
     *
     * @param \cobe\EstadisticasBundle\Entity\EstadoEstadistica $estado
     * @return Estadistica
     */
    public function setEstado(\cobe\EstadisticasBundle\Entity\EstadoEstadistica $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \cobe\EstadisticasBundle\Entity\EstadoEstadistica 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add etiquetas
     *
     * @param \cobe\CommonBundle\Entity\Etiqueta $etiquetas
     * @return Estadistica
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
     * Add caracteristicas
     *
     * @param \cobe\EstadisticasBundle\Entity\Caracteristica $caracteristicas
     * @return Estadistica
     */
    public function addCaracteristica(\cobe\EstadisticasBundle\Entity\Caracteristica $caracteristicas)
    {
        $this->caracteristicas[] = $caracteristicas;

        return $this;
    }

    /**
     * Remove caracteristicas
     *
     * @param \cobe\EstadisticasBundle\Entity\Caracteristica $caracteristicas
     */
    public function removeCaracteristica(\cobe\EstadisticasBundle\Entity\Caracteristica $caracteristicas)
    {
        $this->caracteristicas->removeElement($caracteristicas);
    }

    /**
     * Get caracteristicas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }
}
