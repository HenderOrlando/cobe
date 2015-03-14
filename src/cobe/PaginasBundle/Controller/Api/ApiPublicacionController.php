<?php

namespace cobe\PaginasBundle\Controller\Api;

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

use cobe\PaginasBundle\Entity\Publicacion;
use cobe\PaginasBundle\Form\PublicacionType;
use cobe\PaginasBundle\Repository\PublicacionRepository;

/**
 * API Publicacion Controller.
 *
 * @package cobe\PaginasBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiPublicacionController extends ApiController
{
    /**
     * Retorna el repositorio de Publicacion
     *
     * @return PublicacionRepository
     */
    public function getPublicacionRepository()
    {
        return $this->getManager()->getRepository('cobePaginasBundle:Publicacion');
    }

    /**
     * Regresa opciones de API para Publicaciones.
     *
     * @Route("/publicaciones", name="options_publicaciones")
     * @Route("/publicaciones/", name="options_publicaciones_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsPublicacionesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/publicaciones',
                'method'        => 'GET',
                'description'   => 'Lista todos los publicaciones.',
                'examples'       => array(
                    '/publicaciones',
                    '/publicaciones/',
                ),
            ),
            array(
                'route'         => '/publicaciones/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los publicaciones.',
                'examples'       => array(
                    '/publicaciones/{id}',
                    '/publicaciones/{id}/',
                ),
            ),
            array(
                'route'         => '/publicaciones/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/publicaciones/params/?publicacion[nombre]=Ecuador',
                    '/publicaciones/params/?publicacion[descripcion]=Suramérica',
                    '/publicaciones/params/?publicacion[descripcion]=País-Suraméricano',
                    '/publicaciones/params/?publicacion[nombre]=República-Bolivariana-de-Venezuela&publicacion[descripcion]=suramerica',
                    '/publicaciones/params/?publicacion[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/publicaciones/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/publicaciones/o1/',
                    '/publicaciones/o10',
                ),
            ),
            array(
                'route'         => '/publicaciones/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/publicaciones/l2/',
                    '/publicaciones/l10',
                ),
            ),
            array(
                'route'         => '/publicaciones/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/publicaciones/o1/l2/',
                    '/publicaciones/o10/l10',
                ),
            ),
            array(
                'route'         => '/publicaciones/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/publicaciones/new/',
                    '/publicaciones/new',
                ),
            ),
            array(
                'route'         => '/publicaciones',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/publicaciones/',
                    '/publicaciones',
                ),
            ),
            array(
                'route'         => '/publicaciones/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de publicacion para editar.',
                'examples'       => array(
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/publicaciones/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los etributos de publicacion.',
                'examples'       => array(
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/publicaciones/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de publicacion',
                'examples'       => array(
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/publicaciones/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar publicacion.',
                'examples'       => array(
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/publicaciones/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra publicacion.',
                'examples'       => array(
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/publicaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_publicaciones', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Publicaciones.
     *
     * @Route("/publicaciones", name="get_publicaciones")
     * @Route("/publicaciones/", name="get_publicaciones_")
     * @Template()
     * @Method("GET")
     */
    public function getPublicacionesAction(Request $request)
    {
        $repository = $this->getPublicacionRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Publicaciones
     *
     * @Route("/publicaciones/new", name="new_publicaciones")
     * @Route("/publicaciones/new/", name="new_publicaciones_")
     * @Template()
     * @Method("GET")
     */
    public function newPublicacionesAction(Request $request)
    {
        $type = new PublicacionType($this->generateUrl('post_publicaciones'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Publicaciones.
     *
     * @Route("/publicaciones", name="post_publicaciones")
     * @Route("/publicaciones/", name="post_publicaciones_")
     * @Template()
     * @Method("POST")
     */
    public function postPublicacionesAction(Request $request)
    {
        $publicacion = new Publicacion();
        $type = new PublicacionType($this->generateUrl('post_publicaciones'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $publicacion, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($publicacion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Publicacion.
     *
     * @Route("/publicaciones/{slug}", name="get_publicaciones_slug")
     * @Route("/publicaciones/{slug}/", name="get_publicaciones_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getPublicacionAction(Request $request, $slug)
    {
        $publicacion = null;
        switch($slug){
            case 'params':
                $datos = $request->get('publicacion', false);
                if($datos){
                    $publicacion = $this->getPublicacionRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $publicacion = $this->getPublicacionRepository()->find($slug);
                break;
        }
        if (!$publicacion) {
            $publicacion = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($publicacion, $request);
    }

    /**
     * Regresa el formulario para poder editar Publicacion existente.
     *
     * @Route("/publicaciones/{slug}/edit", name="edit_publicaciones")
     * @Route("/publicaciones/{slug}/edit/", name="edit_publicaciones_")
     * @Template()
     * @Method("GET")
     */
    public function editPublicacionAction(Request $request, $slug)
    {
        $publicacion = $this->getPublicacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new PublicacionType($this->generateUrl('put_publicaciones', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $publicacion );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Publicacion existente.
     *
     * @Route("/publicaciones/{slug}", name="put_publicaciones")
     * @Route("/publicaciones/{slug}/", name="put_publicaciones_")
     * @Template()
     * @Method("PUT")
     */
    public function putPublicacionAction(Request $request, $slug)
    {
        $publicacion = $this->getPublicacionRepository()->find($slug);
        $type = new PublicacionType($this->generateUrl('put_publicaciones', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $publicacion, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($publicacion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Publicacion existente.
     *
     * @Route("/publicaciones/{slug}", name="patch_publicaciones")
     * @Route("/publicaciones/{slug}/", name="patch_publicaciones_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchPublicacionAction(Request $request, $slug)
    {
        $publicacion = $this->getPublicacionRepository()->find($slug);
        $type = new PublicacionType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $publicacion){
            $repo = $this->getPublicacionRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($publicacion));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($publicacion, $id) !== $dato){
                        $accessor->setValue($publicacion, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($publicacion));
                    $name = $name[count($name)-1];
                    $publicacion = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $publicacion;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Publicaciones..
     *
     * @Route("/publicaciones/{slug}/remove", name="remove_publicaciones")
     * @Route("/publicaciones/{slug}/remove/", name="remove_publicaciones_")
     * @Template()
     * @Method("GET")
     */
    public function removePublicacionesAction(Request $request, $slug)
    {
        $publicacion = $this->getPublicacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($publicacion){
            $form = $this->createDeleteForm($slug,'delete_publicaciones');
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
     * Elimina Publicaciones
     *
     * @Route("/publicaciones/{slug}", name="delete_publicaciones")
     * @Route("/publicaciones/{slug}/", name="delete_publicaciones_")
     * @Template()
     * @Method("DELETE")
     */
    public function deletePublicacionesAction(Request $request, $slug)
    {
        $publicacion = $this->getPublicacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($publicacion){
            $form = $this->createDeleteForm($slug,'delete_publicaciones');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $publicacion){
                $em = $this->getManager();
                $em->remove($publicacion);
                $em->flush();
                $rta = $publicacion;
                $deleted = true;
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
