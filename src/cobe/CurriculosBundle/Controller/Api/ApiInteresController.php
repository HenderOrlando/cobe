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

use cobe\CurriculosBundle\Entity\Interes;
use cobe\CurriculosBundle\Form\InteresType;
use cobe\CurriculosBundle\Repository\InteresRepository;

/**
 * API Interes Controller.
 *
 * @package cobe\CurriculosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiInteresController extends ApiController
{
    /**
     * Retorna el repositorio de Interes
     *
     * @return InteresRepository
     */
    public function getInteresRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:Interes');
    }

    /**
     * Regresa opciones de API para Intereses.
     *
     * @Route("/intereses/attributes", name="options_intereses_validate")
     * @Route("/intereses/attributes/", name="options_intereses_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Interes();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Intereses.
     *
     * @Route("/intereses", name="options_intereses")
     * @Route("/intereses/", name="options_intereses_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsInteresesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/intereses',
                'method'        => 'GET',
                'description'   => 'Lista todos los intereses.',
                'examples'       => array(
                    '/intereses',
                    '/intereses/',
                ),
            ),
            array(
                'route'         => '/intereses/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los intereses.',
                'examples'       => array(
                    '/intereses/{id}',
                    '/intereses/{id}/',
                ),
            ),
            array(
                'route'         => '/intereses/params',
                'method'        => 'GET',
                'description'   => 'Lista los interéses que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/intereses/params/?interes[nombre]=Ecuador',
                    '/intereses/params/?interes[descripcion]=Suramérica',
                    '/intereses/params/?interes[descripcion]=Interés-Suraméricano',
                    '/intereses/params/?interes[nombre]=República-Bolivariana-de-Venezuela&interes[descripcion]=suramerica',
                    '/intereses/params/?interes[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/intereses/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los interéses iniciando en el Offset.',
                'examples'       => array(
                    '/intereses/o1/',
                    '/intereses/o10',
                ),
            ),
            array(
                'route'         => '/intereses/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los interéses iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/intereses/l2/',
                    '/intereses/l10',
                ),
            ),
            array(
                'route'         => '/intereses/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los interéses iniciando en offset hasta limit.',
                'examples'       => array(
                    '/intereses/o1/l2/',
                    '/intereses/o10/l10',
                ),
            ),
            array(
                'route'         => '/intereses/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un interés.',
                'examples'       => array(
                    '/intereses/new/',
                    '/intereses/new',
                ),
            ),
            array(
                'route'         => '/intereses',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea interéses. Puede recibir datos de varios interéses.',
                'examples'       => array(
                    '/intereses/',
                    '/intereses',
                ),
            ),
            array(
                'route'         => '/intereses/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de interes para editar.',
                'examples'       => array(
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/intereses/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de interes.',
                'examples'       => array(
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/intereses/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de interes',
                'examples'       => array(
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/intereses/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar interes.',
                'examples'       => array(
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/intereses/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra interes.',
                'examples'       => array(
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/intereses/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_intereses', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Intereses.
     *
     * @Route("/intereses", name="get_intereses")
     * @Route("/intereses/", name="get_intereses_")
     * @Template()
     * @Method("GET")
     */
    public function getInteresesAction(Request $request)
    {
        $repository = $this->getInteresRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Intereses
     *
     * @Route("/intereses/new", name="new_intereses")
     * @Route("/intereses/new/", name="new_intereses_")
     * @Template()
     * @Method("GET")
     */
    public function newInteresesAction(Request $request)
    {
        $type = new InteresType($this->generateUrl('post_intereses'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Intereses.
     *
     * @Route("/intereses", name="post_intereses")
     * @Route("/intereses/", name="post_intereses_")
     * @Template()
     * @Method("POST")
     */
    public function postInteresesAction(Request $request)
    {
        $interes = new Interes();
        $type = new InteresType($this->generateUrl('post_intereses'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Interés.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $interes, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($interes, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Interes.
     *
     * @Route("/intereses/{slug}", name="get_intereses_slug")
     * @Route("/intereses/{slug}/", name="get_intereses_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getInteresAction(Request $request, $slug)
    {
        $interes = null;
        switch($slug){
            case 'params':
                $datos = $request->get('interes', false);
                if($datos){
                    $interes = $this->getInteresRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $interes = $this->getInteresRepository()->find($slug);
                break;
        }
        if (!$interes) {
            $interes = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Interés no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($interes, $request);
    }

    /**
     * Regresa el formulario para poder editar Interes existente.
     *
     * @Route("/intereses/{slug}/edit", name="edit_intereses")
     * @Route("/intereses/{slug}/edit/", name="edit_intereses_")
     * @Template()
     * @Method("GET")
     */
    public function editInteresAction(Request $request, $slug)
    {
        $interes = $this->getInteresRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Interés no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new InteresType($this->generateUrl('put_intereses', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $interes );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Interes existente.
     *
     * @Route("/intereses/{slug}", name="put_intereses")
     * @Route("/intereses/{slug}/", name="put_intereses_")
     * @Template()
     * @Method("PUT")
     */
    public function putInteresAction(Request $request, $slug)
    {
        $interes = $this->getInteresRepository()->find($slug);
        $type = new InteresType($this->generateUrl('put_intereses', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Interés.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $interes, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($interes, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Interes existente.
     *
     * @Route("/intereses/{slug}", name="patch_intereses")
     * @Route("/intereses/{slug}/", name="patch_intereses_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchInteresAction(Request $request, $slug)
    {
        $interes = $this->getInteresRepository()->find($slug);
        $type = new InteresType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Interés.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $interes){
            $repo = $this->getInteresRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($interes));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id) && !in_array($id, $noModify)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($interes, $id) !== $dato){
                        $accessor->setValue($interes, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                $interes = $this->captureErrorFlush($em, $interes, 'editar');
            }
            $rta = $interes;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Intereses..
     *
     * @Route("/intereses/{slug}/remove", name="remove_intereses")
     * @Route("/intereses/{slug}/remove/", name="remove_intereses_")
     * @Template()
     * @Method("GET")
     */
    public function removeInteresesAction(Request $request, $slug)
    {
        $interes = $this->getInteresRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Interés no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($interes){
            $form = $this->createDeleteForm($slug,'delete_intereses');
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
     * Elimina Intereses
     *
     * @Route("/intereses/{slug}", name="delete_intereses")
     * @Route("/intereses/{slug}/", name="delete_intereses_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteInteresesAction(Request $request, $slug)
    {
        $interes = $this->getInteresRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Interés no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($interes){
            $form = $this->createDeleteForm($slug,'delete_intereses');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $interes){
                $em = $this->getManager();
                $em->remove($interes);
                $interes = $this->captureErrorFlush($em, $interes, 'borrar');
                $rta = $interes;
                if(!$rta['errors']){
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
