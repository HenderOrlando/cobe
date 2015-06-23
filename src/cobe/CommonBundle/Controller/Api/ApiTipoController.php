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

use cobe\CommonBundle\Entity\Tipo;
use cobe\CommonBundle\Form\TipoType;
use cobe\CommonBundle\Repository\TipoRepository;

/**
 * API Tipo Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiTipoController extends ApiController
{
    /**
     * Retorna el repositorio de Tipo
     *
     * @return TipoRepository
     */
    public function getTipoRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Tipo');
    }

    /**
     * Regresa herencias de API para Tipos.
     *
     * @Route("/tipos/aplicaciones", name="aplicaciones_tipos")
     * @Route("/tipos/aplicaciones/", name="aplicaciones_tipos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function herenciasTiposAction(Request $request){
        $herencias = array(
            "Tipo"                  => "Tipo",
            "Historial"             => "Tipo para un Historial",
            "Estudio"               => "Tipo para un Estudio",
            "Recomendacion"         => "Tipo para una Recomendación",
            "Reconocimiento"        => "Tipo para un Reconocimiento",
            "Proyecto"              => "Tipo para un Proyecto",
            "OfertaLaboral"         => "Tipo para una Oferta Laboral",
            "Publicacion"           => "Tipo para una Publicación",
            "VotacionPublicacion"   => "Tipo para una Votación en una Publicación",
            "Archivo"               => "Tipo para un Archivo",
            "Estadistica"           => "Tipo para una Estadística",
            "Plantilla"             => "Tipo para una Plantilla"
        );
        return $this->getJsonResponse($herencias, $request);
    }

    /**
     * Regresa opciones de API para Tipos.
     *
     * @Route("/tipos/attributes", name="options_tipos_validate")
     * @Route("/tipos/attributes/", name="options_tipos_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Tipo();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Tipos.
     *
     * @Route("/tipos", name="options_tipos")
     * @Route("/tipos/", name="options_tipos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsTiposAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/tipos',
                'method'        => 'GET',
                'description'   => 'Lista todos los tipos.',
                'examples'       => array(
                    '/tipos',
                    '/tipos/',
                ),
            ),
            array(
                'route'         => '/tipos/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los tipos.',
                'examples'       => array(
                    '/tipos/{id}',
                    '/tipos/{id}/',
                ),
            ),
            array(
                'route'         => '/tipos/params',
                'method'        => 'GET',
                'description'   => 'Lista los tipos que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/tipos/params/?tipo[nombre]=Ecuador',
                    '/tipos/params/?tipo[descripcion]=Suramérica',
                    '/tipos/params/?tipo[descripcion]=Tipo-Suraméricano',
                    '/tipos/params/?tipo[nombre]=República-Bolivariana-de-Venezuela&tipo[descripcion]=suramerica',
                    '/tipos/params/?tipo[nombre]=republica-bolivariana-de-venezuela',
                    '/tipos/params/?tipo[herencia]=usuario',
                ),
            ),
            array(
                'route'         => '/tipos/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los tipos iniciando en el Offset.',
                'examples'       => array(
                    '/tipos/o1/',
                    '/tipos/o10',
                ),
            ),
            array(
                'route'         => '/tipos/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los tipos iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/tipos/l2/',
                    '/tipos/l10',
                ),
            ),
            array(
                'route'         => '/tipos/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los tipos iniciando en offset hasta limit.',
                'examples'       => array(
                    '/tipos/o1/l2/',
                    '/tipos/o10/l10',
                ),
            ),
            array(
                'route'         => '/tipos/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un tipo.',
                'examples'       => array(
                    '/tipos/new/',
                    '/tipos/new',
                ),
            ),
            array(
                'route'         => '/tipos',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea tipos. Puede recibir datos de varios tipos.',
                'examples'       => array(
                    '/tipos/',
                    '/tipos',
                ),
            ),
            array(
                'route'         => '/tipos/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de tipo para editar.',
                'examples'       => array(
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/tipos/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de tipo.',
                'examples'       => array(
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/tipos/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de tipo',
                'examples'       => array(
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/tipos/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar tipo.',
                'examples'       => array(
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/tipos/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra tipo.',
                'examples'       => array(
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/tipos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/tipos/aplicaciones',
                'method'        => 'OPTIONS',
                'description'   => 'Ver las aplicaciones de Tipo.',
                'examples'       => array(
                    '/tipos/aplicaciones/',
                    '/tipos/aplicaciones',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_tipos', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Tipos.
     *
     * @Route("/tipos", name="get_tipos")
     * @Route("/tipos/", name="get_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function getTiposAction(Request $request)
    {
        $repository = $this->getTipoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Tipos
     *
     * @Route("/tipos/new", name="new_tipos")
     * @Route("/tipos/new/", name="new_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function newTiposAction(Request $request)
    {
        $type = new TipoType($this->generateUrl('post_tipos'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Tipos.
     *
     * @Route("/tipos", name="post_tipos")
     * @Route("/tipos/", name="post_tipos_")
     * @Template()
     * @Method("POST")
     */
    public function postTiposAction(Request $request)
    {
        $tipo = new Tipo();
        $type = new TipoType($this->generateUrl('post_tipos'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Tipo.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $datos = $request->get($type->getName(), false);
            $herencias = $tipo->getHerencias();
            $datos['herencia'] = ucfirst(strtolower($datos['herencia']));
            if(isset($datos['herencia']) && is_array($herencias) && array_key_exists($datos['herencia'],$herencias)){
                $tipoHerencia = $herencias[$datos['herencia']];
                $tipo = new $tipoHerencia();
                $type = new TipoType($this->generateUrl('post_tipos'), 'POST', array(), $tipoHerencia);
            }
            $form = $this->getForm($type, $tipo, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($tipo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Tipo.
     *
     * @Route("/tipos/{slug}", name="get_tipos_slug")
     * @Route("/tipos/{slug}/", name="get_tipos_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getTipoAction(Request $request, $slug)
    {
        $tipo = null;
        switch($slug){
            case 'params':
                $datos = $request->get('tipo', false);
                if($datos){
                    $tipo = $this->getTipoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $tipo = $this->getTipoRepository()->find($slug);
                break;
        }
        if (!$tipo) {
            $tipo = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Tipo no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($tipo, $request);
    }

    /**
     * Regresa el formulario para poder editar Tipo existente.
     *
     * @Route("/tipos/{slug}/edit", name="edit_tipos")
     * @Route("/tipos/{slug}/edit/", name="edit_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function editTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Tipo no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new TipoType($this->generateUrl('put_tipos', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $tipo );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Tipo existente.
     *
     * @Route("/tipos/{slug}", name="put_tipos")
     * @Route("/tipos/{slug}/", name="put_tipos_")
     * @Template()
     * @Method("PUT")
     */
    public function putTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);
        $type = new TipoType($this->generateUrl('put_tipos', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Tipo.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $tipo, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($tipo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Tipo existente.
     *
     * @Route("/tipos/{slug}", name="patch_tipos")
     * @Route("/tipos/{slug}/", name="patch_tipos_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchTipoAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);
        $type = new TipoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Tipo.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $tipo){
            $repo = $this->getTipoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($tipo));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($tipo, $id) !== $dato){
                        $accessor->setValue($tipo, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                $tipo = $this->captureErrorFlush($em, $tipo, 'editar');
            }
            $rta = $tipo;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Tipos..
     *
     * @Route("/tipos/{slug}/remove", name="remove_tipos")
     * @Route("/tipos/{slug}/remove/", name="remove_tipos_")
     * @Template()
     * @Method("GET")
     */
    public function removeTiposAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Tipo no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($tipo){
            $form = $this->createDeleteForm($slug,'delete_tipos');
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
     * Elimina Tipos
     *
     * @Route("/tipos/{slug}", name="delete_tipos")
     * @Route("/tipos/{slug}/", name="delete_tipos_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteTiposAction(Request $request, $slug)
    {
        $tipo = $this->getTipoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Tipo no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($tipo){
            $form = $this->createDeleteForm($slug,'delete_tipos');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $tipo){
                $em = $this->getManager();
                $em->remove($tipo);
                $tipo = $this->captureErrorFlush($em, $tipo, 'borrar');
                $rta = $tipo;
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
