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

use cobe\CommonBundle\Entity\Pais;
use cobe\CommonBundle\Form\PaisType;
use cobe\CommonBundle\Repository\PaisRepository;

/**
 * API Pais Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiPaisController extends ApiController
{
    /**
     * Retorna el repositorio de Pais
     *
     * @return PaisRepository
     */
    public function getPaisRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Pais');
    }

    /**
     * Regresa opciones de API para Paises.
     *
     * @Route("/paises", name="options_paises")
     * @Route("/paises/", name="options_paises_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsPaisesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/paises',
                'method'        => 'GET',
                'description'   => 'Lista todos los paises.',
                'examples'       => array(
                    '/paises',
                    '/paises/',
                ),
            ),
            array(
                'route'         => '/paises/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los paises.',
                'examples'       => array(
                    '/paises/{id}',
                    '/paises/{id}/',
                ),
            ),
            array(
                'route'         => '/paises/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/paises/params/?pais[nombre]=Ecuador',
                    '/paises/params/?pais[descripcion]=Suramérica',
                    '/paises/params/?pais[descripcion]=País-Suraméricano',
                    '/paises/params/?pais[nombre]=República-Bolivariana-de-Venezuela&pais[descripcion]=suramerica',
                    '/paises/params/?pais[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/paises/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/paises/o1/',
                    '/paises/o10',
                ),
            ),
            array(
                'route'         => '/paises/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/paises/l2/',
                    '/paises/l10',
                ),
            ),
            array(
                'route'         => '/paises/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/paises/o1/l2/',
                    '/paises/o10/l10',
                ),
            ),
            array(
                'route'         => '/paises/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/paises/new/',
                    '/paises/new',
                ),
            ),
            array(
                'route'         => '/paises',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/paises/',
                    '/paises',
                ),
            ),
            array(
                'route'         => '/paises/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de pais para editar.',
                'examples'       => array(
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/paises/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de pais.',
                'examples'       => array(
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/paises/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de pais',
                'examples'       => array(
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/paises/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar pais.',
                'examples'       => array(
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/paises/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra pais.',
                'examples'       => array(
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/paises/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_paises', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Paises.
     *
     * @Route("/paises", name="get_paises")
     * @Route("/paises/", name="get_paises_")
     * @Template()
     * @Method("GET")
     */
    public function getPaisesAction(Request $request)
    {
        $repository = $this->getPaisRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Paises
     *
     * @Route("/paises/new", name="new_paises")
     * @Route("/paises/new/", name="new_paises_")
     * @Template()
     * @Method("GET")
     */
    public function newPaisesAction(Request $request)
    {
        $type = new PaisType($this->generateUrl('post_paises'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Paises.
     *
     * @Route("/paises", name="post_paises")
     * @Route("/paises/", name="post_paises_")
     * @Template()
     * @Method("POST")
     */
    public function postPaisesAction(Request $request)
    {
        $pais = new Pais();
        $type = new PaisType($this->generateUrl('post_paises'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $pais, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($pais, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Pais.
     *
     * @Route("/paises/{slug}", name="get_paises_slug")
     * @Route("/paises/{slug}/", name="get_paises_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getPaisAction(Request $request, $slug)
    {
        $pais = null;
        switch($slug){
            case 'params':
                $datos = $request->get('pais', false);
                if($datos){
                    $pais = $this->getPaisRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $pais = $this->getPaisRepository()->find($slug);
                break;
        }
        if (!$pais) {
            $pais = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($pais, $request);
    }

    /**
     * Regresa el formulario para poder editar Pais existente.
     *
     * @Route("/paises/{slug}/edit", name="edit_paises")
     * @Route("/paises/{slug}/edit/", name="edit_paises_")
     * @Template()
     * @Method("GET")
     */
    public function editPaisAction(Request $request, $slug)
    {
        $pais = $this->getPaisRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new PaisType($this->generateUrl('put_paises', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $pais );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Pais existente.
     *
     * @Route("/paises/{slug}", name="put_paises")
     * @Route("/paises/{slug}/", name="put_paises_")
     * @Template()
     * @Method("PUT")
     */
    public function putPaisAction(Request $request, $slug)
    {
        $pais = $this->getPaisRepository()->find($slug);
        $type = new PaisType($this->generateUrl('put_paises', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $pais, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($pais, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Pais existente.
     *
     * @Route("/paises/{slug}", name="patch_paises")
     * @Route("/paises/{slug}/", name="patch_paises_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchPaisAction(Request $request, $slug)
    {
        $pais = $this->getPaisRepository()->find($slug);
        $type = new PaisType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $pais){
            $repo = $this->getPaisRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($pais));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($pais, $id) !== $dato){
                        $accessor->setValue($pais, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($pais));
                    $name = $name[count($name)-1];
                    $pais = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $pais;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Paises..
     *
     * @Route("/paises/{slug}/remove", name="remove_paises")
     * @Route("/paises/{slug}/remove/", name="remove_paises_")
     * @Template()
     * @Method("GET")
     */
    public function removePaisesAction(Request $request, $slug)
    {
        $pais = $this->getPaisRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($pais){
            $form = $this->createDeleteForm($slug,'delete_paises');
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
     * Elimina Paises
     *
     * @Route("/paises/{slug}", name="delete_paises")
     * @Route("/paises/{slug}/", name="delete_paises_")
     * @Template()
     * @Method("DELETE")
     */
    public function deletePaisesAction(Request $request, $slug)
    {
        $pais = $this->getPaisRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($pais){
            $form = $this->createDeleteForm($slug,'delete_paises');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $pais){
                $em = $this->getManager();
                $em->remove($pais);
                $em->flush();
                $rta = $pais;
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
