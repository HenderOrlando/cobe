<?php

namespace cobe\CommonBundle\Controller\Api;

use cobe\UsuariosBundle\Entity\RolUsuario;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use JMS\Serializer\SerializationContext;
use Pagerfanta\Pagerfanta;
use Proxies\__CG__\cobe\CommonBundle\Entity\Etiqueta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Configuration\Route as HateoasRoute;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * API controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiController extends Controller
{
    protected $page = 1;
    protected $offset = 1;
    protected $limit = 5;

    /**
     * Creates a form to delete a Resource entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm($slug, $route)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route, array('slug' => $slug)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }

    /**
     * @return ClassMetadata
    */
    public function getClassMetadata( $entity ){
        $em = $this->getManager();
        $classMetadata = null;
        if(!is_string($entity) && is_object($entity)){
            $entity = get_class($entity);
        }
        if(is_string($entity)){
            $classMetadata = $em->getClassMetadata($entity);
        }
        return $classMetadata;
    }

    /**
     * Retorna el manejador de Objetos Doctrine
     *
     * @return ObjectManager
     */
    public function getManager(){
        return $this->getDoctrine()->getManager();
    }

    public function getRta($objs, $format = 'json', $route = ''){
        if(is_array($objs) || is_a($objs, 'Doctrine\ORM\QueryBuilder') || is_a($objs,'Hateoas\Representation\PaginatedRepresentation') && $route){
            $objs = $this->getPagerfanta($objs, $route);
        }
        $datos = $this->container->get('serializer')->serialize($objs, $format, SerializationContext::create()->enableMaxDepthChecks());

        /*foreach($objs as $id => $dato){
            if(is_object($dato) && method_exists($dato,'getId')){
                $hateoas = HateoasBuilder::create()->build();
                $datos[$dato->getId()] = $hateoas->serialize($dato, $format);
            }else{
                $datos[$id] = $dato;
            }
        }*/

        return $datos;
    }

    public function getJson($objs, $route = ''){
        return $this->getRta($objs, 'json', $route);
    }

    public function getJsonResponse($objs, Request $request){
        $this->offset = intval($request->get('offset', $this->offset));
        $this->limit = intval($request->get('limit', $this->limit));
        //$this->page = floor($offset / $limit) + 1;
        $this->page = intval($request->get('page',$this->page));

        //$code = 200;

        $rta = $this->getJson($objs, array(
            'route' => $request->attributes->get('_route'),
            'params' => $request->attributes->get('_route_params'),
        ));

        /*$decode = json_decode($rta);
        return new JsonResponse($decode);*/
        return new Response($rta, 200, array('Content-Type' => 'application/json'));
    }

    public function getPagerfanta($list, $route = null){
        if(is_string($route)){
            $route = array(
                'route' => $route,
                'params' => array(),
            );
        }elseif(is_array($route) && isset($route['route'])){
            if(!isset($route['params'])){
                $route['params'] = array();
            }
        }else{
            $route = null;
        }

        if(is_array($list)){
            $adapter = new ArrayAdapter($list);
        }else{
            $adapter = new DoctrineORMAdapter($list);
        }
        $pager = new Pagerfanta($adapter);
        $pager
            ->setMaxPerPage($this->limit)
            ->setCurrentPage($this->page);


        $pagerfantaFactory = new PagerfantaFactory();

        $rta = $pagerfantaFactory->createRepresentation(
            $pager,
            new HateoasRoute($route['route'], $route['params'],true)
        );
        return $rta;
    }

    public function getForm($type, $obj = null, Request $request = null, $save = false){
        $form = $this->createForm($type, $obj);
        $isValid = false;
        $saved = false;
        $errors = array();
        if($request){
            $this->getColectionsObjects($obj, $type, $request);
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $isValid = true;
            if($isValid){
                if($save){
                    $em = $this->getManager();
                    $em->persist($obj);
                    $obj = $this->captureErrorFlush($em, $obj, 'crear');
                    if(!is_object($obj) && $obj['errors']){
                        $errors = $obj['errors'];
                    }else{
                        $saved = true;
                    }
                }
            }else{
                $errors = array(
                    '400' => array(
                        'message'   => "El formulario no es válido",
                        'code'      => "400",
                    ),
                );
            }
        }
        if(empty($errors)){
            $rta = array(
                'form'  => array(
                    'saved'     => $saved,
                    'isValid'   => $isValid,
                    'html'      => $this->renderView('cobeCommonBundle:Api:_form.html.twig', array(
                        'form' => $form->createView(),
                    )),
                ),
            );
        }else{
            $rta['errors'] = $errors;
        }
        if($saved){
            $rta['recurso'] = $obj;
        }
        return $rta;
    }

    public function getConfigObject($obj, $herencia){
        $herencias = null;
        if($herencia && method_exists($obj, 'getHerencias')){
            $herencias = $obj->getHerencias();
            if(is_array($herencias) && array_key_exists(ucfirst($herencia),$herencias)){
                $objHerencia = $herencias[ucfirst($herencia)];
                $obj = new $objHerencia();
            }
        }
        $classmetadata = $this->getClassMetadata($obj);
        $datos = array();
        $source = explode('\\',$classmetadata->getName());
        $source = strtolower($source[count($source)-1]);

        foreach($classmetadata->getFieldNames() as $fieldname){
            $map = $classmetadata->getFieldMapping($fieldname);
            $datos[$fieldname]['unique'] = $map['unique'];
            $datos[$fieldname]['nullable'] = $map['nullable'];
            $datos[$fieldname]['type'] = $map['type'];
            $datos[$fieldname]['collection'] = false;
        }
        foreach($classmetadata->getAssociationMappings() as $map){
            //$fieldname = str_replace(ucfirst($source),'',str_replace($source,'',$map['fieldName']));
            $fieldname = $map['fieldName'];
            $target = explode('\\',$map['targetEntity']);
            $target = strtolower($target[count($target)-1]);
            if(isset($association['joinColumns'])){
                $configJoin = $association['joinColumns'][0];
                $datos[$fieldname]['nullable'] = $configJoin['nullable'];
                $datos[$fieldname]['unique'] = $configJoin['unique'];
            }else{
                $datos[$fieldname]['nullable'] = true;
                $datos[$fieldname]['unique'] = false;
            }
            $datos[$fieldname]['collection'] = $classmetadata->isCollectionValuedAssociation($fieldname);
            $datos[$fieldname]['type'] = str_replace($source,'',$target);
        }
        return $datos;
    }

    public function validateTypeField($type, $value, \Doctrine\ORM\QueryBuilder $qb = null){
        $valid = false;
        if($value && !empty($value)){
            switch($type){
                case 'string':
                case 'text':
                    if(is_string($value)){
                        $valid = true;
                        if($qb){
                            $valid = $qb->expr()->literal(trim($value));
                        }
                    }
                    break;
                case 'date':
                case 'time':
                case 'datetime':
                case 'datetimetz':
                    if((is_object($value) && is_a($value,'DateTime')) || (is_string($value) && (
                            \DateTime::createFromFormat('Y/m/d H:i:s', $value) !== false ||
                            \DateTime::createFromFormat('Y-m-d H:i:s', $value) !== false ||
                            \DateTime::createFromFormat('d/m/Y H:i:s', $value) !== false ||
                            \DateTime::createFromFormat('d-m-Y H:i:s', $value) !== false ||
                            \DateTime::createFromFormat('d/m/Y', $value) !== false ||
                            \DateTime::createFromFormat('d-m-Y', $value) !== false ||
                            \DateTime::createFromFormat('Y/m/d', $value) !== false ||
                            \DateTime::createFromFormat('Y-m-d', $value) !== false
                        ))){
                        $valid = true;
                        if($qb){
                            $valid = $qb->expr()->literal($value);
                        }
                    }
                    break;
                case 'integer':
                case 'smallint':
                case 'bigint': // String
                    if(is_int($value)){
                        $valid = true;
                        if($qb){
                            $valid = $value;
                        }
                    }
                    break;
                case 'boolean':
                    if(is_bool($value)){
                        $valid = true;
                        if($qb){
                            $valid = $value;
                        }
                    }
                    break;
                case 'decimal': // string
                case 'float': // double
                    if(is_double($value)){
                        $valid = true;
                        if($qb){
                            $valid = $value;
                        }
                    }
                    break;
                case 'guid':
                    if(preg_match('/^\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/', $value)){
                        $valid = true;
                        if($qb){
                            $valid = $value;
                        }
                    }
                    break;
            }
        }
        return $valid;
    }

    public function validateFields($obj, ClassMetadata $classMetadata, $excepts = array('canonical','slug','salt','token')){
        $msgs = array();
        foreach($classMetadata->getFieldNames() as $fieldName){
            $except = false;
            foreach($excepts as $ex){
                if(strpos(strtolower($fieldName), strtolower($ex)) !== false){
                    $except = true;
                }
            }
            if(!$except){
                $field = $classMetadata->getFieldMapping($fieldName);
                $value = $classMetadata->getFieldValue($obj, $fieldName);
                if(!$value || empty($value)){
                    if(!$field['nullable']){
                        $msgs[$fieldName]['nullable'] = 'El atributo "'.$fieldName.'" no debe ser VACÍO.';
                    }
                }else{
                    if($field['unique']){
                        $qb = $this->getManager()->getRepository($classMetadata->getName())->createQueryBuilder('a');
                        $valQuery = $this->validateTypeField($field['type'], $value, $qb);
                        $qb->select('count(a.id) as num')->where($qb->expr()->eq('a.'.$classMetadata->getColumnName($fieldName),$valQuery));
                        $unique = $qb->getQuery()->getResult();
                        if($unique[0]['num'] > 0){
                            $msgs[$fieldName]['unique'] = 'El atributo "'.$fieldName.'" con valor "'.$value.'" ya existe.';
                        };
                    }elseif(!$this->validateTypeField($field['type'], $value)){
                        $msgs[$fieldName]['valid'] = 'El atributo "'.$fieldName.'" debe ser de tipo "'.strtoupper($field['type']).'".';
                    }
                }
            }
        }
        return $msgs;
    }

    public function validateAssociations($obj, ClassMetadata $classMetadata){
        $msgs = array();
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach($classMetadata->getAssociationNames() as $associationName){
            $value = $accessor->getValue($obj, $associationName);
            $msgs = $this->validateOneAssociation($classMetadata, $value, $associationName);
        }
        return $msgs;
    }

    public function validateOneAssociation(ClassMetadata $classMetadata, $value, $associationName){
        $msgs = array();
        $association = $classMetadata->getAssociationMapping($associationName);
        $configJoin = null;
        if(isset($association['joinColumns'])){
            $configJoin = $association['joinColumns'];
        }
        if($value){
            $valid = true;
            if($classMetadata->isCollectionValuedAssociation($associationName) && is_array($value)){
                //$objs = new ArrayCollection();
                foreach($value as $v){
                    if(!is_a($v, $association['targetEntity'])){
                        if($this->validateTypeField('guid', $v)){
                            $entity = $this->getManager()->getRepository($association['targetEntity'])->find($v);
                            if(!$entity){
                                $valid = false;
                            }
                        }else{
                            $valid = false;
                        }
                        if(!$valid){
                            break;
                        }
                    }
                }
                if(!$valid){
                    $msgs[$associationName]['valid'] = '"'.$associationName.'" no es una colección de Objetos de "'.strtoupper($association['targetEntity']).'".';
                }
            }elseif($classMetadata->isSingleValuedAssociation($associationName)){
                if(!is_a($value, $association['targetEntity'])){
                    if($this->validateTypeField('guid', $value)){
                        $entity = $this->getManager()->getRepository($association['targetEntity'])->find($value);
                        if(!$entity){
                            $valid = false;
                        }
                    }else{
                        $valid = false;
                    }
                }
                if(!$valid){
                    $msgs[$associationName]['valid'] = 'Se esperaba que "'.$associationName.'" fuera un Objeto "'.strtoupper($association['targetEntity']).'".';
                }
            }
        }/*elseif($classMetadata->isNullable($associationName)){
                $msgs[$associationName]['valid'] = '"'.$associationName.'" no debe ser VACÍO.';
            }*/
        elseif($configJoin && !$configJoin[0]['nullable']){
            $msgs[$associationName]['valid'] = '"'.$associationName.'" no es válido.';
        }
        return $msgs;
    }

    public function captureErrorFlush($em, $obj, $msg){
        try{
            $em->flush();
        }catch(\Exception $e){
            /*var_dump($e);
            die;*/
            $classMetadata = $this->getClassMetadata($obj);
            $msgs = 'No details.';
            if($classMetadata){
                $msgs = $this->validateFields($obj, $classMetadata);
                $msgs = array_merge($msgs, $this->validateAssociations($obj, $classMetadata));
            }
            $name = explode('\\',get_class($obj));
            $name = $name[count($name)-1];
            $nombre = '';
            if(method_exists($obj,'getNombre') && $obj->getNombre()){
                $nombre = '" de nombre "'.$obj->getNombre();
            }
            switch($msg){
                case 'borrar':
                    $msg = 'No se pudo borrar el recurso "'.$name.$nombre.'"';
                    break;
                case 'crear':
                    $msg = 'No se pudo agregar el recurso "'.$name.$nombre.'"';
                    break;
                case 'editar':
                    $msg = 'No se pudo actualizar el recurso "'.$name.$nombre.'"';
                    break;
                case 'default':
                    $msg = 'No se pudo realizar la acción en el recurso "'.$name.$nombre.'"';
                    break;
            }
            $obj = array(
                'errors' => array(
                    '400' => array(
                        'message'   => $msg,
                        'code'      => "400",
                        'details'   => $msgs,
                    ),
                ),
            );
        }
        return $obj;
    }

    public function getColeccionObject(ClassMetadata $metadata, $data, $type, $request, $collectionName, $returnObjs = false){
        if(isset($data[$collectionName])){
            if(is_string($data[$collectionName]) && substr_count($data[$collectionName],'[') == 1 && substr_count($data[$collectionName],']') == 1 && substr_count($data[$collectionName],',') >= 1){
                $collections = explode(',',str_replace('[','',str_replace(']','',str_replace(' ','',$data[$collectionName]))));
            }elseif(is_array($data[$collectionName])){
                $collections = $data[$collectionName];
            }
            if(is_array($collections)){
                $className = $metadata->getAssociationTargetClass($collectionName);
                foreach($collections as $i => $nombre){
                    $qb = $this->getManager()->getRepository($className)->createQueryBuilder('e');
                    if(!$returnObjs){
                        $qb->select('e.id');
                    }
                    if(!$this->validateTypeField('guid',$nombre)){
                        $qb->andWhere($qb->expr()->like('e.nombre',$qb->expr()->literal('%'.$nombre.'%')));
                    }else{
                        $qb->andWhere($qb->expr()->eq('e.nombre',$qb->expr()->literal($nombre)));
                    }
                    $collection = $qb->getQuery()->execute();
                    $id = false;
                    if(empty($collection)){
                        $collection = new $className();
                        $collection
                            ->setNombre($nombre)
                            ->setDescripcion($nombre)
                        ;
                        $this->getManager()->persist($collection);
                        $this->getManager()->flush();
                        if($returnObjs){
                            $id = $collection;
                        }else{
                            $id = $collection->getId();
                        }
                    }elseif(isset($collection[0])){
                        $collection = $collection[0];
                        if(is_array($collection) && isset($collection['id'])){
                            $id = $collection['id'];
                        }elseif($returnObjs){
                            $id = $collection;
                        }
                    }
                    if($id){
                        $collections[$i] = $id;
                    }
                }
                $data[$collectionName] = $collections;
                if($returnObjs){
                    return $collections;
                }
                if($request->query->get($type->getName())){
                    $request->query->set($type->getName(), $data);
                }
                if($request->attributes->get($type->getName())){
                    $request->attributes->set($type->getName(), $data);
                }
                if($request->request->get($type->getName())){
                    $request->request->set($type->getName(), $data);
                }
            }
        }
    }

    public function getColectionsObjects($obj, $type, $request, $coleccionsNames = null){
        $data = $request->get($type->getName(), null);
        $metadata = $this->getClassMetadata($obj);
        if(!is_array($coleccionsNames)){
            $coleccionsNames = $metadata->getAssociationNames();
        }
        foreach($coleccionsNames as $cn){
            $this->getColeccionObject($metadata, $data, $type, $request, $cn);
        }
    }

}
