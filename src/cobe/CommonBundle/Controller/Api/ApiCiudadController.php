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

use cobe\CommonBundle\Entity\Ciudad;
use cobe\CommonBundle\Form\CiudadType;
use cobe\CommonBundle\Repository\CiudadRepository;

/**
 * API Ciudad Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiCiudadController extends ApiController
{
    /**
     * Retorna el repositorio de Ciudad
     *
     * @return CiudadRepository
     */
    public function getCiudadRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Ciudad');
    }

    /**
     * Regresa opciones de API para Ciudades.
     *
     * @Route("/ciudades/attributes", name="options_ciudades_validate")
     * @Route("/ciudades/attributes/", name="options_ciudades_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Ciudad();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Ciudades.
     *
     * @Route("/ciudades", name="options_ciudades")
     * @Route("/ciudades/", name="options_ciudades_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsCiudadesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/ciudades',
                'method'        => 'GET',
                'description'   => 'Lista todos las ciudades.',
                'examples'       => array(
                    '/ciudades',
                    '/ciudades/',
                ),
            ),
            array(
                'route'         => '/ciudades/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos las ciudades.',
                'examples'       => array(
                    '/ciudades/{id}',
                    '/ciudades/{id}/',
                ),
            ),
            array(
                'route'         => '/ciudades/params',
                'method'        => 'GET',
                'description'   => 'Lista las ciudades que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/ciudades/params/?ciudad[nombre]=Ecuador',
                    '/ciudades/params/?ciudad[descripcion]=Suramérica',
                    '/ciudades/params/?ciudad[descripcion]=País-Suraméricano',
                    '/ciudades/params/?ciudad[nombre]=República-Bolivariana-de-Venezuela&ciudad[descripcion]=suramerica',
                    '/ciudades/params/?ciudad[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/ciudades/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las ciudades iniciando en el Offset.',
                'examples'       => array(
                    '/ciudades/o1/',
                    '/ciudades/o10',
                ),
            ),
            array(
                'route'         => '/ciudades/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las ciudades iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/ciudades/l2/',
                    '/ciudades/l10',
                ),
            ),
            array(
                'route'         => '/ciudades/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las ciudades iniciando en offset hasta limit.',
                'examples'       => array(
                    '/ciudades/o1/l2/',
                    '/ciudades/o10/l10',
                ),
            ),
            array(
                'route'         => '/ciudades/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una ciudad.',
                'examples'       => array(
                    '/ciudades/new/',
                    '/ciudades/new',
                ),
            ),
            array(
                'route'         => '/ciudades',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea ciudades. Puede recibir datos de varias ciudades.',
                'examples'       => array(
                    '/ciudades/',
                    '/ciudades',
                ),
            ),
            array(
                'route'         => '/ciudades/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de ciudad para editar.',
                'examples'       => array(
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/ciudades/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de ciudad.',
                'examples'       => array(
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/ciudades/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de ciudad',
                'examples'       => array(
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/ciudades/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar ciudad.',
                'examples'       => array(
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/ciudades/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra ciudad.',
                'examples'       => array(
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/ciudades/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_ciudades', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Ciudades.
     *
     * @Route("/ciudades", name="get_ciudades")
     * @Route("/ciudades/", name="get_ciudades_")
     * @Template()
     * @Method("GET")
     */
    public function getCiudadesAction(Request $request)
    {
        $repository = $this->getCiudadRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Ciudades
     *
     * @Route("/ciudades/new", name="new_ciudades")
     * @Route("/ciudades/new/", name="new_ciudades_")
     * @Template()
     * @Method("GET")
     */
    public function newCiudadesAction(Request $request)
    {
        $type = new CiudadType($this->generateUrl('post_ciudades'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Ciudades.
     *
     * @Route("/ciudades", name="post_ciudades")
     * @Route("/ciudades/", name="post_ciudades_")
     * @Template()
     * @Method("POST")
     */
    public function postCiudadesAction(Request $request)
    {
        $ciudad = new Ciudad();
        $type = new CiudadType($this->generateUrl('post_ciudades'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Ciudad.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $ciudad, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($ciudad, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Ciudad.
     *
     * @Route("/ciudades/{slug}", name="get_ciudades_slug")
     * @Route("/ciudades/{slug}/", name="get_ciudades_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getCiudadAction(Request $request, $slug)
    {
        $ciudad = null;
        switch($slug){
            case 'params':
                $datos = $request->get('ciudad', false);
                if($datos){
                    $ciudad = $this->getCiudadRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $ciudad = $this->getCiudadRepository()->find($slug);
                break;
        }
        if (!$ciudad) {
            $ciudad = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($ciudad, $request);
    }

    /**
     * Regresa el formulario para poder editar Ciudad existente.
     *
     * @Route("/ciudades/{slug}/edit", name="edit_ciudades")
     * @Route("/ciudades/{slug}/edit/", name="edit_ciudades_")
     * @Template()
     * @Method("GET")
     */
    public function editCiudadAction(Request $request, $slug)
    {
        $ciudad = $this->getCiudadRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new CiudadType($this->generateUrl('put_ciudades', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $ciudad );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Ciudad existente.
     *
     * @Route("/ciudades/{slug}", name="put_ciudades")
     * @Route("/ciudades/{slug}/", name="put_ciudades_")
     * @Template()
     * @Method("PUT")
     */
    public function putCiudadAction(Request $request, $slug)
    {
        $ciudad = $this->getCiudadRepository()->find($slug);
        $type = new CiudadType($this->generateUrl('put_ciudades', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Ciudad.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $ciudad, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($ciudad, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Ciudad existente.
     *
     * @Route("/ciudades/{slug}", name="patch_ciudades")
     * @Route("/ciudades/{slug}/", name="patch_ciudades_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCiudadAction(Request $request, $slug)
    {
        $ciudad = $this->getCiudadRepository()->find($slug);
        $type = new CiudadType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Ciudad.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $ciudad){
            $repo = $this->getCiudadRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($ciudad));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($ciudad, $id) !== $dato){
                            $accessor->setValue($ciudad, $id, $dato);
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
                                if(method_exists($ciudad,$set)){
                                    //$collection = new ArrayCollection($collection);
                                    $ciudad->$set($collection);
                                }
                                $isModify = true;
                            }
                        }else{
                            $dato = $repo->sanearDato($dato, 'guid');
                            $accessor = PropertyAccess::createPropertyAccessor();
                            $dato_ = $accessor->getValue($ciudad, $id);
                            if($dato && (!$dato_ || (is_object($dato_) && method_exists($dato_,'getId') && $dato_->getId() !== $dato))){
                                $association = $this->getManager()->getRepository($metadata->getAssociationTargetClass($id))->find($dato);
                                if($association && $association->getId()){
                                    $accessor->setValue($ciudad, $id, $association);
                                    $isModify = true;
                                }
                            }
                        }
                    }
                }
            }
            if($isModify){
                $ciudad = $this->captureErrorFlush($em, $ciudad, 'editar');
            }
            $rta = $ciudad;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Ciudades..
     *
     * @Route("/ciudades/{slug}/remove", name="remove_ciudades")
     * @Route("/ciudades/{slug}/remove/", name="remove_ciudades_")
     * @Template()
     * @Method("GET")
     */
    public function removeCiudadesAction(Request $request, $slug)
    {
        $ciudad = $this->getCiudadRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($ciudad){
            $form = $this->createDeleteForm($slug,'delete_ciudades');
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
     * Elimina Ciudades
     *
     * @Route("/ciudades/{slug}", name="delete_ciudades")
     * @Route("/ciudades/{slug}/", name="delete_ciudades_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCiudadesAction(Request $request, $slug)
    {
        $ciudad = $this->getCiudadRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($ciudad){
            $form = $this->createDeleteForm($slug,'delete_ciudades');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $ciudad){
                $em = $this->getManager();
                $em->remove($ciudad);
                $ciudad = $this->captureErrorFlush($em, $ciudad, 'borrar');
                $rta = $ciudad;
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
