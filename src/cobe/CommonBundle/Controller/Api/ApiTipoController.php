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

use cobe\CommonBundle\Entity\Tipo;
use cobe\CommonBundle\Form\TipoType;
use cobe\CommonBundle\Repository\TipoRepository;

/**
 * API Tipo Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiTipoController extends ApiController
{
    /**
     * Retorna el repositorio de Tipo
     *
     * @return TipoRepository
     */
    public function getTipoRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Tipo');
    }

    /**
     * Regresa opciones de API para Tipos.
     *
     * @Route("/tipos", name="options_tipos")
     * @Route("/tipos/", name="options_tipos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsTiposAction(Request $request)
    {
        $opciones = array(
            '/tipos' => array(
                'route'         => '/tipos',
                'method'        => 'GET',
                'description'   => 'Lista todos los tipos.',
                'examples'       => array(
                    '/tipos',
                    '/tipos/',
                ),
            ),
            '/tipos/params' => array(
                'route'         => '/tipos/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/tipos/params/?tipo[nombre]=Ecuador',
                    '/tipos/params/?tipo[descripcion]=Suramérica',
                    '/tipos/params/?tipo[descripcion]=País-Suraméricano',
                    '/tipos/params/?tipo[nombre]=República-Bolivariana-de-Venezuela&tipo[descripcion]=suramerica',
                    '/tipos/params/?tipo[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/tipos/o{offset}/' => array(
                'route'         => '/tipos/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/tipos/o1/',
                    '/tipos/o10',
                ),
            ),
            '/tipos/l{limit}/' => array(
                'route'         => '/tipos/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/tipos/l2/',
                    '/tipos/l10',
                ),
            ),
            '/tipos/0{offset}/l{limit}' => array(
                'route'         => '/tipos/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/tipos/o1/l2/',
                    '/tipos/o10/l10',
                ),
            ),
            '/tipos/new' => array(
                'route'         => '/tipos/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/tipos/new/',
                    '/tipos/new',
                ),
            ),
            '/tipos' => array(
                'route'         => '/tipos',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/tipos/',
                    '/tipos',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_tipos', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Tipos.
     *
     * @Route("/tipos", name="get_tipos")
     * @Route("/tipos/", name="get_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function getTiposAction(Request $request)
    {
        $repository = $this->getTipoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Tipos
     *
     * @Route("/tipos/new", name="new_tipos")
     * @Route("/tipos/new/", name="new_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function newTiposAction(Request $request)
    {
        $type = new TipoType($this->generateUrl('post_tipos'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Tipos.
     *
     * @Route("/tipos", name="post_tipos")
     * @Route("/tipos/", name="post_tipos_")
     * @Template()
     * @Method("POST")
     */
    public function postTiposAction(Request $request)
    {
        $tipo = new Tipo();
        $type = new TipoType($this->generateUrl('post_tipos'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $tipo, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($tipo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Tipos.
     *
     * @Route("/tipos", name="patch_tipos")
     * @Route("/tipos/", name="patch_tipos_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchTiposAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Tipo.
     *
     * @Route("/tipos/{slug}", name="get_tipos_slug")
     * @Route("/tipos/{slug}/", name="get_tipos_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getTipoAction(Request $request, $slug)
    {
        $tipo = null;
        switch($slug){
            case 'params':
                $datos = $request->get('tipo', false);
                if($datos){
                    $tipo = $this->getTipoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $tipo = $this->getTipoRepository()->find($slug);
                break;
        }
        if (!$tipo) {
            $tipo = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($tipo, $request);
    }

    /**
     * Regresa el formulario para poder editar Tipo existente.
     *
     * @Route("/tipos/{slug}/edit", name="edit_tipos")
     * @Route("/tipos/{slug}/edit/", name="edit_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function editTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new TipoType($this->generateUrl('put_tipos', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $tipo );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Tipo existente.
     *
     * @Route("/tipos/{slug}", name="put_tipos")
     * @Route("/tipos/{slug}/", name="put_tipos_")
     * @Template()
     * @Method("PUT")
     */
    public function putTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);
        $type = new TipoType($this->generateUrl('put_tipos', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $tipo, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($tipo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Tipo existente.
     *
     * @Route("/tipo/{slug}", name="patch_tipo")
     * @Route("/tipo/{slug}/", name="patch_tipo_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);
        $type = new TipoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $tipo){
            $repo = $this->getTipoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($tipo));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($tipo, $id) !== $dato){
                        $accessor->setValue($tipo, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($tipo));
                    $name = $name[count($name)-1];
                    $tipo = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $tipo;
        }
        return $this->getJsonResponse($tipo, $request);
    }

    /**
     * Regresa formulario para Eliminar Tipos..
     *
     * @Route("/tipos/{slug}/remove", name="remove_tipos")
     * @Route("/tipos/{slug}/remove/", name="remove_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function removeTiposAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($tipo){
            $form = $this->createDeleteForm($slug,'delete_tipos');
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
     * Regresa formulario para Eliminar Tipo.
     *
     * @Route("/tipo/{slug}/remove", name="remove_tipo")
     * @Route("/tipo/{slug}/remove/", name="remove_tipo_")
     * @Template()
     * @Method("GET")
     */
    public function removeTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($tipo){
            $form = $this->createDeleteForm($slug,'delete_tipo');
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
     * Elimina Tipos
     *
     * @Route("/tipos/{slug}", name="delete_tipos")
     * @Route("/tipos/{slug}/", name="delete_tipos_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteTiposAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($tipo){
            $form = $this->createDeleteForm($slug,'delete_tipos');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $tipo){
                $em = $this->getManager();
                $em->remove($tipo);
                $em->flush();
                $rta = $tipo;
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
     * Elimina Tipo
     *
     * @Route("/tipo/{slug}", name="delete_tipo")
     * @Route("/tipo/{slug}/", name="delete_tipo_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($tipo){
            $form = $this->createDeleteForm($slug,'delete_tipo');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $tipo){
                $em = $this->getManager();
                $em->remove($tipo);
                $em->flush();
                $rta = $tipo;
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