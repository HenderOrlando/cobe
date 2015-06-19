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

use cobe\CommonBundle\Entity\Idioma;
use cobe\CommonBundle\Form\IdiomaType;
use cobe\CommonBundle\Repository\IdiomaRepository;

/**
 * API Idioma Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiIdiomaController extends ApiController
{
    /**
     * Retorna el repositorio de Idioma
     *
     * @return IdiomaRepository
     */
    public function getIdiomaRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Idioma');
    }

    /**
     * Regresa opciones de API para Idiomas.
     *
     * @Route("/idiomas/attributes", name="options_idiomas_validate")
     * @Route("/idiomas/attributes/", name="options_idiomas_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Idioma();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Idiomas.
     *
     * @Route("/idiomas", name="options_idiomas")
     * @Route("/idiomas/", name="options_idiomas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsIdiomasAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/idiomas',
                'method'        => 'GET',
                'description'   => 'Lista todos los idiomas.',
                'examples'       => array(
                    '/idiomas',
                    '/idiomas/',
                ),
            ),
            array(
                'route'         => '/idiomas/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los idiomas.',
                'examples'       => array(
                    '/idiomas/{id}',
                    '/idiomas/{id}/',
                ),
            ),
            array(
                'route'         => '/idiomas/params',
                'method'        => 'GET',
                'description'   => 'Lista los idiomas que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/idiomas/params/?idioma[nombre]=Ecuador',
                    '/idiomas/params/?idioma[descripcion]=Suramérica',
                    '/idiomas/params/?idioma[descripcion]=Idioma-Suraméricano',
                    '/idiomas/params/?idioma[nombre]=República-Bolivariana-de-Venezuela&idioma[descripcion]=suramerica',
                    '/idiomas/params/?idioma[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/idiomas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los idiomas iniciando en el Offset.',
                'examples'       => array(
                    '/idiomas/o1/',
                    '/idiomas/o10',
                ),
            ),
            array(
                'route'         => '/idiomas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los idiomas iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/idiomas/l2/',
                    '/idiomas/l10',
                ),
            ),
            array(
                'route'         => '/idiomas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los idiomas iniciando en offset hasta limit.',
                'examples'       => array(
                    '/idiomas/o1/l2/',
                    '/idiomas/o10/l10',
                ),
            ),
            array(
                'route'         => '/idiomas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un idioma.',
                'examples'       => array(
                    '/idiomas/new/',
                    '/idiomas/new',
                ),
            ),
            array(
                'route'         => '/idiomas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea idiomas. Puede recibir datos de varios idiomas.',
                'examples'       => array(
                    '/idiomas/',
                    '/idiomas',
                ),
            ),
            array(
                'route'         => '/idiomas/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de idioma para editar.',
                'examples'       => array(
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/idiomas/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de idioma.',
                'examples'       => array(
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/idiomas/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de idioma',
                'examples'       => array(
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/idiomas/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar idioma.',
                'examples'       => array(
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/idiomas/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra idioma.',
                'examples'       => array(
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/idiomas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_idiomas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Idiomas.
     *
     * @Route("/idiomas", name="get_idiomas")
     * @Route("/idiomas/", name="get_idiomas_")
     * @Template()
     * @Method("GET")
     */
    public function getIdiomasAction(Request $request)
    {
        $repository = $this->getIdiomaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Idiomas
     *
     * @Route("/idiomas/new", name="new_idiomas")
     * @Route("/idiomas/new/", name="new_idiomas_")
     * @Template()
     * @Method("GET")
     */
    public function newIdiomasAction(Request $request)
    {
        $type = new IdiomaType($this->generateUrl('post_idiomas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Idiomas.
     *
     * @Route("/idiomas", name="post_idiomas")
     * @Route("/idiomas/", name="post_idiomas_")
     * @Template()
     * @Method("POST")
     */
    public function postIdiomasAction(Request $request)
    {
        $idioma = new Idioma();
        $type = new IdiomaType($this->generateUrl('post_idiomas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Idioma.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $idioma, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($idioma, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Idioma.
     *
     * @Route("/idiomas/{slug}", name="get_idiomas_slug")
     * @Route("/idiomas/{slug}/", name="get_idiomas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getIdiomaAction(Request $request, $slug)
    {
        $idioma = null;
        switch($slug){
            case 'params':
                $datos = $request->get('idioma', false);
                if($datos){
                    $idioma = $this->getIdiomaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $idioma = $this->getIdiomaRepository()->find($slug);
                break;
        }
        if (!$idioma) {
            $idioma = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Idioma no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($idioma, $request);
    }

    /**
     * Regresa el formulario para poder editar Idioma existente.
     *
     * @Route("/idiomas/{slug}/edit", name="edit_idiomas")
     * @Route("/idiomas/{slug}/edit/", name="edit_idiomas_")
     * @Template()
     * @Method("GET")
     */
    public function editIdiomaAction(Request $request, $slug)
    {
        $idioma = $this->getIdiomaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Idioma no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new IdiomaType($this->generateUrl('put_idiomas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $idioma );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Idioma existente.
     *
     * @Route("/idiomas/{slug}", name="put_idiomas")
     * @Route("/idiomas/{slug}/", name="put_idiomas_")
     * @Template()
     * @Method("PUT")
     */
    public function putIdiomaAction(Request $request, $slug)
    {
        $idioma = $this->getIdiomaRepository()->find($slug);
        $type = new IdiomaType($this->generateUrl('put_idiomas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Idioma.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $idioma, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($idioma, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Idioma existente.
     *
     * @Route("/idiomas/{slug}", name="patch_idiomas")
     * @Route("/idiomas/{slug}/", name="patch_idiomas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchIdiomaAction(Request $request, $slug)
    {
        $idioma = $this->getIdiomaRepository()->find($slug);
        $type = new IdiomaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Idioma.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $idioma){
            $repo = $this->getIdiomaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($idioma));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($idioma, $id) !== $dato){
                        $accessor->setValue($idioma, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                $idioma = $this->captureErrorFlush($em, $idioma, 'editar');
            }
            $rta = $idioma;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Idiomas..
     *
     * @Route("/idiomas/{slug}/remove", name="remove_idiomas")
     * @Route("/idiomas/{slug}/remove/", name="remove_idiomas_")
     * @Template()
     * @Method("GET")
     */
    public function removeIdiomasAction(Request $request, $slug)
    {
        $idioma = $this->getIdiomaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Idioma no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($idioma){
            $form = $this->createDeleteForm($slug,'delete_idiomas');
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
     * Elimina Idiomas
     *
     * @Route("/idiomas/{slug}", name="delete_idiomas")
     * @Route("/idiomas/{slug}/", name="delete_idiomas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteIdiomasAction(Request $request, $slug)
    {
        $idioma = $this->getIdiomaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Idioma no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($idioma){
            $form = $this->createDeleteForm($slug,'delete_idiomas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $idioma){
                $em = $this->getManager();
                $em->remove($idioma);
                $idioma = $this->captureErrorFlush($em, $idioma, 'borrar');
                $rta = $idioma;
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
