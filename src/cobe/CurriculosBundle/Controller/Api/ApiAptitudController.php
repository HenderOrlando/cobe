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

use cobe\CurriculosBundle\Entity\Aptitud;
use cobe\CurriculosBundle\Form\AptitudType;
use cobe\CurriculosBundle\Repository\AptitudRepository;

/**
 * API Aptitud Controller.
 *
 * @package cobe\CurriculosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiAptitudController extends ApiController
{
    /**
     * Retorna el repositorio de Aptitud
     *
     * @return AptitudRepository
     */
    public function getAptitudRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:Aptitud');
    }

    /**
     * Regresa opciones de API para Aptitudes.
     *
     * @Route("/aptitudes/attributes", name="options_aptitudes_validate")
     * @Route("/aptitudes/attributes/", name="options_aptitudes_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Aptitud();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Aptitudes.
     *
     * @Route("/aptitudes", name="options_aptitudes")
     * @Route("/aptitudes/", name="options_aptitudes_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsAptitudesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/aptitudes',
                'method'        => 'GET',
                'description'   => 'Lista todos las aptitudes.',
                'examples'       => array(
                    '/aptitudes',
                    '/aptitudes/',
                ),
            ),
            array(
                'route'         => '/aptitudes/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos las aptitudes.',
                'examples'       => array(
                    '/aptitudes/{id}',
                    '/aptitudes/{id}/',
                ),
            ),
            array(
                'route'         => '/aptitudes/params',
                'method'        => 'GET',
                'description'   => 'Lista las aptiudes que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/aptitudes/params/?aptitud[nombre]=Ecuador',
                    '/aptitudes/params/?aptitud[descripcion]=Suramérica',
                    '/aptitudes/params/?aptitud[descripcion]=Aptiud-Suraméricano',
                    '/aptitudes/params/?aptitud[nombre]=República-Bolivariana-de-Venezuela&aptitud[descripcion]=suramerica',
                    '/aptitudes/params/?aptitud[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/aptitudes/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las aptiudes iniciando en el Offset.',
                'examples'       => array(
                    '/aptitudes/o1/',
                    '/aptitudes/o10',
                ),
            ),
            array(
                'route'         => '/aptitudes/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las aptiudes iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/aptitudes/l2/',
                    '/aptitudes/l10',
                ),
            ),
            array(
                'route'         => '/aptitudes/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las aptiudes iniciando en offset hasta limit.',
                'examples'       => array(
                    '/aptitudes/o1/l2/',
                    '/aptitudes/o10/l10',
                ),
            ),
            array(
                'route'         => '/aptitudes/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una aptiud.',
                'examples'       => array(
                    '/aptitudes/new/',
                    '/aptitudes/new',
                ),
            ),
            array(
                'route'         => '/aptitudes',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea aptiudes. Puede recibir datos de varias aptiudes.',
                'examples'       => array(
                    '/aptitudes/',
                    '/aptitudes',
                ),
            ),
            array(
                'route'         => '/aptitudes/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de aptitud para editar.',
                'examples'       => array(
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/aptitudes/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de aptitud.',
                'examples'       => array(
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/aptitudes/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de aptitud',
                'examples'       => array(
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/aptitudes/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar aptitud.',
                'examples'       => array(
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/aptitudes/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra aptitud.',
                'examples'       => array(
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/aptitudes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_aptitudes', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Aptitudes.
     *
     * @Route("/aptitudes", name="get_aptitudes")
     * @Route("/aptitudes/", name="get_aptitudes_")
     * @Template()
     * @Method("GET")
     */
    public function getAptitudesAction(Request $request)
    {
        $repository = $this->getAptitudRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Aptitudes
     *
     * @Route("/aptitudes/new", name="new_aptitudes")
     * @Route("/aptitudes/new/", name="new_aptitudes_")
     * @Template()
     * @Method("GET")
     */
    public function newAptitudesAction(Request $request)
    {
        $type = new AptitudType($this->generateUrl('post_aptitudes'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Aptitudes.
     *
     * @Route("/aptitudes", name="post_aptitudes")
     * @Route("/aptitudes/", name="post_aptitudes_")
     * @Template()
     * @Method("POST")
     */
    public function postAptitudesAction(Request $request)
    {
        $aptitud = new Aptitud();
        $type = new AptitudType($this->generateUrl('post_aptitudes'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Aptiud.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $aptitud, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($aptitud, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Aptitud.
     *
     * @Route("/aptitudes/{slug}", name="get_aptitudes_slug")
     * @Route("/aptitudes/{slug}/", name="get_aptitudes_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getAptitudAction(Request $request, $slug)
    {
        $aptitud = null;
        switch($slug){
            case 'params':
                $datos = $request->get('aptitud', false);
                if($datos){
                    $aptitud = $this->getAptitudRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $aptitud = $this->getAptitudRepository()->find($slug);
                break;
        }
        if (!$aptitud) {
            $aptitud = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Aptiud no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($aptitud, $request);
    }

    /**
     * Regresa el formulario para poder editar Aptitud existente.
     *
     * @Route("/aptitudes/{slug}/edit", name="edit_aptitudes")
     * @Route("/aptitudes/{slug}/edit/", name="edit_aptitudes_")
     * @Template()
     * @Method("GET")
     */
    public function editAptitudAction(Request $request, $slug)
    {
        $aptitud = $this->getAptitudRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Aptiud no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new AptitudType($this->generateUrl('put_aptitudes', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $aptitud );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Aptitud existente.
     *
     * @Route("/aptitudes/{slug}", name="put_aptitudes")
     * @Route("/aptitudes/{slug}/", name="put_aptitudes_")
     * @Template()
     * @Method("PUT")
     */
    public function putAptitudAction(Request $request, $slug)
    {
        $aptitud = $this->getAptitudRepository()->find($slug);
        $type = new AptitudType($this->generateUrl('put_aptitudes', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Aptiud.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $aptitud, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($aptitud, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Aptitud existente.
     *
     * @Route("/aptitudes/{slug}", name="patch_aptitudes")
     * @Route("/aptitudes/{slug}/", name="patch_aptitudes_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchAptitudAction(Request $request, $slug)
    {
        $aptitud = $this->getAptitudRepository()->find($slug);
        $type = new AptitudType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Aptiud.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $aptitud){
            $repo = $this->getAptitudRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($aptitud));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($aptitud, $id) !== $dato){
                        $accessor->setValue($aptitud, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                $aptitud = $this->captureErrorFlush($em, $aptitud, 'editar');
            }
            $rta = $aptitud;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Aptitudes..
     *
     * @Route("/aptitudes/{slug}/remove", name="remove_aptitudes")
     * @Route("/aptitudes/{slug}/remove/", name="remove_aptitudes_")
     * @Template()
     * @Method("GET")
     */
    public function removeAptitudesAction(Request $request, $slug)
    {
        $aptitud = $this->getAptitudRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Aptiud no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($aptitud){
            $form = $this->createDeleteForm($slug,'delete_aptitudes');
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
     * Elimina Aptitudes
     *
     * @Route("/aptitudes/{slug}", name="delete_aptitudes")
     * @Route("/aptitudes/{slug}/", name="delete_aptitudes_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteAptitudesAction(Request $request, $slug)
    {
        $aptitud = $this->getAptitudRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Aptiud no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($aptitud){
            $form = $this->createDeleteForm($slug,'delete_aptitudes');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $aptitud){
                $em = $this->getManager();
                $em->remove($aptitud);
                $aptitud = $this->captureErrorFlush($em, $aptitud, 'borrar');
                $rta = $aptitud;
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
