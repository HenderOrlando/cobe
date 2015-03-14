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

use cobe\CurriculosBundle\Entity\Reconocimiento;
use cobe\CurriculosBundle\Form\ReconocimientoType;
use cobe\CurriculosBundle\Repository\ReconocimientoRepository;

/**
 * API Reconocimiento Controller.
 *
 * @package cobe\CurriculosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiReconocimientoController extends ApiController
{
    /**
     * Retorna el repositorio de Reconocimiento
     *
     * @return ReconocimientoRepository
     */
    public function getReconocimientoRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:Reconocimiento');
    }

    /**
     * Regresa opciones de API para Reconocimientos.
     *
     * @Route("/reconocimientos", name="options_reconocimientos")
     * @Route("/reconocimientos/", name="options_reconocimientos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsReconocimientosAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/reconocimientos',
                'method'        => 'GET',
                'description'   => 'Lista todos los reconocimientos.',
                'examples'       => array(
                    '/reconocimientos',
                    '/reconocimientos/',
                ),
            ),
            array(
                'route'         => '/reconocimientos/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los reconocimientos.',
                'examples'       => array(
                    '/reconocimientos/{id}',
                    '/reconocimientos/{id}/',
                ),
            ),
            array(
                'route'         => '/reconocimientos/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/reconocimientos/params/?reconocimiento[nombre]=Ecuador',
                    '/reconocimientos/params/?reconocimiento[descripcion]=Suramérica',
                    '/reconocimientos/params/?reconocimiento[descripcion]=País-Suraméricano',
                    '/reconocimientos/params/?reconocimiento[nombre]=República-Bolivariana-de-Venezuela&reconocimiento[descripcion]=suramerica',
                    '/reconocimientos/params/?reconocimiento[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/reconocimientos/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/reconocimientos/o1/',
                    '/reconocimientos/o10',
                ),
            ),
            array(
                'route'         => '/reconocimientos/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/reconocimientos/l2/',
                    '/reconocimientos/l10',
                ),
            ),
            array(
                'route'         => '/reconocimientos/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/reconocimientos/o1/l2/',
                    '/reconocimientos/o10/l10',
                ),
            ),
            array(
                'route'         => '/reconocimientos/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/reconocimientos/new/',
                    '/reconocimientos/new',
                ),
            ),
            array(
                'route'         => '/reconocimientos',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/reconocimientos/',
                    '/reconocimientos',
                ),
            ),
            array(
                'route'         => '/reconocimientos/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de reconocimiento para editar.',
                'examples'       => array(
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/reconocimientos/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los etributos de reconocimiento.',
                'examples'       => array(
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/reconocimientos/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de reconocimiento',
                'examples'       => array(
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/reconocimientos/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar reconocimiento.',
                'examples'       => array(
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/reconocimientos/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra reconocimiento.',
                'examples'       => array(
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/reconocimientos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_reconocimientos', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Reconocimientos.
     *
     * @Route("/reconocimientos", name="get_reconocimientos")
     * @Route("/reconocimientos/", name="get_reconocimientos_")
     * @Template()
     * @Method("GET")
     */
    public function getReconocimientosAction(Request $request)
    {
        $repository = $this->getReconocimientoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Reconocimientos
     *
     * @Route("/reconocimientos/new", name="new_reconocimientos")
     * @Route("/reconocimientos/new/", name="new_reconocimientos_")
     * @Template()
     * @Method("GET")
     */
    public function newReconocimientosAction(Request $request)
    {
        $type = new ReconocimientoType($this->generateUrl('post_reconocimientos'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Reconocimientos.
     *
     * @Route("/reconocimientos", name="post_reconocimientos")
     * @Route("/reconocimientos/", name="post_reconocimientos_")
     * @Template()
     * @Method("POST")
     */
    public function postReconocimientosAction(Request $request)
    {
        $reconocimiento = new Reconocimiento();
        $type = new ReconocimientoType($this->generateUrl('post_reconocimientos'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $reconocimiento, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($reconocimiento, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Reconocimiento.
     *
     * @Route("/reconocimientos/{slug}", name="get_reconocimientos_slug")
     * @Route("/reconocimientos/{slug}/", name="get_reconocimientos_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getReconocimientoAction(Request $request, $slug)
    {
        $reconocimiento = null;
        switch($slug){
            case 'params':
                $datos = $request->get('reconocimiento', false);
                if($datos){
                    $reconocimiento = $this->getReconocimientoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $reconocimiento = $this->getReconocimientoRepository()->find($slug);
                break;
        }
        if (!$reconocimiento) {
            $reconocimiento = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($reconocimiento, $request);
    }

    /**
     * Regresa el formulario para poder editar Reconocimiento existente.
     *
     * @Route("/reconocimientos/{slug}/edit", name="edit_reconocimientos")
     * @Route("/reconocimientos/{slug}/edit/", name="edit_reconocimientos_")
     * @Template()
     * @Method("GET")
     */
    public function editReconocimientoAction(Request $request, $slug)
    {
        $reconocimiento = $this->getReconocimientoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new ReconocimientoType($this->generateUrl('put_reconocimientos', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $reconocimiento );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Reconocimiento existente.
     *
     * @Route("/reconocimientos/{slug}", name="put_reconocimientos")
     * @Route("/reconocimientos/{slug}/", name="put_reconocimientos_")
     * @Template()
     * @Method("PUT")
     */
    public function putReconocimientoAction(Request $request, $slug)
    {
        $reconocimiento = $this->getReconocimientoRepository()->find($slug);
        $type = new ReconocimientoType($this->generateUrl('put_reconocimientos', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $reconocimiento, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($reconocimiento, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Reconocimiento existente.
     *
     * @Route("/reconocimientos/{slug}", name="patch_reconocimientos")
     * @Route("/reconocimientos/{slug}/", name="patch_reconocimientos_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchReconocimientoAction(Request $request, $slug)
    {
        $reconocimiento = $this->getReconocimientoRepository()->find($slug);
        $type = new ReconocimientoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $reconocimiento){
            $repo = $this->getReconocimientoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($reconocimiento));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($reconocimiento, $id) !== $dato){
                        $accessor->setValue($reconocimiento, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($reconocimiento));
                    $name = $name[count($name)-1];
                    $reconocimiento = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $reconocimiento;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Reconocimientos..
     *
     * @Route("/reconocimientos/{slug}/remove", name="remove_reconocimientos")
     * @Route("/reconocimientos/{slug}/remove/", name="remove_reconocimientos_")
     * @Template()
     * @Method("GET")
     */
    public function removeReconocimientosAction(Request $request, $slug)
    {
        $reconocimiento = $this->getReconocimientoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($reconocimiento){
            $form = $this->createDeleteForm($slug,'delete_reconocimientos');
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
     * Elimina Reconocimientos
     *
     * @Route("/reconocimientos/{slug}", name="delete_reconocimientos")
     * @Route("/reconocimientos/{slug}/", name="delete_reconocimientos_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteReconocimientosAction(Request $request, $slug)
    {
        $reconocimiento = $this->getReconocimientoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($reconocimiento){
            $form = $this->createDeleteForm($slug,'delete_reconocimientos');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $reconocimiento){
                $em = $this->getManager();
                $em->remove($reconocimiento);
                $em->flush();
                $rta = $reconocimiento;
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
