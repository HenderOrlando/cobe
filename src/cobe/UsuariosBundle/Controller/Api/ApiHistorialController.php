<?php

namespace cobe\UsuariosBundle\Controller\Api;

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

use cobe\UsuariosBundle\Entity\Historial;
use cobe\UsuariosBundle\Form\HistorialType;
use cobe\UsuariosBundle\Repository\HistorialRepository;

/**
 * API Historial Controller.
 *
 * @package cobe\UsuariosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiHistorialController extends ApiController
{
    /**
     * Retorna el repositorio de Historial
     *
     * @return HistorialRepository
     */
    public function getHistorialRepository()
    {
        return $this->getManager()->getRepository('cobeUsuariosBundle:Historial');
    }

    /**
     * Regresa opciones de API para Historiales.
     *
     * @Route("/historiales", name="options_historiales")
     * @Route("/historiales/", name="options_historiales_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsHistorialesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/historiales',
                'method'        => 'GET',
                'description'   => 'Lista todos los historiales.',
                'examples'       => array(
                    '/historiales',
                    '/historiales/',
                ),
            ),
            array(
                'route'         => '/historiales/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los historiales.',
                'examples'       => array(
                    '/historiales/{id}',
                    '/historiales/{id}/',
                ),
            ),
            array(
                'route'         => '/historiales/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/historiales/params/?historial[nombre]=Ecuador',
                    '/historiales/params/?historial[descripcion]=Suramérica',
                    '/historiales/params/?historial[descripcion]=País-Suraméricano',
                    '/historiales/params/?historial[nombre]=República-Bolivariana-de-Venezuela&historial[descripcion]=suramerica',
                    '/historiales/params/?historial[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/historiales/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/historiales/o1/',
                    '/historiales/o10',
                ),
            ),
            array(
                'route'         => '/historiales/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/historiales/l2/',
                    '/historiales/l10',
                ),
            ),
            array(
                'route'         => '/historiales/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/historiales/o1/l2/',
                    '/historiales/o10/l10',
                ),
            ),
            array(
                'route'         => '/historiales/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/historiales/new/',
                    '/historiales/new',
                ),
            ),
            array(
                'route'         => '/historiales',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/historiales/',
                    '/historiales',
                ),
            ),
            array(
                'route'         => '/historiales/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de historial para editar.',
                'examples'       => array(
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/historiales/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los etributos de historial.',
                'examples'       => array(
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/historiales/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de historial',
                'examples'       => array(
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/historiales/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar historial.',
                'examples'       => array(
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/historiales/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra historial.',
                'examples'       => array(
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/historiales/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_historiales', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Historiales.
     *
     * @Route("/historiales", name="get_historiales")
     * @Route("/historiales/", name="get_historiales_")
     * @Template()
     * @Method("GET")
     */
    public function getHistorialesAction(Request $request)
    {
        $repository = $this->getHistorialRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Historiales
     *
     * @Route("/historiales/new", name="new_historiales")
     * @Route("/historiales/new/", name="new_historiales_")
     * @Template()
     * @Method("GET")
     */
    public function newHistorialesAction(Request $request)
    {
        $type = new HistorialType($this->generateUrl('post_historiales'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Historiales.
     *
     * @Route("/historiales", name="post_historiales")
     * @Route("/historiales/", name="post_historiales_")
     * @Template()
     * @Method("POST")
     */
    public function postHistorialesAction(Request $request)
    {
        $historial = new Historial();
        $type = new HistorialType($this->generateUrl('post_historiales'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $historial, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($historial, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Historial.
     *
     * @Route("/historiales/{slug}", name="get_historiales_slug")
     * @Route("/historiales/{slug}/", name="get_historiales_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getHistorialAction(Request $request, $slug)
    {
        $historial = null;
        switch($slug){
            case 'params':
                $datos = $request->get('historial', false);
                if($datos){
                    $historial = $this->getHistorialRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $historial = $this->getHistorialRepository()->find($slug);
                break;
        }
        if (!$historial) {
            $historial = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($historial, $request);
    }

    /**
     * Regresa el formulario para poder editar Historial existente.
     *
     * @Route("/historiales/{slug}/edit", name="edit_historiales")
     * @Route("/historiales/{slug}/edit/", name="edit_historiales_")
     * @Template()
     * @Method("GET")
     */
    public function editHistorialAction(Request $request, $slug)
    {
        $historial = $this->getHistorialRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new HistorialType($this->generateUrl('put_historiales', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $historial );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Historial existente.
     *
     * @Route("/historiales/{slug}", name="put_historiales")
     * @Route("/historiales/{slug}/", name="put_historiales_")
     * @Template()
     * @Method("PUT")
     */
    public function putHistorialAction(Request $request, $slug)
    {
        $historial = $this->getHistorialRepository()->find($slug);
        $type = new HistorialType($this->generateUrl('put_historiales', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $historial, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($historial, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Historial existente.
     *
     * @Route("/historiales/{slug}", name="patch_historiales")
     * @Route("/historiales/{slug}/", name="patch_historiales_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchHistorialAction(Request $request, $slug)
    {
        $historial = $this->getHistorialRepository()->find($slug);
        $type = new HistorialType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $historial){
            $repo = $this->getHistorialRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($historial));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($historial, $id) !== $dato){
                        $accessor->setValue($historial, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($historial));
                    $name = $name[count($name)-1];
                    $historial = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $historial;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Historiales..
     *
     * @Route("/historiales/{slug}/remove", name="remove_historiales")
     * @Route("/historiales/{slug}/remove/", name="remove_historiales_")
     * @Template()
     * @Method("GET")
     */
    public function removeHistorialesAction(Request $request, $slug)
    {
        $historial = $this->getHistorialRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($historial){
            $form = $this->createDeleteForm($slug,'delete_historiales');
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
     * Elimina Historiales
     *
     * @Route("/historiales/{slug}", name="delete_historiales")
     * @Route("/historiales/{slug}/", name="delete_historiales_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteHistorialesAction(Request $request, $slug)
    {
        $historial = $this->getHistorialRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($historial){
            $form = $this->createDeleteForm($slug,'delete_historiales');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $historial){
                $em = $this->getManager();
                $em->remove($historial);
                $em->flush();
                $rta = $historial;
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
