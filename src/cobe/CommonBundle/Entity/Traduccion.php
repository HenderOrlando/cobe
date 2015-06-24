<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\TraduccionRepository")
 * @ORM\Table(options={"comment":"Traducciones."})
 */
class Traduccion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @MaxDepth(2)
     * @ORM\OneToMany(targetEntity="\cobe\ColeccionesBundle\Entity\ArchivoTraduccion", mappedBy="traduccion")
     */
    private $archivos;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CommonBundle\Entity\Idioma", inversedBy="traduccionesTraducido")
     * @ORM\JoinColumn(name="idiomaTraducir", referencedColumnName="id", nullable=false)
     */
    private $idiomaTraducido;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="\cobe\CommonBundle\Entity\Idioma", inversedBy="traduccionesTraductor")
     * @ORM\JoinColumn(name="idiomaTraductor", referencedColumnName="id", nullable=false)
     */
    private $idiomaTraductor;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoTraduccion $archivos
     * @return Traduccion
     */
    public function addArchivo(\cobe\ColeccionesBundle\Entity\ArchivoTraduccion $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \cobe\ColeccionesBundle\Entity\ArchivoTraduccion $archivos
     */
    public function removeArchivo(\cobe\ColeccionesBundle\Entity\ArchivoTraduccion $archivos)
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
     * Set idiomaTraducido
     *
     * @param \cobe\CommonBundle\Entity\Idioma $idiomaTraducido
     * @return Traduccion
     */
    public function setIdiomaTraducido(\cobe\CommonBundle\Entity\Idioma $idiomaTraducido)
    {
        $this->idiomaTraducido = $idiomaTraducido;

        return $this;
    }

    /**
     * Get idiomaTraducido
     *
     * @return \cobe\CommonBundle\Entity\Idioma 
     */
    public function getIdiomaTraducido()
    {
        return $this->idiomaTraducido;
    }

    /**
     * Set idiomaTraductor
     *
     * @param \cobe\CommonBundle\Entity\Idioma $idiomaTraductor
     * @return Traduccion
     */
    public function setIdiomaTraductor(\cobe\CommonBundle\Entity\Idioma $idiomaTraductor)
    {
        $this->idiomaTraductor = $idiomaTraductor;

        return $this;
    }

    /**
     * Get idiomaTraductor
     *
     * @return \cobe\CommonBundle\Entity\Idioma 
     */
    public function getIdiomaTraductor()
    {
        return $this->idiomaTraductor;
    }
}
