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

use cobe\CurriculosBundle\Entity\Proyecto;
use cobe\CurriculosBundle\Form\ProyectoType;
use cobe\CurriculosBundle\Repository\ProyectoRepository;

/**
 * API Proyecto Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiProyectoController extends ApiController
{
    /**
     * Retorna el repositorio de Proyecto
     *
     * @return ProyectoRepository
     */
    public function getProyectoRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:Proyecto');
    }

    /**
     * Regresa opciones de API para Proyectos.
     *
     * @Route("/proyectos", name="options_proyectos")
     * @Route("/proyectos/", name="options_proyectos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsProyectosAction(Request $request)
    {
        $opciones = array(
            '/proyectos' => array(
                'route'         => '/proyectos',
                'method'        => 'GET',
                'description'   => 'Lista todos los proyectos.',
                'examples'       => array(
                    '/proyectos',
                    '/proyectos/',
                ),
            ),
            '/proyectos/params' => array(
                'route'         => '/proyectos/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/proyectos/params/?proyecto[nombre]=Ecuador',
                    '/proyectos/params/?proyecto[descripcion]=Suramérica',
                    '/proyectos/params/?proyecto[descripcion]=País-Suraméricano',
                    '/proyectos/params/?proyecto[nombre]=República-Bolivariana-de-Venezuela&proyecto[descripcion]=suramerica',
                    '/proyectos/params/?proyecto[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/proyectos/o{offset}/' => array(
                'route'         => '/proyectos/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/proyectos/o1/',
                    '/proyectos/o10',
                ),
            ),
            '/proyectos/l{limit}/' => array(
                'route'         => '/proyectos/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/proyectos/l2/',
                    '/proyectos/l10',
                ),
            ),
            '/proyectos/0{offset}/l{limit}' => array(
                'route'         => '/proyectos/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/proyectos/o1/l2/',
                    '/proyectos/o10/l10',
                ),
            ),
            '/proyectos/new' => array(
                'route'         => '/proyectos/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/proyectos/new/',
                    '/proyectos/new',
                ),
            ),
            '/proyectos' => array(
                'route'         => '/proyectos',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/proyectos/',
                    '/proyectos',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_proyectos', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Proyectos.
     *
     * @Route("/proyectos", name="get_proyectos")
     * @Route("/proyectos/", name="get_proyectos_")
     * @Template()
     * @Method("GET")
     */
    public function getProyectosAction(Request $request)
    {
        $repository = $this->getProyectoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Proyectos
     *
     * @Route("/proyectos/new", name="new_proyectos")
     * @Route("/proyectos/new/", name="new_proyectos_")
     * @Template()
     * @Method("GET")
     */
    public function newProyectosAction(Request $request)
    {
        $type = new ProyectoType($this->generateUrl('post_proyectos'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Proyectos.
     *
     * @Route("/proyectos", name="post_proyectos")
     * @Route("/proyectos/", name="post_proyectos_")
     * @Template()
     * @Method("POST")
     */
    public function postProyectosAction(Request $request)
    {
        $proyecto = new Proyecto();
        $type = new ProyectoType($this->generateUrl('post_proyectos'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $proyecto, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($proyecto, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Proyectos.
     *
     * @Route("/proyectos", name="patch_proyectos")
     * @Route("/proyectos/", name="patch_proyectos_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchProyectosAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Proyecto.
     *
     * @Route("/proyectos/{slug}", name="get_proyectos_slug")
     * @Route("/proyectos/{slug}/", name="get_proyectos_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getProyectoAction(Request $request, $slug)
    {
        $proyecto = null;
        switch($slug){
            case 'params':
                $datos = $request->get('proyecto', false);
                if($datos){
                    $proyecto = $this->getProyectoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $proyecto = $this->getProyectoRepository()->find($slug);
                break;
        }
        if (!$proyecto) {
            $proyecto = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($proyecto, $request);
    }

    /**
     * Regresa el formulario para poder editar Proyecto existente.
     *
     * @Route("/proyectos/{slug}/edit", name="edit_proyectos")
     * @Route("/proyectos/{slug}/edit/", name="edit_proyectos_")
     * @Template()
     * @Method("GET")
     */
    public function editProyectoAction(Request $request, $slug)
    {
        $proyecto = $this->getProyectoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new ProyectoType($this->generateUrl('put_proyectos', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $proyecto );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Proyecto existente.
     *
     * @Route("/proyectos/{slug}", name="put_proyectos")
     * @Route("/proyectos/{slug}/", name="put_proyectos_")
     * @Template()
     * @Method("PUT")
     */
    public function putProyectoAction(Request $request, $slug)
    {
        $proyecto = $this->getProyectoRepository()->find($slug);
        $type = new ProyectoType($this->generateUrl('put_proyectos', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $proyecto, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($proyecto, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Proyecto existente.
     *
     * @Route("/proyecto/{slug}", name="patch_proyecto")
     * @Route("/proyecto/{slug}/", name="patch_proyecto_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchProyectoAction(Request $request, $slug)
    {
        $proyecto = $this->getProyectoRepository()->find($slug);
        $type = new ProyectoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $proyecto){
            $repo = $this->getProyectoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($proyecto));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($proyecto, $id) !== $dato){
                        $accessor->setValue($proyecto, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($proyecto));
                    $name = $name[count($name)-1];
                    $proyecto = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $proyecto;
        }
        return $this->getJsonResponse($proyecto, $request);
    }

    /**
     * Regresa formulario para Eliminar Proyectos..
     *
     * @Route("/proyectos/{slug}/remove", name="remove_proyectos")
     * @Route("/proyectos/{slug}/remove/", name="remove_proyectos_")
     * @Template()
     * @Method("GET")
     */
    public function removeProyectosAction(Request $request, $slug)
    {
        $proyecto = $this->getProyectoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($proyecto){
            $form = $this->createDeleteForm($slug,'delete_proyectos');
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
     * Regresa formulario para Eliminar Proyecto.
     *
     * @Route("/proyecto/{slug}/remove", name="remove_proyecto")
     * @Route("/proyecto/{slug}/remove/", name="remove_proyecto_")
     * @Template()
     * @Method("GET")
     */
    public function removeProyectoAction(Request $request, $slug)
    {
        $proyecto = $this->getProyectoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($proyecto){
            $form = $this->createDeleteForm($slug,'delete_proyecto');
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
     * Elimina Proyectos
     *
     * @Route("/proyectos/{slug}", name="delete_proyectos")
     * @Route("/proyectos/{slug}/", name="delete_proyectos_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteProyectosAction(Request $request, $slug)
    {
        $proyecto = $this->getProyectoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($proyecto){
            $form = $this->createDeleteForm($slug,'delete_proyectos');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $proyecto){
                $em = $this->getManager();
                $em->remove($proyecto);
                $em->flush();
                $rta = $proyecto;
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
     * Elimina Proyecto
     *
     * @Route("/proyecto/{slug}", name="delete_proyecto")
     * @Route("/proyecto/{slug}/", name="delete_proyecto_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteProyectoAction(Request $request, $slug)
    {
        $proyecto = $this->getProyectoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($proyecto){
            $form = $this->createDeleteForm($slug,'delete_proyecto');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $proyecto){
                $em = $this->getManager();
                $em->remove($proyecto);
                $em->flush();
                $rta = $proyecto;
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
