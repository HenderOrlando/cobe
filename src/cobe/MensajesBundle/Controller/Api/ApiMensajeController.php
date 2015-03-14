<?php

namespace cobe\MensajesBundle\Controller\Api;

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

use cobe\MensajesBundle\Entity\Mensaje;
use cobe\MensajesBundle\Form\MensajeType;
use cobe\MensajesBundle\Repository\MensajeRepository;

/**
 * API Mensaje Controller.
 *
 * @package cobe\MensajesBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiMensajeController extends ApiController
{
    /**
     * Retorna el repositorio de Mensaje
     *
     * @return MensajeRepository
     */
    public function getMensajeRepository()
    {
        return $this->getManager()->getRepository('cobeMensajesBundle:Mensaje');
    }

    /**
     * Regresa opciones de API para Mensajes.
     *
     * @Route("/mensajes", name="options_mensajes")
     * @Route("/mensajes/", name="options_mensajes_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsMensajesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/mensajes',
                'method'        => 'GET',
                'description'   => 'Lista todos los mensajes.',
                'examples'       => array(
                    '/mensajes',
                    '/mensajes/',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los mensajes.',
                'examples'       => array(
                    '/mensajes/{id}',
                    '/mensajes/{id}/',
                ),
            ),
            array(
                'route'         => '/mensajes/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/mensajes/params/?mensaje[nombre]=Ecuador',
                    '/mensajes/params/?mensaje[descripcion]=Suramérica',
                    '/mensajes/params/?mensaje[descripcion]=País-Suraméricano',
                    '/mensajes/params/?mensaje[nombre]=República-Bolivariana-de-Venezuela&mensaje[descripcion]=suramerica',
                    '/mensajes/params/?mensaje[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/mensajes/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/mensajes/o1/',
                    '/mensajes/o10',
                ),
            ),
            array(
                'route'         => '/mensajes/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/mensajes/l2/',
                    '/mensajes/l10',
                ),
            ),
            array(
                'route'         => '/mensajes/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/mensajes/o1/l2/',
                    '/mensajes/o10/l10',
                ),
            ),
            array(
                'route'         => '/mensajes/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/mensajes/new/',
                    '/mensajes/new',
                ),
            ),
            array(
                'route'         => '/mensajes',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/mensajes/',
                    '/mensajes',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de mensaje para editar.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los etributos de mensaje.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de mensaje',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar mensaje.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra mensaje.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_mensajes', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Mensajes.
     *
     * @Route("/mensajes", name="get_mensajes")
     * @Route("/mensajes/", name="get_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function getMensajesAction(Request $request)
    {
        $repository = $this->getMensajeRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Mensajes
     *
     * @Route("/mensajes/new", name="new_mensajes")
     * @Route("/mensajes/new/", name="new_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function newMensajesAction(Request $request)
    {
        $type = new MensajeType($this->generateUrl('post_mensajes'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Mensajes.
     *
     * @Route("/mensajes", name="post_mensajes")
     * @Route("/mensajes/", name="post_mensajes_")
     * @Template()
     * @Method("POST")
     */
    public function postMensajesAction(Request $request)
    {
        $mensaje = new Mensaje();
        $type = new MensajeType($this->generateUrl('post_mensajes'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $mensaje, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($mensaje, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Mensaje.
     *
     * @Route("/mensajes/{slug}", name="get_mensajes_slug")
     * @Route("/mensajes/{slug}/", name="get_mensajes_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getMensajeAction(Request $request, $slug)
    {
        $mensaje = null;
        switch($slug){
            case 'params':
                $datos = $request->get('mensaje', false);
                if($datos){
                    $mensaje = $this->getMensajeRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $mensaje = $this->getMensajeRepository()->find($slug);
                break;
        }
        if (!$mensaje) {
            $mensaje = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($mensaje, $request);
    }

    /**
     * Regresa el formulario para poder editar Mensaje existente.
     *
     * @Route("/mensajes/{slug}/edit", name="edit_mensajes")
     * @Route("/mensajes/{slug}/edit/", name="edit_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function editMensajeAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new MensajeType($this->generateUrl('put_mensajes', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $mensaje );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Mensaje existente.
     *
     * @Route("/mensajes/{slug}", name="put_mensajes")
     * @Route("/mensajes/{slug}/", name="put_mensajes_")
     * @Template()
     * @Method("PUT")
     */
    public function putMensajeAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);
        $type = new MensajeType($this->generateUrl('put_mensajes', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $mensaje, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($mensaje, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Mensaje existente.
     *
     * @Route("/mensajes/{slug}", name="patch_mensajes")
     * @Route("/mensajes/{slug}/", name="patch_mensajes_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchMensajeAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);
        $type = new MensajeType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $mensaje){
            $repo = $this->getMensajeRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($mensaje));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($mensaje, $id) !== $dato){
                        $accessor->setValue($mensaje, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($mensaje));
                    $name = $name[count($name)-1];
                    $mensaje = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $mensaje;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Mensajes..
     *
     * @Route("/mensajes/{slug}/remove", name="remove_mensajes")
     * @Route("/mensajes/{slug}/remove/", name="remove_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function removeMensajesAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($mensaje){
            $form = $this->createDeleteForm($slug,'delete_mensajes');
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
     * Elimina Mensajes
     *
     * @Route("/mensajes/{slug}", name="delete_mensajes")
     * @Route("/mensajes/{slug}/", name="delete_mensajes_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteMensajesAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($mensaje){
            $form = $this->createDeleteForm($slug,'delete_mensajes');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $mensaje){
                $em = $this->getManager();
                $em->remove($mensaje);
                $em->flush();
                $rta = $mensaje;
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
