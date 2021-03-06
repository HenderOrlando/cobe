<?php

namespace cobe\UsuariosBundle\Repository;

use cobe\CommonBundle\Repository\ObjectRepository;
use cobe\UsuariosBundle\Entity\Usuario;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends ObjectRepository
{
    public function validaUsuario($clave, $email = false, $nombre = false, $docId = false){
        $qb = $this->createQueryBuilder('u');
        if($clave || $email || $nombre || $docId){

            if($email){
                $qb
                    ->andWhere($qb->expr()->like('u.email',$qb->expr()->literal($email)))
                ;
            }
            if($nombre){
                $qb
                    ->andWhere($qb->expr()->like('u.nombre',$qb->expr()->literal($nombre)))
                ;
            }
            if($clave){
                $sqb = $this->createQueryBuilder('u1')
                    ->select('SHA2(CONCAT('.$qb->expr()->literal($clave).',u1.salt),256)')
                    ->getDQL()
                ;
                $qb
                    //->andWhere($qb->expr()->orX($qb->expr()->like('u.email',$qb->expr()->literal($email)),$qb->expr()->like('u.nombre',$qb->expr()->literal($nombre))))
                    ->andWhere($qb->expr()->in('u.clave',$sqb))
                ;
            }
            if($docId){
                $sqb = $this->getEntityManager()->createQueryBuilder()
                    ->from('cobeUsuariosBundle:Persona','p')
                    ->select('p.id')
                    ->andWhere($qb->expr()->eq('p.doc_id',$docId))
                    ->getDQL()
                ;
                $qb
                    ->andWhere($qb->expr()->in('u.id',$sqb))
                ;
                /*$qb
                    ->andWhere('p.id = u.id')
                    ->andWhere($qb->expr()->eq('p.doc_id',$docId))
                ;*/
            }
            $qb->setMaxResults(1);
            /*var_dump($qb->getDQL());
            die;*/
            //$rta = $qb->getQuery()->execute();
            $rta = $qb->getQuery()->getResult();
            if(isset($rta[0])){
                if(($nombre || $email) && !$clave){
                    $rta = array(
                        'validate' => true
                    );
                }elseif($clave){
                    $rta = $rta[0];
                }
            }else{
                $rta = array(
                    'validate' => false
                );
            }
        }else{
            $rta = array(
                'validate' => false
            );
        }
        return $rta;
    }
}
