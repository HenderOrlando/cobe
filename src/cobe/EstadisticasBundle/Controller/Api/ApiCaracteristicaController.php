<?php

namespace cobe\EstadisticasBundle\Controller\Api;

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

use cobe\EstadisticasBundle\Entity\Caracteristica;
use cobe\EstadisticasBundle\Form\CaracteristicaType;
use cobe\EstadisticasBundle\Repository\CaracteristicaRepository;

/**
 * API Caracteristica Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiCaracteristicaController extends ApiController
{
    /**
     * Retorna el repositorio de Caracteristica
     *
     * @return CaracteristicaRepository
     */
    public function getCaracteristicaRepository()
    {
        return $this->getManager()->getRepository('cobeEstadisticasBundle:Caracteristica');
    }

    /**
     * Regresa opciones de API para Caracteristicas.
     *
     * @Route("/caracteristicas", name="options_caracteristicas")
     * @Route("/caracteristicas/", name="options_caracteristicas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsCaracteristicasAction(Request $request)
    {
        $opciones = array(
            '/caracteristicas' => array(
                'route'         => '/caracteristicas',
                'method'        => 'GET',
                'description'   => 'Lista todos los caracteristicas.',
                'examples'       => array(
                    '/caracteristicas',
                    '/caracteristicas/',
                ),
            ),
            '/caracteristicas/params' => array(
                'route'         => '/caracteristicas/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/caracteristicas/params/?caracteristica[nombre]=Ecuador',
                    '/caracteristicas/params/?caracteristica[descripcion]=Suramérica',
                    '/caracteristicas/params/?caracteristica[descripcion]=País-Suraméricano',
                    '/caracteristicas/params/?caracteristica[nombre]=República-Bolivariana-de-Venezuela&caracteristica[descripcion]=suramerica',
                    '/caracteristicas/params/?caracteristica[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/caracteristicas/o{offset}/' => array(
                'route'         => '/caracteristicas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/caracteristicas/o1/',
                    '/caracteristicas/o10',
                ),
            ),
            '/caracteristicas/l{limit}/' => array(
                'route'         => '/caracteristicas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/caracteristicas/l2/',
                    '/caracteristicas/l10',
                ),
            ),
            '/caracteristicas/0{offset}/l{limit}' => array(
                'route'         => '/caracteristicas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/caracteristicas/o1/l2/',
                    '/caracteristicas/o10/l10',
                ),
            ),
            '/caracteristicas/new' => array(
                'route'         => '/caracteristicas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/caracteristicas/new/',
                    '/caracteristicas/new',
                ),
            ),
            '/caracteristicas' => array(
                'route'         => '/caracteristicas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/caracteristicas/',
                    '/caracteristicas',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_caracteristicas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Caracteristicas.
     *
     * @Route("/caracteristicas", name="get_caracteristicas")
     * @Route("/caracteristicas/", name="get_caracteristicas_")
     * @Template()
     * @Method("GET")
     */
    public function getCaracteristicasAction(Request $request)
    {
        $repository = $this->getCaracteristicaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Caracteristicas
     *
     * @Route("/caracteristicas/new", name="new_caracteristicas")
     * @Route("/caracteristicas/new/", name="new_caracteristicas_")
     * @Template()
     * @Method("GET")
     */
    public function newCaracteristicasAction(Request $request)
    {
        $type = new CaracteristicaType($this->generateUrl('post_caracteristicas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Caracteristicas.
     *
     * @Route("/caracteristicas", name="post_caracteristicas")
     * @Route("/caracteristicas/", name="post_caracteristicas_")
     * @Template()
     * @Method("POST")
     */
    public function postCaracteristicasAction(Request $request)
    {
        $caracteristica = new Caracteristica();
        $type = new CaracteristicaType($this->generateUrl('post_caracteristicas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $caracteristica, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($caracteristica, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Caracteristicas.
     *
     * @Route("/caracteristicas", name="patch_caracteristicas")
     * @Route("/caracteristicas/", name="patch_caracteristicas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCaracteristicasAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Caracteristica.
     *
     * @Route("/caracteristicas/{slug}", name="get_caracteristicas_slug")
     * @Route("/caracteristicas/{slug}/", name="get_caracteristicas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getCaracteristicaAction(Request $request, $slug)
    {
        $caracteristica = null;
        switch($slug){
            case 'params':
                $datos = $request->get('caracteristica', false);
                if($datos){
                    $caracteristica = $this->getCaracteristicaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $caracteristica = $this->getCaracteristicaRepository()->find($slug);
                break;
        }
        if (!$caracteristica) {
            $caracteristica = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($caracteristica, $request);
    }

    /**
     * Regresa el formulario para poder editar Caracteristica existente.
     *
     * @Route("/caracteristicas/{slug}/edit", name="edit_caracteristicas")
     * @Route("/caracteristicas/{slug}/edit/", name="edit_caracteristicas_")
     * @Template()
     * @Method("GET")
     */
    public function editCaracteristicaAction(Request $request, $slug)
    {
        $caracteristica = $this->getCaracteristicaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new CaracteristicaType($this->generateUrl('put_caracteristicas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $caracteristica );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Caracteristica existente.
     *
     * @Route("/caracteristicas/{slug}", name="put_caracteristicas")
     * @Route("/caracteristicas/{slug}/", name="put_caracteristicas_")
     * @Template()
     * @Method("PUT")
     */
    public function putCaracteristicaAction(Request $request, $slug)
    {
        $caracteristica = $this->getCaracteristicaRepository()->find($slug);
        $type = new CaracteristicaType($this->generateUrl('put_caracteristicas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $caracteristica, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($caracteristica, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Caracteristica existente.
     *
     * @Route("/caracteristica/{slug}", name="patch_caracteristica")
     * @Route("/caracteristica/{slug}/", name="patch_caracteristica_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCaracteristicaAction(Request $request, $slug)
    {
        $caracteristica = $this->getCaracteristicaRepository()->find($slug);
        $type = new CaracteristicaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $caracteristica){
            $repo = $this->getCaracteristicaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($caracteristica));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($caracteristica, $id) !== $dato){
                        $accessor->setValue($caracteristica, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($caracteristica));
                    $name = $name[count($name)-1];
                    $caracteristica = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $caracteristica;
        }
        return $this->getJsonResponse($caracteristica, $request);
    }

    /**
     * Regresa formulario para Eliminar Caracteristicas..
     *
     * @Route("/caracteristicas/{slug}/remove", name="remove_caracteristicas")
     * @Route("/caracteristicas/{slug}/remove/", name="remove_caracteristicas_")
     * @Template()
     * @Method("GET")
     */
    public function removeCaracteristicasAction(Request $request, $slug)
    {
        $caracteristica = $this->getCaracteristicaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($caracteristica){
            $form = $this->createDeleteForm($slug,'delete_caracteristicas');
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
     * Regresa formulario para Eliminar Caracteristica.
     *
     * @Route("/caracteristica/{slug}/remove", name="remove_caracteristica")
     * @Route("/caracteristica/{slug}/remove/", name="remove_caracteristica_")
     * @Template()
     * @Method("GET")
     */
    public function removeCaracteristicaAction(Request $request, $slug)
    {
        $caracteristica = $this->getCaracteristicaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($caracteristica){
            $form = $this->createDeleteForm($slug,'delete_caracteristica');
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
     * Elimina Caracteristicas
     *
     * @Route("/caracteristicas/{slug}", name="delete_caracteristicas")
     * @Route("/caracteristicas/{slug}/", name="delete_caracteristicas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCaracteristicasAction(Request $request, $slug)
    {
        $caracteristica = $this->getCaracteristicaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($caracteristica){
            $form = $this->createDeleteForm($slug,'delete_caracteristicas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $caracteristica){
                $em = $this->getManager();
                $em->remove($caracteristica);
                $em->flush();
                $rta = $caracteristica;
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
     * Elimina Caracteristica
     *
     * @Route("/caracteristica/{slug}", name="delete_caracteristica")
     * @Route("/caracteristica/{slug}/", name="delete_caracteristica_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCaracteristicaAction(Request $request, $slug)
    {
        $caracteristica = $this->getCaracteristicaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($caracteristica){
            $form = $this->createDeleteForm($slug,'delete_caracteristica');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $caracteristica){
                $em = $this->getManager();
                $em->remove($caracteristica);
                $em->flush();
                $rta = $caracteristica;
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
