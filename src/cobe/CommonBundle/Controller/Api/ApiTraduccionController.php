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
     * @Route("/traducciones", name="options_traducciones")
     * @Route("/traducciones/", name="options_traducciones_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsTraduccionesAction(Request $request)
    {
        $opciones = array(
            '/traducciones' => array(
                'route'         => '/traducciones',
                'method'        => 'GET',
                'description'   => 'Lista todos los traducciones.',
                'examples'       => array(
                    '/traducciones',
                    '/traducciones/',
                ),
            ),
            '/traducciones/params' => array(
                'route'         => '/traducciones/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/traducciones/params/?traduccion[nombre]=Ecuador',
                    '/traducciones/params/?traduccion[descripcion]=Suramérica',
                    '/traducciones/params/?traduccion[descripcion]=País-Suraméricano',
                    '/traducciones/params/?traduccion[nombre]=República-Bolivariana-de-Venezuela&traduccion[descripcion]=suramerica',
                    '/traducciones/params/?traduccion[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/traducciones/o{offset}/' => array(
                'route'         => '/traducciones/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/traducciones/o1/',
                    '/traducciones/o10',
                ),
            ),
            '/traducciones/l{limit}/' => array(
                'route'         => '/traducciones/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/traducciones/l2/',
                    '/traducciones/l10',
                ),
            ),
            '/traducciones/0{offset}/l{limit}' => array(
                'route'         => '/traducciones/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/traducciones/o1/l2/',
                    '/traducciones/o10/l10',
                ),
            ),
            '/traducciones/new' => array(
                'route'         => '/traducciones/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/traducciones/new/',
                    '/traducciones/new',
                ),
            ),
            '/traducciones' => array(
                'route'         => '/traducciones',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/traducciones/',
                    '/traducciones',
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
                    'message'   => 'No se encuentran los datos para crear el País.',
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
     * Valida los datos y modifica atributos de Traducciones.
     *
     * @Route("/traducciones", name="patch_traducciones")
     * @Route("/traducciones/", name="patch_traducciones_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchTraduccionesAction()
    {
        return array(
            // ...
        );
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
                        'message'   => 'País no encontrado.',
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
                    'message'   => 'País no encontrado.',
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
                    'message'   => 'No se encuentran los datos para crear el País.',
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
     * @Route("/traduccion/{slug}", name="patch_traduccion")
     * @Route("/traduccion/{slug}/", name="patch_traduccion_")
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
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $traduccion){
            $repo = $this->getTraduccionRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($traduccion));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($traduccion, $id) !== $dato){
                        $accessor->setValue($traduccion, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($traduccion));
                    $name = $name[count($name)-1];
                    $traduccion = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $traduccion;
        }
        return $this->getJsonResponse($traduccion, $request);
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
                    'message'   => 'País no encontrado.',
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
     * Regresa formulario para Eliminar Traduccion.
     *
     * @Route("/traduccion/{slug}/remove", name="remove_traduccion")
     * @Route("/traduccion/{slug}/remove/", name="remove_traduccion_")
     * @Template()
     * @Method("GET")
     */
    public function removeTraduccionAction(Request $request, $slug)
    {
        $traduccion = $this->getTraduccionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($traduccion){
            $form = $this->createDeleteForm($slug,'delete_traduccion');
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
                    'message'   => 'País no encontrado.',
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
                $em->flush();
                $rta = $traduccion;
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
     * Elimina Traduccion
     *
     * @Route("/traduccion/{slug}", name="delete_traduccion")
     * @Route("/traduccion/{slug}/", name="delete_traduccion_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteTraduccionAction(Request $request, $slug)
    {
        $traduccion = $this->getTraduccionRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($traduccion){
            $form = $this->createDeleteForm($slug,'delete_traduccion');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $traduccion){
                $em = $this->getManager();
                $em->remove($traduccion);
                $em->flush();
                $rta = $traduccion;
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
