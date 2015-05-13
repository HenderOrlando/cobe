<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto as Obj;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\EstadoRepository")
 * @ORM\Table(options={"comment":"Estados de los Objetos del sistema."})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaEstado", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "Estado"="\cobe\CommonBundle\Entity\Estado",
 *     "Usuario"="\cobe\UsuariosBundle\Entity\EstadoUsuario",
 *     "GrupoPersona"="cobe\GruposBundle\Entity\EstadoGrupoPersona",
 *     "Votacion"="cobe\GruposBundle\Entity\EstadoVotacion",
 *     "Trabajo"="\cobe\OfertasLaboralesBundle\Entity\EstadoOfertaLaboral",
 *     "Mensaje"="\cobe\MensajesBundle\Entity\EstadoMensaje",
 *     "Destinatario"="\cobe\MensajesBundle\Entity\EstadoDestinatario",
 *     "Publicacion"="\cobe\PaginasBundle\Entity\EstadoPublicacion",
 *     "Plantilla"="\cobe\PaginasBundle\Entity\EstadoPlantilla",
 *     "Archivo"="\cobe\ColeccionesBundle\Entity\EstadoArchivo",
 *     "Estadistica"="\cobe\EstadisticasBundle\Entity\EstadoEstadistica"
 * }
 * )
 */
class Estado extends Obj
{

    public function setHerenciaEstado($tipo){
        $this->herenciaEstado = $tipo;

        return $this;
    }

    public function getHerenciaEstado(){
        return $this->herenciaEstado;
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId(){
        return parent::getId();
    }
}
