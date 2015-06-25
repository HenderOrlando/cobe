<?php

namespace cobe\CurriculosBundle\Controller\Api;

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

use cobe\CurriculosBundle\Entity\Recomendacion;
use cobe\CurriculosBundle\Form\RecomendacionType;
use cobe\CurriculosBundle\Repository\RecomendacionRepository;

/**
 * API Recomendacion Controller.
 *
 * @package cobe\CurriculosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiRecomendacionController extends ApiController
{
    /**
     * Retorna el repositorio de Recomendacion
     *
     * @return RecomendacionRepository
     */
    public function getRecomendacionRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:Recomendacion');
    }

    /**
     * Regresa opciones de API para Recomendaciones.
     *
     * @Route("/recomendaciones/attributes", name="options_recomendaciones_validate")
     * @Route("/recomendaciones/attributes/", name="options_recomendaciones_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Recomendacion();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Recomendaciones.
     *
     * @Route("/recomendaciones", name="options_recomendaciones")
     * @Route("/recomendaciones/", name="options_recomendaciones_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsRecomendacionesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/recomendaciones',
                'method'        => 'GET',
                'description'   => 'Lista todos las recomendaciones.',
                'examples'       => array(
                    '/recomendaciones',
                    '/recomendaciones/',
                ),
            ),
            array(
                'route'         => '/recomendaciones/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos las recomendaciones.',
                'examples'       => array(
                    '/recomendaciones/{id}',
                    '/recomendaciones/{id}/',
                ),
            ),
            array(
                'route'         => '/recomendaciones/params',
                'method'        => 'GET',
                'description'   => 'Lista las recomendaciones que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/recomendaciones/params/?recomendacion[nombre]=Ecuador',
                    '/recomendaciones/params/?recomendacion[descripcion]=Suramérica',
                    '/recomendaciones/params/?recomendacion[descripcion]=Recomendación-Suraméricano',
                    '/recomendaciones/params/?recomendacion[nombre]=República-Bolivariana-de-Venezuela&recomendacion[descripcion]=suramerica',
                    '/recomendaciones/params/?recomendacion[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/recomendaciones/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las recomendaciones iniciando en el Offset.',
                'examples'       => array(
                    '/recomendaciones/o1/',
                    '/recomendaciones/o10',
                ),
            ),
            array(
                'route'         => '/recomendaciones/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las recomendaciones iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/recomendaciones/l2/',
                    '/recomendaciones/l10',
                ),
            ),
            array(
                'route'         => '/recomendaciones/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las recomendaciones iniciando en offset hasta limit.',
                'examples'       => array(
                    '/recomendaciones/o1/l2/',
                    '/recomendaciones/o10/l10',
                ),
            ),
            array(
                'route'         => '/recomendaciones/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un recomendación.',
                'examples'       => array(
                    '/recomendaciones/new/',
                    '/recomendaciones/new',
                ),
            ),
            array(
                'route'         => '/recomendaciones',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea recomendaciones. Puede recibir datos de varias recomendaciones.',
                'examples'       => array(
                    '/recomendaciones/',
                    '/recomendaciones',
                ),
            ),
            array(
                'route'         => '/recomendaciones/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de recomendacion para editar.',
                'examples'       => array(
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/recomendaciones/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de recomendacion.',
                'examples'       => array(
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/recomendaciones/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de recomendacion',
                'examples'       => array(
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/recomendaciones/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar recomendacion.',
                'examples'       => array(
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/recomendaciones/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra recomendacion.',
                'examples'       => array(
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/recomendaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_recomendaciones', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Recomendaciones.
     *
     * @Route("/recomendaciones", name="get_recomendaciones")
     * @Route("/recomendaciones/", name="get_recomendaciones_")
     * @Template()
     * @Method("GET")
     */
    public function getRecomendacionesAction(Request $request)
    {
        $repository = $this->getRecomendacionRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Recomendaciones
     *
     * @Route("/recomendaciones/new", name="new_recomendaciones")
     * @Route("/recomendaciones/new/", name="new_recomendaciones_")
     * @Template()
     * @Method("GET")
     */
    public function newRecomendacionesAction(Request $request)
    {
        $type = new RecomendacionType($this->generateUrl('post_recomendaciones'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Recomendaciones.
     *
     * @Route("/recomendaciones", name="post_recomendaciones")
     * @Route("/recomendaciones/", name="post_recomendaciones_")
     * @Template()
     * @Method("POST")
     */
    public function postRecomendacionesAction(Request $request)
    {
        $recomendacion = new Recomendacion();
        $type = new RecomendacionType($this->generateUrl('post_recomendaciones'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Recomendación.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $recomendacion, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($recomendacion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Recomendacion.
     *
     * @Route("/recomendaciones/{slug}", name="get_recomendaciones_slug")
     * @Route("/recomendaciones/{slug}/", name="get_recomendaciones_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getRecomendacionAction(Request $request, $slug)
    {
        $recomendacion = null;
        switch($slug){
            case 'params':
                $datos = $request->get('recomendacion', false);
                if($datos){
                    $recomendacion = $this->getRecomendacionRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $recomendacion = $this->getRecomendacionRepository()->find($slug);
                break;
        }
        if (!$recomendacion) {
            $recomendacion = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Recomendación no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($recomendacion, $request);
    }

    /**
     * Regresa el formulario para poder editar Recomendacion existente.
     *
     * @Route("/recomendaciones/{slug}/edit", name="edit_recomendaciones")
     * @Route("/recomendaciones/{slug}/edit/", name="edit_recomendaciones_")
     * @Template()
     * @Method("GET")
     */
    public function editRecomendacionAction(Request $request, $slug)
    {
        $recomendacion = $this->getRecomendacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Recomendación no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new RecomendacionType($this->generateUrl('put_recomendaciones', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $recomendacion );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Recomendacion existente.
     *
     * @Route("/recomendaciones/{slug}", name="put_recomendaciones")
     * @Route("/recomendaciones/{slug}/", name="put_recomendaciones_")
     * @Template()
     * @Method("PUT")
     */
    public function putRecomendacionAction(Request $request, $slug)
    {
        $recomendacion = $this->getRecomendacionRepository()->find($slug);
        $type = new RecomendacionType($this->generateUrl('put_recomendaciones', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Recomendación.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $recomendacion, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($recomendacion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Recomendacion existente.
     *
     * @Route("/recomendaciones/{slug}", name="patch_recomendaciones")
     * @Route("/recomendaciones/{slug}/", name="patch_recomendaciones_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchRecomendacionAction(Request $request, $slug)
    {
        $recomendacion = $this->getRecomendacionRepository()->find($slug);
        $type = new RecomendacionType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Recomendación.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $recomendacion){
            $repo = $this->getRecomendacionRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($recomendacion));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($recomendacion, $id) !== $dato){
                            $accessor->setValue($recomendacion, $id, $dato);
                            $isModify = true;
                        }
                    }elseif($metadata->isCollectionValuedAssociation($id)){
                        $collection = $this->getColeccionObject($metadata, $datos, $type, $request, $id, true);
                        //$datos_ = $request->request->get($type->getName(), false);
                        //$dato = $datos[$id] = $datos_[$id];
                        $msgs = $this->validateOneAssociation($metadata, $collection, $id);
                        if(empty($msgs)){
                            $set = 'set'.ucfirst($id);
                            if(method_exists($recomendacion,$set)){
                                //$collection = new ArrayCollection($collection);
                                $recomendacion->$set($collection);
                            }
                            $isModify = true;
                        }
                    }
                }
            }
            if($isModify){
                $recomendacion = $this->captureErrorFlush($em, $recomendacion, 'editar');
            }
            $rta = $recomendacion;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Recomendaciones..
     *
     * @Route("/recomendaciones/{slug}/remove", name="remove_recomendaciones")
     * @Route("/recomendaciones/{slug}/remove/", name="remove_recomendaciones_")
     * @Template()
     * @Method("GET")
     */
    public function removeRecomendacionesAction(Request $request, $slug)
    {
        $recomendacion = $this->getRecomendacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Recomendación no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($recomendacion){
            $form = $this->createDeleteForm($slug,'delete_recomendaciones');
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
     * Elimina Recomendaciones
     *
     * @Route("/recomendaciones/{slug}", name="delete_recomendaciones")
     * @Route("/recomendaciones/{slug}/", name="delete_recomendaciones_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteRecomendacionesAction(Request $request, $slug)
    {
        $recomendacion = $this->getRecomendacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Recomendación no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($recomendacion){
            $form = $this->createDeleteForm($slug,'delete_recomendaciones');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $recomendacion){
                $em = $this->getManager();
                $em->remove($recomendacion);
                $recomendacion = $this->captureErrorFlush($em, $recomendacion, 'borrar');
                $rta = $recomendacion;
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
