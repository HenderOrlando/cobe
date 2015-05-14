<?php

namespace cobe\CommonBundle\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * ObjectRepository
 *
 */
class ObjectRepository extends EntityRepository{

    public function getAll($opt='all'){
        $metadata = $this->getClassMetadata();
        $tableName = $metadata->getTableName();
        $qb = $this->createQueryBuilder($tableName);
        switch($opt){
            case 'count':
                $qb->select('count('.$tableName.'.id)');
                $qb = $qb->getQuery()->getSingleScalarResult();
                break;
        }
        return $qb;
    }

    public function getBy($datos, ObjectManager $em, $queryBuilder = false){
        $rta = null;

        $metadata = $this->getClassMetadata();
        $tableName = $metadata->getTableName();
        $qb = $this->createQueryBuilder($tableName);

        foreach($datos as $id => $dato){
            $qb_ = null;
            if($metadata->hasAssociation($id)){
                $mapping = $metadata->getAssociationMapping($id);
                $meta = $em->getClassMetadata($mapping['targetEntity']);
                if(!is_array($dato)){
                    $dato = array('id' => $dato);
                }
                if($metadata->isSingleValuedAssociation($id)){
                    $qb_ = $em->createQueryBuilder($meta->getTableName())
                        ->select($meta->getTableName().'.id')
                        ->from($meta->getName(), $meta->getTableName());
                    $this->getByParams($dato, $meta, $qb_, true);
                    $qb->andWhere($qb->expr()->in($tableName.'.'.$id, $qb_->getDql()));
                }elseif($metadata->isCollectionValuedAssociation($id)){
                    $qb->innerJoin($tableName.'.'.$id, $meta->getTableName());
                    $this->getByParams($dato, $meta, $qb, true);
                }
                unset($datos[$id]);
            }
        }
        if(!empty($datos)){
            $qb_ = null;
            $this->getByParams($datos, $metadata, $qb, true);
        }
        $rta = $qb;
        if(!$queryBuilder){
            $rta = $qb->getQuery()->execute();
        }
        return $rta;
    }

    public function getByParams($datos, ClassMetadata $metadata, $queryBuilder = false){
        $rta = null;
        $tableName = $metadata->getTableName();
        $qb = $queryBuilder;

        if(!is_a($qb,'Doctrine\ORM\QueryBuilder')){
            $qb = $this->createQueryBuilder($tableName);
        }
        if($metadata->isInheritanceTypeSingleTable() && isset($datos['herencia'])){
            $name = $metadata->getName();
            $obj = new $name();
            $dato = ucfirst(strtolower($datos['herencia']));
            if(method_exists($obj,'getHerencias')){
                $herencias = $obj->getHerencias();
                if(array_key_exists($dato,$herencias)){
                    $herencia = $herencias[$dato];
                    $qb->andWhere($tableName.' INSTANCE OF '.$herencia);
                    unset($datos['herencia']);
                }elseif(strtolower($dato) === strtolower($tableName)){
                    unset($datos['herencia']);
                }
            }
            /*var_dump($qb->getDQL());
            echo '---------------------';
            die;*/
        }
        foreach($datos as $id => $dato){
            if($metadata->hasField($id)){
                // boolean | number | bigint | decimal | integer | smallint | float | string | text | datetime | date | time
                $type = $metadata->getTypeOfField($id);
                $dato = $this->sanearDato($dato, $type);
                if($type == 'string' || $type == 'text'){
                    $q = $qb->expr()->like($tableName.'.'.$id,$qb->expr()->literal('%'.$dato.'%'));
                }else{
                    $q = $qb->expr()->eq($tableName.'.'.$id,$dato);
                }
                $qb->andWhere($q);
                unset($datos[$id]);
            }/*elseif($id == 'herencia'){
                $qb->andWhere($qb->expr()->like($tableName.'.'.$id,$qb->expr()->literal('%'.$dato.'%')));
                var_dump($id);
                var_dump($dato);
                die;
            }*/
        }
        $rta = $qb;
        if(!$queryBuilder){
            $rta = $qb->getQuery()->execute();
        }

        return $rta;
    }

    public function sanearDato($dato, $type){
        return $dato;
    }
}
