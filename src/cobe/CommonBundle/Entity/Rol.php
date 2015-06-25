<?php
namespace cobe\CommonBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\CommonBundle\Entity\Objeto as Obj;

/**
 * @ORM\Entity(repositoryClass="cobe\CommonBundle\Repository\RolRepository")
 * @ORM\Table(options={"comment":"Roles de los Objetos del sistema."})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaRol", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "Rol"="\cobe\CommonBundle\Entity\Rol",
 *     "Usuario"="\cobe\UsuariosBundle\Entity\RolUsuario",
 *     "ProyectoPersona"="\cobe\CurriculosBundle\Entity\RolProyectoPersona",
 *     "GrupoPersona"="\cobe\GruposBundle\Entity\RolGrupoPersona",
 *     "TrabajoPersona"="\cobe\OfertasLaboralesBundle\Entity\RolOfertaLaboralPersona"
 * }
 * )
 */
class Rol extends Obj
{
    public function getHerencias(){
        return array(
            "Rol"=>'\cobe\CommonBundle\Entity\Rol',
            "Usuario"=>'\cobe\UsuariosBundle\Entity\RolUsuario',
            "ProyectoPersona"=>'\cobe\CurriculosBundle\Entity\RolProyectoPersona',
            "Proyectopersona"=>'\cobe\CurriculosBundle\Entity\RolProyectoPersona',
            "GrupoPersona"=>'\cobe\GruposBundle\Entity\RolGrupoPersona',
            "Grupopersona"=>'\cobe\GruposBundle\Entity\RolGrupoPersona',
            "TrabajoPersona"=>'\cobe\OfertasLaboralesBundle\Entity\RolOfertaLaboralPersona',
            "Trabajopersona"=>'\cobe\OfertasLaboralesBundle\Entity\RolOfertaLaboralPersona'
        );
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
