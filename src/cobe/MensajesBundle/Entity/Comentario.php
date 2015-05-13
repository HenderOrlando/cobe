<?php
namespace cobe\MensajesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use cobe\MensajesBundle\Entity\Mensaje;

/**
 * @ORM\Entity(repositoryClass="cobe\MensajesBundle\Repository\ComentarioRepository")
 * @ORM\Table(options={"comment":"Comentarios en el sistema"})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="herenciaMensaje", length=25, type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *      "Comentario"="\cobe\MensajesBundle\Entity\Comentario",
 *      "Usuario"="\cobe\MensajesBundle\Entity\ComentarioUsuario",
 *      "Grupo"="\cobe\MensajesBundle\Entity\ComentarioGrupo",
 *      "Archivo"="\cobe\MensajesBundle\Entity\ComentarioArchivo",
 *      "Publicacion"="\cobe\MensajesBundle\Entity\ComentarioPublicacion",
 *      "OfertaLaboral"="\cobe\MensajesBundle\Entity\ComentarioOfertaLaboral"
 *     }
 * )
 */
class Comentario extends Mensaje
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
