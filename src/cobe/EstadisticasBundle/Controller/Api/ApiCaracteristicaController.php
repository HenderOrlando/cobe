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
 * @package cobe\EstadisticasBundle\Controller
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
     * @Route("/caracteristicas/attributes", name="options_caracteristicas_validate")
     * @Route("/caracteristicas/attributes/", name="options_caracteristicas_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Caracteristica();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
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
            array(
                'route'         => '/caracteristicas',
                'method'        => 'GET',
                'description'   => 'Lista todos las caracteristicas.',
                'examples'       => array(
                    '/caracteristicas',
                    '/caracteristicas/',
                ),
            ),
            array(
                'route'         => '/caracteristicas/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos las caracteristicas.',
                'examples'       => array(
                    '/caracteristicas/{id}',
                    '/caracteristicas/{id}/',
                ),
            ),
            array(
                'route'         => '/caracteristicas/params',
                'method'        => 'GET',
                'description'   => 'Lista las caracetrísticas que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/caracteristicas/params/?caracteristica[nombre]=Ecuador',
                    '/caracteristicas/params/?caracteristica[descripcion]=Suramérica',
                    '/caracteristicas/params/?caracteristica[descripcion]=Caracetrística-Suraméricano',
                    '/caracteristicas/params/?caracteristica[nombre]=República-Bolivariana-de-Venezuela&caracteristica[descripcion]=suramerica',
                    '/caracteristicas/params/?caracteristica[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/caracteristicas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las caracetrísticas iniciando en el Offset.',
                'examples'       => array(
                    '/caracteristicas/o1/',
                    '/caracteristicas/o10',
                ),
            ),
            array(
                'route'         => '/caracteristicas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las caracetrísticas iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/caracteristicas/l2/',
                    '/caracteristicas/l10',
                ),
            ),
            array(
                'route'         => '/caracteristicas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las caracetrísticas iniciando en offset hasta limit.',
                'examples'       => array(
                    '/caracteristicas/o1/l2/',
                    '/caracteristicas/o10/l10',
                ),
            ),
            array(
                'route'         => '/caracteristicas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una caracetrística.',
                'examples'       => array(
                    '/caracteristicas/new/',
                    '/caracteristicas/new',
                ),
            ),
            array(
                'route'         => '/caracteristicas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea caracetrísticas. Puede recibir datos de varias caracetrísticas.',
                'examples'       => array(
                    '/caracteristicas/',
                    '/caracteristicas',
                ),
            ),
            array(
                'route'         => '/caracteristicas/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de caracteristica para editar.',
                'examples'       => array(
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/caracteristicas/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de caracteristica.',
                'examples'       => array(
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/caracteristicas/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de caracteristica',
                'examples'       => array(
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/caracteristicas/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar caracteristica.',
                'examples'       => array(
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/caracteristicas/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra caracteristica.',
                'examples'       => array(
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/caracteristicas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
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
                    'message'   => 'No se encuentran los datos para crear la Caracetrística.',
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
                        'message'   => 'Caracetrística no encontrada.',
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
                    'message'   => 'Caracetrística no encontrada.',
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
                    'message'   => 'No se encuentran los datos para crear la Caracetrística.',
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
     * @Route("/caracteristicas/{slug}", name="patch_caracteristicas")
     * @Route("/caracteristicas/{slug}/", name="patch_caracteristicas_")
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
                    'message'   => 'No se encuentran los datos para crear la Caracetrística.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $caracteristica){
            $repo = $this->getCaracteristicaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($caracteristica));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($caracteristica, $id) !== $dato){
                            $accessor->setValue($caracteristica, $id, $dato);
                            $isModify = true;
                        }
                    }elseif($metadata->hasAssociation($id)){
                        if($metadata->isCollectionValuedAssociation($id)){
                            $collection = $this->getColeccionObject($metadata, $datos, $type, $request, $id, true);
                            //$datos_ = $request->request->get($type->getName(), false);
                            //$dato = $datos[$id] = $datos_[$id];
                            $msgs = $this->validateOneAssociation($metadata, $collection, $id);
                            if(empty($msgs)){
                                $set = 'set'.ucfirst($id);
                                if(method_exists($caracteristica,$set)){
                                    //$collection = new ArrayCollection($collection);
                                    $caracteristica->$set($collection);
                                }
                                $isModify = true;
                            }
                        }else{
                            $dato = $repo->sanearDato($dato, 'guid');
                            $accessor = PropertyAccess::createPropertyAccessor();
                            $dato_ = $accessor->getValue($caracteristica, $id);
                            if($dato && (!$dato_ || (is_object($dato_) && method_exists($dato_,'getId') && $dato_->getId() !== $dato))){
                                $association = $this->getManager()->getRepository($metadata->getAssociationTargetClass($id))->find($dato);
                                if($association && $association->getId()){
                                    $accessor->setValue($caracteristica, $id, $association);
                                    $isModify = true;
                                }
                            }
                        }
                    }
                }
            }
            if($isModify){
                $caracteristica = $this->captureErrorFlush($em, $caracteristica, 'editar');
            }
            $rta = $caracteristica;
        }
        return $this->getJsonResponse($rta, $request);
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
                    'message'   => 'Caracetrística no encontrada.',
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
                    'message'   => 'Caracetrística no encontrada.',
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
                $caracteristica = $this->captureErrorFlush($em, $caracteristica, 'borrar');
                $rta = $caracteristica;
                if(!is_array($rta) && method_exists($rta, 'getId') && !$rta->getId()){
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
