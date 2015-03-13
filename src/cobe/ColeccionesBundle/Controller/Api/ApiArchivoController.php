<?php

namespace cobe\ColeccionesBundle\Controller\Api;

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

use cobe\ColeccionesBundle\Entity\Archivo;
use cobe\ColeccionesBundle\Form\ArchivoType;
use cobe\ColeccionesBundle\Repository\ArchivoRepository;

/**
 * API Archivo Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiArchivoController extends ApiController
{
    /**
     * Retorna el repositorio de Archivo
     *
     * @return ArchivoRepository
     */
    public function getArchivoRepository()
    {
        return $this->getManager()->getRepository('cobeColeccionesBundle:Archivo');
    }

    /**
     * Regresa opciones de API para Archivos.
     *
     * @Route("/archivos", name="options_archivos")
     * @Route("/archivos/", name="options_archivos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsArchivosAction(Request $request)
    {
        $opciones = array(
            '/archivos' => array(
                'route'         => '/archivos',
                'method'        => 'GET',
                'description'   => 'Lista todos los archivos.',
                'examples'       => array(
                    '/archivos',
                    '/archivos/',
                ),
            ),
            '/archivos/params' => array(
                'route'         => '/archivos/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/archivos/params/?archivo[nombre]=Ecuador',
                    '/archivos/params/?archivo[descripcion]=Suramérica',
                    '/archivos/params/?archivo[descripcion]=País-Suraméricano',
                    '/archivos/params/?archivo[nombre]=República-Bolivariana-de-Venezuela&archivo[descripcion]=suramerica',
                    '/archivos/params/?archivo[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/archivos/o{offset}/' => array(
                'route'         => '/archivos/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/archivos/o1/',
                    '/archivos/o10',
                ),
            ),
            '/archivos/l{limit}/' => array(
                'route'         => '/archivos/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/archivos/l2/',
                    '/archivos/l10',
                ),
            ),
            '/archivos/0{offset}/l{limit}' => array(
                'route'         => '/archivos/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/archivos/o1/l2/',
                    '/archivos/o10/l10',
                ),
            ),
            '/archivos/new' => array(
                'route'         => '/archivos/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/archivos/new/',
                    '/archivos/new',
                ),
            ),
            '/archivos' => array(
                'route'         => '/archivos',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/archivos/',
                    '/archivos',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_archivos', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Archivos.
     *
     * @Route("/archivos", name="get_archivos")
     * @Route("/archivos/", name="get_archivos_")
     * @Template()
     * @Method("GET")
     */
    public function getArchivosAction(Request $request)
    {
        $repository = $this->getArchivoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Archivos
     *
     * @Route("/archivos/new", name="new_archivos")
     * @Route("/archivos/new/", name="new_archivos_")
     * @Template()
     * @Method("GET")
     */
    public function newArchivosAction(Request $request)
    {
        $type = new ArchivoType($this->generateUrl('post_archivos'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Archivos.
     *
     * @Route("/archivos", name="post_archivos")
     * @Route("/archivos/", name="post_archivos_")
     * @Template()
     * @Method("POST")
     */
    public function postArchivosAction(Request $request)
    {
        $archivo = new Archivo();
        $type = new ArchivoType($this->generateUrl('post_archivos'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $archivo, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($archivo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Archivos.
     *
     * @Route("/archivos", name="patch_archivos")
     * @Route("/archivos/", name="patch_archivos_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchArchivosAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Archivo.
     *
     * @Route("/archivos/{slug}", name="get_archivos_slug")
     * @Route("/archivos/{slug}/", name="get_archivos_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getArchivoAction(Request $request, $slug)
    {
        $archivo = null;
        switch($slug){
            case 'params':
                $datos = $request->get('archivo', false);
                if($datos){
                    $archivo = $this->getArchivoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $archivo = $this->getArchivoRepository()->find($slug);
                break;
        }
        if (!$archivo) {
            $archivo = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($archivo, $request);
    }

    /**
     * Regresa el formulario para poder editar Archivo existente.
     *
     * @Route("/archivos/{slug}/edit", name="edit_archivos")
     * @Route("/archivos/{slug}/edit/", name="edit_archivos_")
     * @Template()
     * @Method("GET")
     */
    public function editArchivoAction(Request $request, $slug)
    {
        $archivo = $this->getArchivoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new ArchivoType($this->generateUrl('put_archivos', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $archivo );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Archivo existente.
     *
     * @Route("/archivos/{slug}", name="put_archivos")
     * @Route("/archivos/{slug}/", name="put_archivos_")
     * @Template()
     * @Method("PUT")
     */
    public function putArchivoAction(Request $request, $slug)
    {
        $archivo = $this->getArchivoRepository()->find($slug);
        $type = new ArchivoType($this->generateUrl('put_archivos', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $archivo, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($archivo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Archivo existente.
     *
     * @Route("/archivo/{slug}", name="patch_archivo")
     * @Route("/archivo/{slug}/", name="patch_archivo_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchArchivoAction(Request $request, $slug)
    {
        $archivo = $this->getArchivoRepository()->find($slug);
        $type = new ArchivoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $archivo){
            $repo = $this->getArchivoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($archivo));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($archivo, $id) !== $dato){
                        $accessor->setValue($archivo, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($archivo));
                    $name = $name[count($name)-1];
                    $archivo = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $archivo;
        }
        return $this->getJsonResponse($archivo, $request);
    }

    /**
     * Regresa formulario para Eliminar Archivos..
     *
     * @Route("/archivos/{slug}/remove", name="remove_archivos")
     * @Route("/archivos/{slug}/remove/", name="remove_archivos_")
     * @Template()
     * @Method("GET")
     */
    public function removeArchivosAction(Request $request, $slug)
    {
        $archivo = $this->getArchivoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($archivo){
            $form = $this->createDeleteForm($slug,'delete_archivos');
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
     * Regresa formulario para Eliminar Archivo.
     *
     * @Route("/archivo/{slug}/remove", name="remove_archivo")
     * @Route("/archivo/{slug}/remove/", name="remove_archivo_")
     * @Template()
     * @Method("GET")
     */
    public function removeArchivoAction(Request $request, $slug)
    {
        $archivo = $this->getArchivoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($archivo){
            $form = $this->createDeleteForm($slug,'delete_archivo');
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
     * Elimina Archivos
     *
     * @Route("/archivos/{slug}", name="delete_archivos")
     * @Route("/archivos/{slug}/", name="delete_archivos_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteArchivosAction(Request $request, $slug)
    {
        $archivo = $this->getArchivoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($archivo){
            $form = $this->createDeleteForm($slug,'delete_archivos');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $archivo){
                $em = $this->getManager();
                $em->remove($archivo);
                $em->flush();
                $rta = $archivo;
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
     * Elimina Archivo
     *
     * @Route("/archivo/{slug}", name="delete_archivo")
     * @Route("/archivo/{slug}/", name="delete_archivo_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteArchivoAction(Request $request, $slug)
    {
        $archivo = $this->getArchivoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($archivo){
            $form = $this->createDeleteForm($slug,'delete_archivo');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $archivo){
                $em = $this->getManager();
                $em->remove($archivo);
                $em->flush();
                $rta = $archivo;
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
