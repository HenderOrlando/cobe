<?php

namespace cobe\OfertasLaboralesBundle\Controller\Api;

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

use cobe\OfertasLaboralesBundle\Entity\OfertaLaboral;
use cobe\OfertasLaboralesBundle\Form\OfertaLaboralType;
use cobe\OfertasLaboralesBundle\Repository\OfertaLaboralRepository;

/**
 * API OfertaLaboral Controller.
 *
 * @package cobe\OfertasLaboralesBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiOfertaLaboralController extends ApiController
{
    /**
     * Retorna el repositorio de OfertaLaboral
     *
     * @return OfertaLaboralRepository
     */
    public function getOfertaLaboralRepository()
    {
        return $this->getManager()->getRepository('cobeOfertasLaboralesBundle:OfertaLaboral');
    }

    /**
     * Regresa opciones de API para Ofertas Laborales.
     *
     * @Route("/ofertaslaborales/attributes", name="options_ofertaslaborales_validate")
     * @Route("/ofertaslaborales/attributes/", name="options_ofertaslaborales_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new OfertaLaboral();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para OfertasLaborales.
     *
     * @Route("/ofertaslaborales", name="options_ofertaslaborales")
     * @Route("/ofertaslaborales/", name="options_ofertaslaborales_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsOfertasLaboralesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/ofertaslaborales',
                'method'        => 'GET',
                'description'   => 'Lista todas las ofertaslaborales.',
                'examples'       => array(
                    '/ofertaslaborales',
                    '/ofertaslaborales/',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todas las ofertaslaborales.',
                'examples'       => array(
                    '/ofertaslaborales/{id}',
                    '/ofertaslaborales/{id}/',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/params',
                'method'        => 'GET',
                'description'   => 'Lista las ofertaslaborales que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/ofertaslaborales/params/?ofertalaboral[nombre]=Ecuador',
                    '/ofertaslaborales/params/?ofertalaboral[descripcion]=Suramérica',
                    '/ofertaslaborales/params/?ofertalaboral[descripcion]=OfertaLaboral-Suraméricano',
                    '/ofertaslaborales/params/?ofertalaboral[nombre]=República-Bolivariana-de-Venezuela&ofertalaboral[descripcion]=suramerica',
                    '/ofertaslaborales/params/?ofertalaboral[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las ofertaslaborales iniciando en el Offset.',
                'examples'       => array(
                    '/ofertaslaborales/o1/',
                    '/ofertaslaborales/o10',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las ofertaslaborales iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/ofertaslaborales/l2/',
                    '/ofertaslaborales/l10',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las ofertaslaborales iniciando en offset hasta limit.',
                'examples'       => array(
                    '/ofertaslaborales/o1/l2/',
                    '/ofertaslaborales/o10/l10',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una ofertaslaboral.',
                'examples'       => array(
                    '/ofertaslaborales/new/',
                    '/ofertaslaborales/new',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea ofertaslaborales. Puede recibir datos de varias ofertaslaborales.',
                'examples'       => array(
                    '/ofertaslaborales/',
                    '/ofertaslaborales',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de ofertalaboral para editar.',
                'examples'       => array(
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de ofertalaboral.',
                'examples'       => array(
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de ofertalaboral',
                'examples'       => array(
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar ofertalaboral.',
                'examples'       => array(
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/ofertaslaborales/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra ofertalaboral.',
                'examples'       => array(
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/ofertaslaborales/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_ofertaslaborales', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de OfertasLaborales.
     *
     * @Route("/ofertaslaborales", name="get_ofertaslaborales")
     * @Route("/ofertaslaborales/", name="get_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function getOfertasLaboralesAction(Request $request)
    {
        $repository = $this->getOfertaLaboralRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear OfertasLaborales
     *
     * @Route("/ofertaslaborales/new", name="new_ofertaslaborales")
     * @Route("/ofertaslaborales/new/", name="new_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function newOfertasLaboralesAction(Request $request)
    {
        $type = new OfertaLaboralType($this->generateUrl('post_ofertaslaborales'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea OfertasLaborales.
     *
     * @Route("/ofertaslaborales", name="post_ofertaslaborales")
     * @Route("/ofertaslaborales/", name="post_ofertaslaborales_")
     * @Template()
     * @Method("POST")
     */
    public function postOfertasLaboralesAction(Request $request)
    {
        $ofertalaboral = new OfertaLaboral();
        $type = new OfertaLaboralType($this->generateUrl('post_ofertaslaborales'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la OfertaLaboral.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $ofertalaboral, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($ofertalaboral, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa OfertaLaboral.
     *
     * @Route("/ofertaslaborales/{slug}", name="get_ofertaslaborales_slug")
     * @Route("/ofertaslaborales/{slug}/", name="get_ofertaslaborales_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = null;
        switch($slug){
            case 'params':
                $datos = $request->get('ofertalaboral', false);
                if($datos){
                    $ofertalaboral = $this->getOfertaLaboralRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);
                break;
        }
        if (!$ofertalaboral) {
            $ofertalaboral = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'OfertaLaboral no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($ofertalaboral, $request);
    }

    /**
     * Regresa el formulario para poder editar OfertaLaboral existente.
     *
     * @Route("/ofertaslaborales/{slug}/edit", name="edit_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/edit/", name="edit_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function editOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'OfertaLaboral no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new OfertaLaboralType($this->generateUrl('put_ofertaslaborales', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $ofertalaboral );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe OfertaLaboral existente.
     *
     * @Route("/ofertaslaborales/{slug}", name="put_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/", name="put_ofertaslaborales_")
     * @Template()
     * @Method("PUT")
     */
    public function putOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);
        $type = new OfertaLaboralType($this->generateUrl('put_ofertaslaborales', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para Sobreescribir la OfertaLaboral.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $ofertalaboral, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($ofertalaboral, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de OfertaLaboral existente.
     *
     * @Route("/ofertaslaborales/{slug}", name="patch_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/", name="patch_ofertaslaborales_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);
        $type = new OfertaLaboralType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para Actualizar la OfertaLaboral.',
                    'code'      => '400',
                ),
            ),
        );
        if($datos && $ofertalaboral){
            $repo = $this->getOfertaLaboralRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($ofertalaboral));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($ofertalaboral, $id) !== $dato){
                            $accessor->setValue($ofertalaboral, $id, $dato);
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
                                if(method_exists($ofertalaboral,$set)){
                                    //$collection = new ArrayCollection($collection);
                                    $ofertalaboral->$set($collection);
                                }
                                $isModify = true;
                            }
                        }else{
                            $dato = $repo->sanearDato($dato, 'guid');
                            $accessor = PropertyAccess::createPropertyAccessor();
                            $dato_ = $accessor->getValue($ofertalaboral, $id);
                            if($dato && (!$dato_ || (is_object($dato_) && method_exists($dato_,'getId') && $dato_->getId() !== $dato))){
                                $association = $this->getManager()->getRepository($metadata->getAssociationTargetClass($id))->find($dato);
                                if($association && $association->getId()){
                                    $accessor->setValue($ofertalaboral, $id, $association);
                                    $isModify = true;
                                }
                            }
                        }
                    }
                }
            }
            if($isModify){
                $ofertalaboral = $this->captureErrorFlush($em, $ofertalaboral, 'editar');
            }
            $rta = $ofertalaboral;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar OfertasLaborales..
     *
     * @Route("/ofertaslaborales/{slug}/remove", name="remove_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/remove/", name="remove_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function removeOfertasLaboralesAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'OfertaLaboral no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($ofertalaboral){
            $form = $this->createDeleteForm($slug,'delete_ofertaslaborales');
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
     * Elimina OfertasLaborales
     *
     * @Route("/ofertaslaborales/{slug}", name="delete_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/", name="delete_ofertaslaborales_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteOfertasLaboralesAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'OfertaLaboral no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($ofertalaboral){
            $form = $this->createDeleteForm($slug,'delete_ofertaslaborales');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $ofertalaboral){
                $em = $this->getManager();
                $em->remove($ofertalaboral);
                $ofertalaboral = $this->captureErrorFlush($em, $ofertalaboral, 'borrar');
                $rta = $ofertalaboral;
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
