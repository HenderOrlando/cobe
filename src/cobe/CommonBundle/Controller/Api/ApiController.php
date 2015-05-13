<?php

namespace cobe\CommonBundle\Controller\Api;

use cobe\UsuariosBundle\Entity\RolUsuario;
use Pagerfanta\Pagerfanta;
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
        $datos = $this->container->get('serializer')->serialize($objs, $format);

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
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $isValid = true;
            if($isValid){
                if($save){
                    $em = $this->getManager();
                    $em->persist($obj);
                    /*try{*/
                    $em->flush();
                    $saved = true;
                    /*}catch (\Exception $e){
                        $name = explode('\\',get_class($obj));
                        $name = $name[count($name)-1];
                        $errors = array(
                            array(
                                'message'   => 'No se pudo crear el recurso "'.$name,
                                'code'      => "400",
                            )
                        );
                    }*/
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
        $rta = array(
            'form'  => array(
                'saved'     => $saved,
                'isValid'   => $isValid,
                'html'      => $this->renderView('cobeCommonBundle:Api:_form.html.twig', array(
                    'form' => $form->createView(),
                )),
            ),
        );
        if(!empty($errors)){
            $rta['errors'] = $errors;
        }
        if($saved){
            $rta['recurso'] = $obj;
        }
        return $rta;
    }
    /**
     * Regresa opciones de API para Ciudades.
     *
     * @Route("/", name="options")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsCiudadesAction(Request $request)
    {
        $opciones = array(
            '/ciudades' => array(
                'method'        => 'OPTIONS',
                'route'         => '/ciudades',
                'description'   => 'Opciones para uso de API de ciudades.',
            ),
            '/estados' => array(
                'method'        => 'OPTIONS',
                'route'         => '/estados',
                'description'   => 'Opciones para uso de API de estados.',
            ),
            '/etiquetas' => array(
                'method'        => 'OPTIONS',
                'route'         => '/etiquetas',
                'description'   => 'Opciones para uso de API de etiquetas.',
            ),
            '/idiomas' => array(
                'method'        => 'OPTIONS',
                'route'         => '/idiomas',
                'description'   => 'Opciones para uso de API de idiomas.',
            ),
            '/paises' => array(
                'method'        => 'OPTIONS',
                'route'         => '/paises',
                'description'   => 'Opciones para uso de API de paises.',
            ),
            '/roles' => array(
                'method'        => 'OPTIONS',
                'route'         => '/roles',
                'description'   => 'Opciones para uso de API de roles.',
            ),
            '/tipos' => array(
                'method'        => 'OPTIONS',
                'route'         => '/tipos',
                'description'   => 'Opciones para uso de API de tipos.',
            ),
            '/traducciones' => array(
                'method'        => 'OPTIONS',
                'route'         => '/traducciones',
                'description'   => 'Opciones para uso de API de traducciones.',
            ),
            '/aptitudes' => array(
                'method'        => 'OPTIONS',
                'route'         => '/aptitudes',
                'description'   => 'Opciones para uso de API de aptitudes.',
            ),
            '/centrosestudios' => array(
                'method'        => 'OPTIONS',
                'route'         => '/centrosestudios',
                'description'   => 'Opciones para uso de API de centrosestudios.',
            ),
            '/estudio' => array(
                'method'        => 'OPTIONS',
                'route'         => '/estudio',
                'description'   => 'Opciones para uso de API de estudio.',
            ),
            '/intereses' => array(
                'method'        => 'OPTIONS',
                'route'         => '/intereses',
                'description'   => 'Opciones para uso de API de intereses.',
            ),
            '/proyectos' => array(
                'method'        => 'OPTIONS',
                'route'         => '/proyectos',
                'description'   => 'Opciones para uso de API de proyectos.',
            ),
            '/recomendaciones' => array(
                'method'        => 'OPTIONS',
                'route'         => '/recomendaciones',
                'description'   => 'Opciones para uso de API de recomendaciones.',
            ),
            '/reconocimientos' => array(
                'method'        => 'OPTIONS',
                'route'         => '/reconocimientos',
                'description'   => 'Opciones para uso de API de reconocimientos.',
            ),
            '/caracteristicas' => array(
                'method'        => 'OPTIONS',
                'route'         => '/caracteristicas',
                'description'   => 'Opciones para uso de API de caracteristicas.',
            ),
            '/estadisticas' => array(
                'method'        => 'OPTIONS',
                'route'         => '/estadisticas',
                'description'   => 'Opciones para uso de API de estadisticas.',
            ),
            '/grupos' => array(
                'method'        => 'OPTIONS',
                'route'         => '/grupos',
                'description'   => 'Opciones para uso de API de grupos.',
            ),
            '/votaciones' => array(
                'method'        => 'OPTIONS',
                'route'         => '/votaciones',
                'description'   => 'Opciones para uso de API de votaciones.',
            ),
            '/categorias' => array(
                'method'        => 'OPTIONS',
                'route'         => '/categorias',
                'description'   => 'Opciones para uso de API de categorias.',
            ),
            '/plantillas' => array(
                'method'        => 'OPTIONS',
                'route'         => '/plantillas',
                'description'   => 'Opciones para uso de API de plantillas.',
            ),
            '/publicaciones' => array(
                'method'        => 'OPTIONS',
                'route'         => '/publicaciones',
                'description'   => 'Opciones para uso de API de publicaciones.',
            ),
            '/mensajes' => array(
                'method'        => 'OPTIONS',
                'route'         => '/mensajes',
                'description'   => 'Opciones para uso de API de mensajes.',
            ),
            '/ofertaslaborales' => array(
                'method'        => 'OPTIONS',
                'route'         => '/ofertaslaborales',
                'description'   => 'Opciones para uso de API de ofertaslaborales.',
            ),
            '/empresas' => array(
                'method'        => 'OPTIONS',
                'route'         => '/empresas',
                'description'   => 'Opciones para uso de API de empresas.',
            ),
            '/historiales' => array(
                'method'        => 'OPTIONS',
                'route'         => '/historiales',
                'description'   => 'Opciones para uso de API de historiales.',
            ),
            '/personas' => array(
                'method'        => 'OPTIONS',
                'route'         => '/personas',
                'description'   => 'Opciones para uso de API de personas.',
            ),
            '/usuarios' => array(
                'method'        => 'OPTIONS',
                'route'         => '/usuarios',
                'description'   => 'Opciones para uso de API de usuarios.',
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_ciudades', true);

        return $this->getJsonResponse($opciones, $request);
    }
}
