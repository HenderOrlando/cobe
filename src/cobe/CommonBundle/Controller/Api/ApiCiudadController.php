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
     * @Route("/ciudades", name="options_ciudades")
     * @Route("/ciudades/", name="options_ciudades_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsCiudadesAction(Request $request)
    {
        $opciones = array(
            '/ciudades' => array(
                'route'         => '/ciudades',
                'method'        => 'GET',
                'description'   => 'Lista todos los ciudades.',
                'examples'       => array(
                    '/ciudades',
                    '/ciudades/',
                ),
            ),
            '/ciudades/params' => array(
                'route'         => '/ciudades/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/ciudades/params/?ciudad[nombre]=Ecuador',
                    '/ciudades/params/?ciudad[descripcion]=Suramérica',
                    '/ciudades/params/?ciudad[descripcion]=País-Suraméricano',
                    '/ciudades/params/?ciudad[nombre]=República-Bolivariana-de-Venezuela&ciudad[descripcion]=suramerica',
                    '/ciudades/params/?ciudad[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/ciudades/o{offset}/' => array(
                'route'         => '/ciudades/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/ciudades/o1/',
                    '/ciudades/o10',
                ),
            ),
            '/ciudades/l{limit}/' => array(
                'route'         => '/ciudades/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/ciudades/l2/',
                    '/ciudades/l10',
                ),
            ),
            '/ciudades/0{offset}/l{limit}' => array(
                'route'         => '/ciudades/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/ciudades/o1/l2/',
                    '/ciudades/o10/l10',
                ),
            ),
            '/ciudades/new' => array(
                'route'         => '/ciudades/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/ciudades/new/',
                    '/ciudades/new',
                ),
            ),
            '/ciudades' => array(
                'route'         => '/ciudades',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/ciudades/',
                    '/ciudades',
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
                    'message'   => 'No se encuentran los datos para crear el País.',
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
     * Valida los datos y modifica atributos de Ciudades.
     *
     * @Route("/ciudades", name="patch_ciudades")
     * @Route("/ciudades/", name="patch_ciudades_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCiudadesAction()
    {
        return array(
            // ...
        );
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
                        'message'   => 'País no encontrado.',
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
                    'message'   => 'País no encontrado.',
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
                    'message'   => 'No se encuentran los datos para crear el País.',
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
     * @Route("/ciudad/{slug}", name="patch_ciudad")
     * @Route("/ciudad/{slug}/", name="patch_ciudad_")
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
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $ciudad){
            $repo = $this->getCiudadRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($ciudad));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($ciudad, $id) !== $dato){
                        $accessor->setValue($ciudad, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($ciudad));
                    $name = $name[count($name)-1];
                    $ciudad = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $ciudad;
        }
        return $this->getJsonResponse($ciudad, $request);
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
                    'message'   => 'País no encontrado.',
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
     * Regresa formulario para Eliminar Ciudad.
     *
     * @Route("/ciudad/{slug}/remove", name="remove_ciudad")
     * @Route("/ciudad/{slug}/remove/", name="remove_ciudad_")
     * @Template()
     * @Method("GET")
     */
    public function removeCiudadAction(Request $request, $slug)
    {
        $ciudad = $this->getCiudadRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($ciudad){
            $form = $this->createDeleteForm($slug,'delete_ciudad');
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
                    'message'   => 'País no encontrado.',
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
                $em->flush();
                $rta = $ciudad;
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

    /**
     * Elimina Ciudad
     *
     * @Route("/ciudad/{slug}", name="delete_ciudad")
     * @Route("/ciudad/{slug}/", name="delete_ciudad_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCiudadAction(Request $request, $slug)
    {
        $ciudad = $this->getCiudadRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($ciudad){
            $form = $this->createDeleteForm($slug,'delete_ciudad');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $ciudad){
                $em = $this->getManager();
                $em->remove($ciudad);
                $em->flush();
                $rta = $ciudad;
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
