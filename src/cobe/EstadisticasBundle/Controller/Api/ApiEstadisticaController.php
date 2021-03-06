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

use cobe\EstadisticasBundle\Entity\Estadistica;
use cobe\EstadisticasBundle\Form\EstadisticaType;
use cobe\EstadisticasBundle\Repository\EstadisticaRepository;

/**
 * API Estadistica Controller.
 *
 * @package cobe\EstadisticasBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEstadisticaController extends ApiController
{
    /**
     * Retorna el repositorio de Estadistica
     *
     * @return EstadisticaRepository
     */
    public function getEstadisticaRepository()
    {
        return $this->getManager()->getRepository('cobeEstadisticasBundle:Estadistica');
    }

    /**
     * Regresa opciones de API para Estadisticas.
     *
     * @Route("/estadisticas/attributes", name="options_estadisticas_validate")
     * @Route("/estadisticas/attributes/", name="options_estadisticas_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Estadistica();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Estadisticas.
     *
     * @Route("/estadisticas", name="options_estadisticas")
     * @Route("/estadisticas/", name="options_estadisticas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEstadisticasAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/estadisticas',
                'method'        => 'GET',
                'description'   => 'Lista todas las estadisticas.',
                'examples'       => array(
                    '/estadisticas',
                    '/estadisticas/',
                ),
            ),
            array(
                'route'         => '/estadisticas/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todas las estadisticas.',
                'examples'       => array(
                    '/estadisticas/{id}',
                    '/estadisticas/{id}/',
                ),
            ),
            array(
                'route'         => '/estadisticas/params',
                'method'        => 'GET',
                'description'   => 'Lista las estadísticas que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/estadisticas/params/?estadistica[nombre]=Ecuador',
                    '/estadisticas/params/?estadistica[descripcion]=Suramérica',
                    '/estadisticas/params/?estadistica[descripcion]=Estadística-Suraméricano',
                    '/estadisticas/params/?estadistica[nombre]=República-Bolivariana-de-Venezuela&estadistica[descripcion]=suramerica',
                    '/estadisticas/params/?estadistica[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/estadisticas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las estadísticas iniciando en el Offset.',
                'examples'       => array(
                    '/estadisticas/o1/',
                    '/estadisticas/o10',
                ),
            ),
            array(
                'route'         => '/estadisticas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las estadísticas iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/estadisticas/l2/',
                    '/estadisticas/l10',
                ),
            ),
            array(
                'route'         => '/estadisticas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las estadísticas iniciando en offset hasta limit.',
                'examples'       => array(
                    '/estadisticas/o1/l2/',
                    '/estadisticas/o10/l10',
                ),
            ),
            array(
                'route'         => '/estadisticas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una estadística.',
                'examples'       => array(
                    '/estadisticas/new/',
                    '/estadisticas/new',
                ),
            ),
            array(
                'route'         => '/estadisticas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea estadísticas. Puede recibir datos de varias estadísticas.',
                'examples'       => array(
                    '/estadisticas/',
                    '/estadisticas',
                ),
            ),
            array(
                'route'         => '/estadisticas/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de estadistica para editar.',
                'examples'       => array(
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/estadisticas/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de estadistica.',
                'examples'       => array(
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estadisticas/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de estadistica',
                'examples'       => array(
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estadisticas/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar estadistica.',
                'examples'       => array(
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/estadisticas/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra estadistica.',
                'examples'       => array(
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estadisticas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_estadisticas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Estadisticas.
     *
     * @Route("/estadisticas", name="get_estadisticas")
     * @Route("/estadisticas/", name="get_estadisticas_")
     * @Template()
     * @Method("GET")
     */
    public function getEstadisticasAction(Request $request)
    {
        $repository = $this->getEstadisticaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Estadisticas
     *
     * @Route("/estadisticas/new", name="new_estadisticas")
     * @Route("/estadisticas/new/", name="new_estadisticas_")
     * @Template()
     * @Method("GET")
     */
    public function newEstadisticasAction(Request $request)
    {
        $type = new EstadisticaType($this->generateUrl('post_estadisticas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Estadisticas.
     *
     * @Route("/estadisticas", name="post_estadisticas")
     * @Route("/estadisticas/", name="post_estadisticas_")
     * @Template()
     * @Method("POST")
     */
    public function postEstadisticasAction(Request $request)
    {
        $estadistica = new Estadistica();
        $type = new EstadisticaType($this->generateUrl('post_estadisticas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Estadística.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estadistica, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estadistica, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Estadistica.
     *
     * @Route("/estadisticas/{slug}", name="get_estadisticas_slug")
     * @Route("/estadisticas/{slug}/", name="get_estadisticas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEstadisticaAction(Request $request, $slug)
    {
        $estadistica = null;
        switch($slug){
            case 'params':
                $datos = $request->get('estadistica', false);
                if($datos){
                    $estadistica = $this->getEstadisticaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $estadistica = $this->getEstadisticaRepository()->find($slug);
                break;
        }
        if (!$estadistica) {
            $estadistica = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Estadística no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($estadistica, $request);
    }

    /**
     * Regresa el formulario para poder editar Estadistica existente.
     *
     * @Route("/estadisticas/{slug}/edit", name="edit_estadisticas")
     * @Route("/estadisticas/{slug}/edit/", name="edit_estadisticas_")
     * @Template()
     * @Method("GET")
     */
    public function editEstadisticaAction(Request $request, $slug)
    {
        $estadistica = $this->getEstadisticaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estadística no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EstadisticaType($this->generateUrl('put_estadisticas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $estadistica );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Estadistica existente.
     *
     * @Route("/estadisticas/{slug}", name="put_estadisticas")
     * @Route("/estadisticas/{slug}/", name="put_estadisticas_")
     * @Template()
     * @Method("PUT")
     */
    public function putEstadisticaAction(Request $request, $slug)
    {
        $estadistica = $this->getEstadisticaRepository()->find($slug);
        $type = new EstadisticaType($this->generateUrl('put_estadisticas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Estadística.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estadistica, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estadistica, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estadistica existente.
     *
     * @Route("/estadisticas/{slug}", name="patch_estadisticas")
     * @Route("/estadisticas/{slug}/", name="patch_estadisticas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstadisticaAction(Request $request, $slug)
    {
        $estadistica = $this->getEstadisticaRepository()->find($slug);
        $type = new EstadisticaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Estadística.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $estadistica){
            $repo = $this->getEstadisticaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($estadistica));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($estadistica, $id) !== $dato){
                            $accessor->setValue($estadistica, $id, $dato);
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
                                if(method_exists($estadistica,$set)){
                                    //$collection = new ArrayCollection($collection);
                                    $estadistica->$set($collection);
                                }
                                $isModify = true;
                            }
                        }else{
                            $dato = $repo->sanearDato($dato, 'guid');
                            $accessor = PropertyAccess::createPropertyAccessor();
                            $dato_ = $accessor->getValue($estadistica, $id);
                            if($dato && (!$dato_ || (is_object($dato_) && method_exists($dato_,'getId') && $dato_->getId() !== $dato))){
                                $association = $this->getManager()->getRepository($metadata->getAssociationTargetClass($id))->find($dato);
                                if($association && $association->getId()){
                                    $accessor->setValue($estadistica, $id, $association);
                                    $isModify = true;
                                }
                            }
                        }
                    }
                }
            }
            if($isModify){
                $estadistica = $this->captureErrorFlush($em, $estadistica, 'editar');
            }
            $rta = $estadistica;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Estadisticas..
     *
     * @Route("/estadisticas/{slug}/remove", name="remove_estadisticas")
     * @Route("/estadisticas/{slug}/remove/", name="remove_estadisticas_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstadisticasAction(Request $request, $slug)
    {
        $estadistica = $this->getEstadisticaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estadística no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($estadistica){
            $form = $this->createDeleteForm($slug,'delete_estadisticas');
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
     * Elimina Estadisticas
     *
     * @Route("/estadisticas/{slug}", name="delete_estadisticas")
     * @Route("/estadisticas/{slug}/", name="delete_estadisticas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstadisticasAction(Request $request, $slug)
    {
        $estadistica = $this->getEstadisticaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estadística no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($estadistica){
            $form = $this->createDeleteForm($slug,'delete_estadisticas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estadistica){
                $em = $this->getManager();
                $em->remove($estadistica);
                $estadistica = $this->captureErrorFlush($em, $estadistica, 'borrar');
                $rta = $estadistica;
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
