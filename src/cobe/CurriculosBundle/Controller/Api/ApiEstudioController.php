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

use cobe\CurriculosBundle\Entity\Estudio;
use cobe\CurriculosBundle\Form\EstudioType;
use cobe\CurriculosBundle\Repository\EstudioRepository;

/**
 * API Estudio Controller.
 *
 * @package cobe\CurriculosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEstudioController extends ApiController
{
    /**
     * Retorna el repositorio de Estudio
     *
     * @return EstudioRepository
     */
    public function getEstudioRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:Estudio');
    }

    /**
     * Regresa opciones de API para Estudios.
     *
     * @Route("/estudios/attributes", name="options_estudios_validate")
     * @Route("/estudios/attributes/", name="options_estudios_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Estudio();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Estudios.
     *
     * @Route("/estudios", name="options_estudios")
     * @Route("/estudios/", name="options_estudios_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEstudiosAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/estudios',
                'method'        => 'GET',
                'description'   => 'Lista todos los estudios.',
                'examples'       => array(
                    '/estudios',
                    '/estudios/',
                ),
            ),
            array(
                'route'         => '/estudios/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los estudios.',
                'examples'       => array(
                    '/estudios/{id}',
                    '/estudios/{id}/',
                ),
            ),
            array(
                'route'         => '/estudios/params',
                'method'        => 'GET',
                'description'   => 'Lista los estudios que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/estudios/params/?estudio[nombre]=Ecuador',
                    '/estudios/params/?estudio[descripcion]=Suramérica',
                    '/estudios/params/?estudio[descripcion]=Estudio-Suraméricano',
                    '/estudios/params/?estudio[nombre]=República-Bolivariana-de-Venezuela&estudio[descripcion]=suramerica',
                    '/estudios/params/?estudio[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/estudios/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los estudios iniciando en el Offset.',
                'examples'       => array(
                    '/estudios/o1/',
                    '/estudios/o10',
                ),
            ),
            array(
                'route'         => '/estudios/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los estudios iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/estudios/l2/',
                    '/estudios/l10',
                ),
            ),
            array(
                'route'         => '/estudios/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los estudios iniciando en offset hasta limit.',
                'examples'       => array(
                    '/estudios/o1/l2/',
                    '/estudios/o10/l10',
                ),
            ),
            array(
                'route'         => '/estudios/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un estudio.',
                'examples'       => array(
                    '/estudios/new/',
                    '/estudios/new',
                ),
            ),
            array(
                'route'         => '/estudios',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea estudios. Puede recibir datos de varios estudios.',
                'examples'       => array(
                    '/estudios/',
                    '/estudios',
                ),
            ),
            array(
                'route'         => '/estudios/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de estudio para editar.',
                'examples'       => array(
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/estudios/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de estudio.',
                'examples'       => array(
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estudios/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de estudio',
                'examples'       => array(
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estudios/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar estudio.',
                'examples'       => array(
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/estudios/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra estudio.',
                'examples'       => array(
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estudios/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_estudios', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Estudios.
     *
     * @Route("/estudios", name="get_estudios")
     * @Route("/estudios/", name="get_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function getEstudiosAction(Request $request)
    {
        $repository = $this->getEstudioRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Estudios
     *
     * @Route("/estudios/new", name="new_estudios")
     * @Route("/estudios/new/", name="new_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function newEstudiosAction(Request $request)
    {
        $type = new EstudioType($this->generateUrl('post_estudios'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Estudios.
     *
     * @Route("/estudios", name="post_estudios")
     * @Route("/estudios/", name="post_estudios_")
     * @Template()
     * @Method("POST")
     */
    public function postEstudiosAction(Request $request)
    {
        $estudio = new Estudio();
        $type = new EstudioType($this->generateUrl('post_estudios'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Estudio.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estudio, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estudio, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Estudio.
     *
     * @Route("/estudios/{slug}", name="get_estudios_slug")
     * @Route("/estudios/{slug}/", name="get_estudios_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEstudioAction(Request $request, $slug)
    {
        $estudio = null;
        switch($slug){
            case 'params':
                $datos = $request->get('estudio', false);
                if($datos){
                    $estudio = $this->getEstudioRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $estudio = $this->getEstudioRepository()->find($slug);
                break;
        }
        if (!$estudio) {
            $estudio = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Estudio no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($estudio, $request);
    }

    /**
     * Regresa el formulario para poder editar Estudio existente.
     *
     * @Route("/estudios/{slug}/edit", name="edit_estudios")
     * @Route("/estudios/{slug}/edit/", name="edit_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function editEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estudio no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EstudioType($this->generateUrl('put_estudios', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $estudio );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Estudio existente.
     *
     * @Route("/estudios/{slug}", name="put_estudios")
     * @Route("/estudios/{slug}/", name="put_estudios_")
     * @Template()
     * @Method("PUT")
     */
    public function putEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);
        $type = new EstudioType($this->generateUrl('put_estudios', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Estudio.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estudio, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estudio, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estudio existente.
     *
     * @Route("/estudios/{slug}", name="patch_estudios")
     * @Route("/estudios/{slug}/", name="patch_estudios_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);
        $type = new EstudioType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Estudio.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $estudio){
            $repo = $this->getEstudioRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($estudio));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($estudio, $id) !== $dato){
                        $accessor->setValue($estudio, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                $estudio = $this->captureErrorFlush($em, $estudio, 'editar');
            }
            $rta = $estudio;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Estudios..
     *
     * @Route("/estudios/{slug}/remove", name="remove_estudios")
     * @Route("/estudios/{slug}/remove/", name="remove_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstudiosAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estudio no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudio){
            $form = $this->createDeleteForm($slug,'delete_estudios');
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
     * Elimina Estudios
     *
     * @Route("/estudios/{slug}", name="delete_estudios")
     * @Route("/estudios/{slug}/", name="delete_estudios_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstudiosAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estudio no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudio){
            $form = $this->createDeleteForm($slug,'delete_estudios');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estudio){
                $em = $this->getManager();
                $em->remove($estudio);
                $estudio = $this->captureErrorFlush($em, $estudio, 'borrar');
                $rta = $estudio;
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
