<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto as Obj;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\TipoRepository")
 * @ORM\Table(options={"comment":"Tipos de los Objetos del sistema."})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaTipo", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "Tipo"="\cobe\CommonBundle\Entity\Tipo",
 *     "Historial"="\cobe\UsuariosBundle\Entity\TipoHistorial",
 *     "Estudio"="\cobe\CurriculosBundle\Entity\TipoEstudio",
 *     "Recomendacion"="\cobe\CurriculosBundle\Entity\TipoRecomendacion",
 *     "Reconocimiento"="\cobe\CurriculosBundle\Entity\TipoProyecto",
 *     "Proyecto"="\cobe\CurriculosBundle\Entity\TipoReconocimiento",
 *     "OfertaLaboral"="\cobe\OfertasLaboralesBundle\Entity\TipoOfertaLaboral",
 *     "Publicacion"="\cobe\PaginasBundle\Entity\TipoPublicacion",
 *     "VotacionPublicacion"="\cobe\PaginasBundle\Entity\TipoVotacionPublicacion",
 *     "Archivo"="\cobe\ColeccionesBundle\Entity\TipoArchivo",
 *     "Estadistica"="\cobe\EstadisticasBundle\Entity\TipoEstadistica",
 *     "Plantilla"="\cobe\PaginasBundle\Entity\TipoPlantilla"
 * }
 * )
 */
class Tipo extends Obj
{


    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }
}
