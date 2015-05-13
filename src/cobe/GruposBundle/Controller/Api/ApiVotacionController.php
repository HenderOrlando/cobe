<?php

namespace cobe\GruposBundle\Controller\Api;

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

use cobe\GruposBundle\Entity\Votacion;
use cobe\GruposBundle\Form\VotacionType;
use cobe\GruposBundle\Repository\VotacionRepository;

/**
 * API Votacion Controller.
 *
 * @package cobe\GruposBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiVotacionController extends ApiController
{
    /**
     * Retorna el repositorio de Votacion
     *
     * @return VotacionRepository
     */
    public function getVotacionRepository()
    {
        return $this->getManager()->getRepository('cobeGruposBundle:Votacion');
    }

    /**
     * Regresa opciones de API para Votaciones.
     *
     * @Route("/votaciones", name="options_votaciones")
     * @Route("/votaciones/", name="options_votaciones_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsVotacionesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/votaciones',
                'method'        => 'GET',
                'description'   => 'Lista todos los votaciones.',
                'examples'       => array(
                    '/votaciones',
                    '/votaciones/',
                ),
            ),
            array(
                'route'         => '/votaciones/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los votaciones.',
                'examples'       => array(
                    '/votaciones/{id}',
                    '/votaciones/{id}/',
                ),
            ),
            array(
                'route'         => '/votaciones/params',
                'method'        => 'GET',
                'description'   => 'Lista los votaciones que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/votaciones/params/?votacion[nombre]=Ecuador',
                    '/votaciones/params/?votacion[descripcion]=Suramérica',
                    '/votaciones/params/?votacion[descripcion]=Votación-Suraméricano',
                    '/votaciones/params/?votacion[nombre]=República-Bolivariana-de-Venezuela&votacion[descripcion]=suramerica',
                    '/votaciones/params/?votacion[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/votaciones/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los votaciones iniciando en el Offset.',
                'examples'       => array(
                    '/votaciones/o1/',
                    '/votaciones/o10',
                ),
            ),
            array(
                'route'         => '/votaciones/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los votaciones iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/votaciones/l2/',
                    '/votaciones/l10',
                ),
            ),
            array(
                'route'         => '/votaciones/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los votaciones iniciando en offset hasta limit.',
                'examples'       => array(
                    '/votaciones/o1/l2/',
                    '/votaciones/o10/l10',
                ),
            ),
            array(
                'route'         => '/votaciones/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/votaciones/new/',
                    '/votaciones/new',
                ),
            ),
            array(
                'route'         => '/votaciones',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea votaciones. Puede recibir datos de varios votaciones.',
                'examples'       => array(
                    '/votaciones/',
                    '/votaciones',
                ),
            ),
            array(
                'route'         => '/votaciones/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de votacion para editar.',
                'examples'       => array(
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/votaciones/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de votacion.',
                'examples'       => array(
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/votaciones/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de votacion',
                'examples'       => array(
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/votaciones/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar votacion.',
                'examples'       => array(
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/votaciones/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra votacion.',
                'examples'       => array(
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/votaciones/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_votaciones', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Votaciones.
     *
     * @Route("/votaciones", name="get_votaciones")
     * @Route("/votaciones/", name="get_votaciones_")
     * @Template()
     * @Method("GET")
     */
    public function getVotacionesAction(Request $request)
    {
        $repository = $this->getVotacionRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Votaciones
     *
     * @Route("/votaciones/new", name="new_votaciones")
     * @Route("/votaciones/new/", name="new_votaciones_")
     * @Template()
     * @Method("GET")
     */
    public function newVotacionesAction(Request $request)
    {
        $type = new VotacionType($this->generateUrl('post_votaciones'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Votaciones.
     *
     * @Route("/votaciones", name="post_votaciones")
     * @Route("/votaciones/", name="post_votaciones_")
     * @Template()
     * @Method("POST")
     */
    public function postVotacionesAction(Request $request)
    {
        $votacion = new Votacion();
        $type = new VotacionType($this->generateUrl('post_votaciones'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Votación.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $datos = $request->get($type->getName(), false);
            $herencias = $votacion->getHerencias();
            if($datos['herencia'] && is_array($herencias) && array_key_exists($datos['herencia'],$herencias)){
                $votacionHerencia = $herencias[$datos['herencia']];
                $votacion = new $votacionHerencia();
                $type = new VotacionType($this->generateUrl('post_votaciones'), 'POST', array(), $votacionHerencia);
            }
            $form = $this->getForm($type, $votacion, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($votacion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Votacion.
     *
     * @Route("/votaciones/{slug}", name="get_votaciones_slug")
     * @Route("/votaciones/{slug}/", name="get_votaciones_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getVotacionAction(Request $request, $slug)
    {
        $votacion = null;
        switch($slug){
            case 'params':
                $datos = $request->get('votacion', false);
                if($datos){
                    $votacion = $this->getVotacionRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $votacion = $this->getVotacionRepository()->find($slug);
                break;
        }
        if (!$votacion) {
            $votacion = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Votación no enconrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($votacion, $request);
    }

    /**
     * Regresa el formulario para poder editar Votacion existente.
     *
     * @Route("/votaciones/{slug}/edit", name="edit_votaciones")
     * @Route("/votaciones/{slug}/edit/", name="edit_votaciones_")
     * @Template()
     * @Method("GET")
     */
    public function editVotacionAction(Request $request, $slug)
    {
        $votacion = $this->getVotacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Votación no enconrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new VotacionType($this->generateUrl('put_votaciones', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $votacion );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Votacion existente.
     *
     * @Route("/votaciones/{slug}", name="put_votaciones")
     * @Route("/votaciones/{slug}/", name="put_votaciones_")
     * @Template()
     * @Method("PUT")
     */
    public function putVotacionAction(Request $request, $slug)
    {
        $votacion = $this->getVotacionRepository()->find($slug);
        $type = new VotacionType($this->generateUrl('put_votaciones', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Votación.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $votacion, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($votacion, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Votacion existente.
     *
     * @Route("/votaciones/{slug}", name="patch_votaciones")
     * @Route("/votaciones/{slug}/", name="patch_votaciones_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchVotacionAction(Request $request, $slug)
    {
        $votacion = $this->getVotacionRepository()->find($slug);
        $type = new VotacionType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Votación.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $votacion){
            $repo = $this->getVotacionRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($votacion));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($votacion, $id) !== $dato){
                        $accessor->setValue($votacion, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($votacion));
                    $name = $name[count($name)-1];
                    $votacion = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $votacion;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Votaciones..
     *
     * @Route("/votaciones/{slug}/remove", name="remove_votaciones")
     * @Route("/votaciones/{slug}/remove/", name="remove_votaciones_")
     * @Template()
     * @Method("GET")
     */
    public function removeVotacionesAction(Request $request, $slug)
    {
        $votacion = $this->getVotacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Votación no enconrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($votacion){
            $form = $this->createDeleteForm($slug,'delete_votaciones');
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
     * Elimina Votaciones
     *
     * @Route("/votaciones/{slug}", name="delete_votaciones")
     * @Route("/votaciones/{slug}/", name="delete_votaciones_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteVotacionesAction(Request $request, $slug)
    {
        $votacion = $this->getVotacionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Votación no enconrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($votacion){
            $form = $this->createDeleteForm($slug,'delete_votaciones');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $votacion){
                $em = $this->getManager();
                $em->remove($votacion);
                $em->flush();
                $rta = $votacion;
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
