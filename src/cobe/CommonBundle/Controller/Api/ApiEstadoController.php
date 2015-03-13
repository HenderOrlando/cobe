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

use cobe\CommonBundle\Entity\Estado;
use cobe\CommonBundle\Form\EstadoType;
use cobe\CommonBundle\Repository\EstadoRepository;

/**
 * API Estado Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEstadoController extends ApiController
{
    /**
     * Retorna el repositorio de Estado
     *
     * @return EstadoRepository
     */
    public function getEstadoRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Estado');
    }

    /**
     * Regresa opciones de API para Estados.
     *
     * @Route("/estados", name="options_estados")
     * @Route("/estados/", name="options_estados_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEstadosAction(Request $request)
    {
        $opciones = array(
            '/estados' => array(
                'route'         => '/estados',
                'method'        => 'GET',
                'description'   => 'Lista todos los estados.',
                'examples'       => array(
                    '/estados',
                    '/estados/',
                ),
            ),
            '/estados/params' => array(
                'route'         => '/estados/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/estados/params/?estado[nombre]=Ecuador',
                    '/estados/params/?estado[descripcion]=Suramérica',
                    '/estados/params/?estado[descripcion]=País-Suraméricano',
                    '/estados/params/?estado[nombre]=República-Bolivariana-de-Venezuela&estado[descripcion]=suramerica',
                    '/estados/params/?estado[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/estados/o{offset}/' => array(
                'route'         => '/estados/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/estados/o1/',
                    '/estados/o10',
                ),
            ),
            '/estados/l{limit}/' => array(
                'route'         => '/estados/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/estados/l2/',
                    '/estados/l10',
                ),
            ),
            '/estados/0{offset}/l{limit}' => array(
                'route'         => '/estados/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/estados/o1/l2/',
                    '/estados/o10/l10',
                ),
            ),
            '/estados/new' => array(
                'route'         => '/estados/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/estados/new/',
                    '/estados/new',
                ),
            ),
            '/estados' => array(
                'route'         => '/estados',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/estados/',
                    '/estados',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_estados', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Estados.
     *
     * @Route("/estados", name="get_estados")
     * @Route("/estados/", name="get_estados_")
     * @Template()
     * @Method("GET")
     */
    public function getEstadosAction(Request $request)
    {
        $repository = $this->getEstadoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Estados
     *
     * @Route("/estados/new", name="new_estados")
     * @Route("/estados/new/", name="new_estados_")
     * @Template()
     * @Method("GET")
     */
    public function newEstadosAction(Request $request)
    {
        $type = new EstadoType($this->generateUrl('post_estados'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Estados.
     *
     * @Route("/estados", name="post_estados")
     * @Route("/estados/", name="post_estados_")
     * @Template()
     * @Method("POST")
     */
    public function postEstadosAction(Request $request)
    {
        $estado = new Estado();
        $type = new EstadoType($this->generateUrl('post_estados'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estado, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estado, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estados.
     *
     * @Route("/estados", name="patch_estados")
     * @Route("/estados/", name="patch_estados_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstadosAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Estado.
     *
     * @Route("/estados/{slug}", name="get_estados_slug")
     * @Route("/estados/{slug}/", name="get_estados_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEstadoAction(Request $request, $slug)
    {
        $estado = null;
        switch($slug){
            case 'params':
                $datos = $request->get('estado', false);
                if($datos){
                    $estado = $this->getEstadoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $estado = $this->getEstadoRepository()->find($slug);
                break;
        }
        if (!$estado) {
            $estado = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($estado, $request);
    }

    /**
     * Regresa el formulario para poder editar Estado existente.
     *
     * @Route("/estados/{slug}/edit", name="edit_estados")
     * @Route("/estados/{slug}/edit/", name="edit_estados_")
     * @Template()
     * @Method("GET")
     */
    public function editEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EstadoType($this->generateUrl('put_estados', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $estado );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Estado existente.
     *
     * @Route("/estados/{slug}", name="put_estados")
     * @Route("/estados/{slug}/", name="put_estados_")
     * @Template()
     * @Method("PUT")
     */
    public function putEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);
        $type = new EstadoType($this->generateUrl('put_estados', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estado, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estado, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estado existente.
     *
     * @Route("/estado/{slug}", name="patch_estado")
     * @Route("/estado/{slug}/", name="patch_estado_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);
        $type = new EstadoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $estado){
            $repo = $this->getEstadoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($estado));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($estado, $id) !== $dato){
                        $accessor->setValue($estado, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($estado));
                    $name = $name[count($name)-1];
                    $estado = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $estado;
        }
        return $this->getJsonResponse($estado, $request);
    }

    /**
     * Regresa formulario para Eliminar Estados..
     *
     * @Route("/estados/{slug}/remove", name="remove_estados")
     * @Route("/estados/{slug}/remove/", name="remove_estados_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstadosAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estado){
            $form = $this->createDeleteForm($slug,'delete_estados');
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
     * Regresa formulario para Eliminar Estado.
     *
     * @Route("/estado/{slug}/remove", name="remove_estado")
     * @Route("/estado/{slug}/remove/", name="remove_estado_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estado){
            $form = $this->createDeleteForm($slug,'delete_estado');
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
     * Elimina Estados
     *
     * @Route("/estados/{slug}", name="delete_estados")
     * @Route("/estados/{slug}/", name="delete_estados_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstadosAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estado){
            $form = $this->createDeleteForm($slug,'delete_estados');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estado){
                $em = $this->getManager();
                $em->remove($estado);
                $em->flush();
                $rta = $estado;
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

    /**
     * Elimina Estado
     *
     * @Route("/estado/{slug}", name="delete_estado")
     * @Route("/estado/{slug}/", name="delete_estado_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estado){
            $form = $this->createDeleteForm($slug,'delete_estado');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estado){
                $em = $this->getManager();
                $em->remove($estado);
                $em->flush();
                $rta = $estado;
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
