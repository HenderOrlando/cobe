<?php

namespace cobe\CommonBundle\Controller\Api;

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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Configuration\Route as HateoasRoute;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;

use Doctrine\Common\Persistence\ObjectManager;
use cobe\CommonBundle\Controller\Api\ApiController;

use cobe\CommonBundle\Entity\Traduccion;
use cobe\CommonBundle\Form\TraduccionType;
use cobe\CommonBundle\Repository\TraduccionRepository;

/**
 * API Traduccion Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiTraduccionController extends ApiController
{
    /**
     * Retorna el repositorio de Traduccion
     *
     * @return TraduccionRepository
     */
    public function getTraduccionRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Traduccion');
    }

    /**
     * Regresa opciones de API para Traducciones.
     *
     * @Route("/traducciones/attributes", name="options_traducciones_validate")
     * @Route("/traducciones/attributes/", name="options_traducciones_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Idioma();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Traducciones.
     *
     * @Route("/traducciones", name="options_traducciones")
     * @Route("/traducciones/", name="options_traducciones_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsTraduccionesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/traducciones',
                'method'        => 'GET',
                'description'   => 'Lista todos los traducciones.',
                'examples'       => array(
                    '/traducciones',
                    '/traducciones/',
                ),
            ),
            array(
                'route'         => '/traducciones/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los traducciones.',
                'examples'       => array(
                    '/traducciones/{id}',
                    '/traducciones/{id}/',
                ),
            ),
            array(
                'route'         => '/traducciones/params',
                'method'        => 'GET',
                'description'   => 'Lista las traducciones que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/traducciones/params/?traduccion[nombre]=Ecuador',
                    '/traducciones/params/?traduccion[descripcion]=Suramérica',
                    '/traducciones/params/?traduccion[descripcion]=País-Suraméricano',
                    '/traducciones/params/?traduccion[nombre]=República-Bolivariana-de-Venezuela&traduccion[descripcion]=suramerica',
                    '/traducciones/params/?traduccion[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/traducciones/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las traducciones iniciando en el Offset.',
                'examples'       => array(
                    '/traducciones/o1/',
                    '/traducciones/o10',
                ),
            ),
            array(
                'route'         => '/traducciones/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las traducciones iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/traducciones/l2/',
                    '/traducciones/l10',
                ),
            ),
            array(
                'route'         => '/traducciones/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las traducciones iniciando en offset hasta limit.',
                'examples'       => array(
                    '/traducciones/o1/l2/',
                    '/traducciones/o10/l10',
                ),
            ),
            array(
                'route'         => '/traducciones/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una traducción.',
                'examples'       => array(
                    '/traducciones/new/',
                    '/traducciones/new',
                ),
            ),
            array(
                'route'         => '/traducciones',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea traducciones. Puede recibir datos de varias traducciones.',
                'examples'       => array(
                    '/traducciones/',
                    '/traducciones',
                ),
            ),
            array(
                'route'         => '/traducciones/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de traduccion para editar.',
                'examples'       => array(
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/traducciones/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de traduccion.',
                'examples'       => array(
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/traducciones/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de traduccion',
                'examples'       => array(
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/traducciones/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar traduccion.',
                'examples'       => array(
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/traducciones/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra traduccion.',
                'examples'       => array(
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/traducciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_traducciones', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Traducciones.
     *
     * @Route("/traducciones", name="get_traducciones")
     * @Route("/traducciones/", name="get_traducciones_")
     * @Template()
     * @Method("GET")
     */
    public function getTraduccionesAction(Request $request)
    {
        $repository = $this->getTraduccionRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Traducciones
     *
     * @Route("/traducciones/new", name="new_traducciones")
     * @Route("/traducciones/new/", name="new_traducciones_")
     * @Template()
     * @Method("GET")
     */
    public function newTraduccionesAction(Request $request)
    {
        $type = new TraduccionType($this->generateUrl('post_traducciones'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Traducciones.
     *
     * @Route("/traducciones", name="post_traducciones")
     * @Route("/traducciones/", name="post_traducciones_")
     * @Template()
     * @Method("POST")
     */
    public function postTraduccionesAction(Request $request)
    {
        $traduccion = new Traduccion();
        $type = new TraduccionType($this->generateUrl('post_traducciones'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Traducción.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $traduccion, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($traduccion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Traduccion.
     *
     * @Route("/traducciones/{slug}", name="get_traducciones_slug")
     * @Route("/traducciones/{slug}/", name="get_traducciones_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getTraduccionAction(Request $request, $slug)
    {
        $traduccion = null;
        switch($slug){
            case 'params':
                $datos = $request->get('traduccion', false);
                if($datos){
                    $traduccion = $this->getTraduccionRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $traduccion = $this->getTraduccionRepository()->find($slug);
                break;
        }
        if (!$traduccion) {
            $traduccion = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($traduccion, $request);
    }

    /**
     * Regresa el formulario para poder editar Traduccion existente.
     *
     * @Route("/traducciones/{slug}/edit", name="edit_traducciones")
     * @Route("/traducciones/{slug}/edit/", name="edit_traducciones_")
     * @Template()
     * @Method("GET")
     */
    public function editTraduccionAction(Request $request, $slug)
    {
        $traduccion = $this->getTraduccionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new TraduccionType($this->generateUrl('put_traducciones', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $traduccion );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Traduccion existente.
     *
     * @Route("/traducciones/{slug}", name="put_traducciones")
     * @Route("/traducciones/{slug}/", name="put_traducciones_")
     * @Template()
     * @Method("PUT")
     */
    public function putTraduccionAction(Request $request, $slug)
    {
        $traduccion = $this->getTraduccionRepository()->find($slug);
        $type = new TraduccionType($this->generateUrl('put_traducciones', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Traducción.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $traduccion, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($traduccion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Traduccion existente.
     *
     * @Route("/traducciones/{slug}", name="patch_traducciones")
     * @Route("/traducciones/{slug}/", name="patch_traducciones_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchTraduccionAction(Request $request, $slug)
    {
        $traduccion = $this->getTraduccionRepository()->find($slug);
        $type = new TraduccionType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Traducción.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $traduccion){
            $repo = $this->getTraduccionRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($traduccion));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($traduccion, $id) !== $dato){
                            $accessor->setValue($traduccion, $id, $dato);
                            $isModify = true;
                        }
                    }elseif($metadata->isCollectionValuedAssociation($id)){
                        $collection = $this->getColeccionObject($metadata, $datos, $type, $request, $id, true);
                        //$datos_ = $request->request->get($type->getName(), false);
                        //$dato = $datos[$id] = $datos_[$id];
                        $msgs = $this->validateOneAssociation($metadata, $collection, $id);
                        if(empty($msgs)){
                            $set = 'set'.ucfirst($id);
                            if(method_exists($traduccion,$set)){
                                //$collection = new ArrayCollection($collection);
                                $traduccion->$set($collection);
                            }
                            $isModify = true;
                        }
                    }
                }
            }
            if($isModify){
                $traduccion = $this->captureErrorFlush($em, $traduccion, 'editar');
            }
            $rta = $traduccion;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Traducciones..
     *
     * @Route("/traducciones/{slug}/remove", name="remove_traducciones")
     * @Route("/traducciones/{slug}/remove/", name="remove_traducciones_")
     * @Template()
     * @Method("GET")
     */
    public function removeTraduccionesAction(Request $request, $slug)
    {
        $traduccion = $this->getTraduccionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($traduccion){
            $form = $this->createDeleteForm($slug,'delete_traducciones');
            $rta = array(
                'form'  => array(
                    'html'      => $this->renderView('cobeCommonBundle:Api:_form.html.twig', array(
                        'form' => $form->createView(),
                    )),
                ),
            );
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Elimina Traducciones
     *
     * @Route("/traducciones/{slug}", name="delete_traducciones")
     * @Route("/traducciones/{slug}/", name="delete_traducciones_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteTraduccionesAction(Request $request, $slug)
    {
        $traduccion = $this->getTraduccionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($traduccion){
            $form = $this->createDeleteForm($slug,'delete_traducciones');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $traduccion){
                $em = $this->getManager();
                $em->remove($traduccion);
                $traduccion = $this->captureErrorFlush($em, $traduccion, 'borrar');
                $rta = $traduccion;
                if(!$rta['errors']){
                    $deleted = true;
                }
            }
            if(!$deleted){
                $rta = array(
                    'form'  => array(
                        'deleted'   => $deleted,
                        'isValid'   => $isValid,
                        'html'      => $this->renderView('cobeCommonBundle:Api:_form.html.twig', array(
                            'form' => $form->createView(),
                        )),
                    ),
                );
            }
        }
        return $this->getJsonResponse($rta, $request);
    }
}
