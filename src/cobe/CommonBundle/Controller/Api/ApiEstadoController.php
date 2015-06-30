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

use cobe\CommonBundle\Entity\Estado;
use cobe\CommonBundle\Form\EstadoType;
use cobe\CommonBundle\Repository\EstadoRepository;

/**
 * API Estado Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEstadoController extends ApiController
{
    /**
     * Retorna el repositorio de Estado
     *
     * @return EstadoRepository
     */
    public function getEstadoRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Estado');
    }

    /**
     * Regresa herencias de API para Estados.
     *
     * @Route("/estados/aplicaciones", name="aplicaciones_estados")
     * @Route("/estados/aplicaciones/", name="aplicaciones_estados_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function herenciasEstadosAction(Request $request){
        $herencias = array(
            "Estado"        => "Estado",
            "Usuario"       => "Estado para un Usuario",
            "GrupoPersona"  => "Estado para una Persona en un Grupo",
            "Grupopersona"  => "Estado para una Persona en un Grupo",
            "Votacion"      => "Estado para una Votación",
            "Trabajo"       => "Estado para un Trabajo",
            "Mensaje"       => "Estado para un Mensaje",
            "Destinatario"  => "Estado para un Destinatario en un Mensaje",
            "OfertaLaboral" => "Estado para una Oferta Laboral",
            "Ofertalaboral" => "Estado para una Oferta Laboral",
            "Publicacion"   => "Estado para una Publicación",
            "Plantilla"     => "Estado para una Plantilla",
            "Archivo"       => "Estado para un Archivo",
            "Estadistica"   => "Estado para una Estadística"
        );
        return $this->getJsonResponse($herencias, $request);
    }

    /**
     * Regresa opciones de API para Estados.
     *
     * @Route("/estados/attributes", name="options_estados_validate")
     * @Route("/estados/attributes/", name="options_estados_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Estado();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Estados.
     *
     * @Route("/estados", name="options_estados")
     * @Route("/estados/", name="options_estados_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEstadosAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/estados',
                'method'        => 'GET',
                'description'   => 'Lista todos los estados.',
                'examples'       => array(
                    '/estados',
                    '/estados/',
                ),
            ),
            array(
                'route'         => '/estados/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los estados.',
                'examples'       => array(
                    '/estados/{id}',
                    '/estados/{id}/',
                ),
            ),
            array(
                'route'         => '/estados/params',
                'method'        => 'GET',
                'description'   => 'Lista los estados que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/estados/params/?estado[nombre]=Ecuador',
                    '/estados/params/?estado[descripcion]=Suramérica',
                    '/estados/params/?estado[descripcion]=Estado-Suraméricano',
                    '/estados/params/?estado[nombre]=República-Bolivariana-de-Venezuela&estado[descripcion]=suramerica',
                    '/estados/params/?estado[nombre]=republica-bolivariana-de-venezuela',
                    '/estados/params/?estado[herencia]=usuario',
                ),
            ),
            array(
                'route'         => '/estados/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los estados iniciando en el Offset.',
                'examples'       => array(
                    '/estados/o1/',
                    '/estados/o10',
                ),
            ),
            array(
                'route'         => '/estados/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los estados iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/estados/l2/',
                    '/estados/l10',
                ),
            ),
            array(
                'route'         => '/estados/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los estados iniciando en offset hasta limit.',
                'examples'       => array(
                    '/estados/o1/l2/',
                    '/estados/o10/l10',
                ),
            ),
            array(
                'route'         => '/estados/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un estado.',
                'examples'       => array(
                    '/estados/new/',
                    '/estados/new',
                ),
            ),
            array(
                'route'         => '/estados',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea estados. Puede recibir datos de varios estados.',
                'examples'       => array(
                    '/estados/',
                    '/estados',
                ),
            ),
            array(
                'route'         => '/estados/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de estado para editar.',
                'examples'       => array(
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/estados/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de estado.',
                'examples'       => array(
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estados/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de estado',
                'examples'       => array(
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estados/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar estado.',
                'examples'       => array(
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/estados/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra estado.',
                'examples'       => array(
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estados/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estados/aplicaciones',
                'method'        => 'OPTIONS',
                'description'   => 'Ver las aplicaciones de Estado.',
                'examples'       => array(
                    '/estados/aplicaciones/',
                    '/estados/aplicaciones',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_estados', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Estados.
     *
     * @Route("/estados", name="get_estados")
     * @Route("/estados/", name="get_estados_")
     * @Template()
     * @Method("GET")
     */
    public function getEstadosAction(Request $request)
    {
        $repository = $this->getEstadoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Estados
     *
     * @Route("/estados/new", name="new_estados")
     * @Route("/estados/new/", name="new_estados_")
     * @Template()
     * @Method("GET")
     */
    public function newEstadosAction(Request $request)
    {
        $type = new EstadoType($this->generateUrl('post_estados'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Estados.
     *
     * @Route("/estados", name="post_estados")
     * @Route("/estados/", name="post_estados_")
     * @Template()
     * @Method("POST")
     */
    public function postEstadosAction(Request $request)
    {
        $estado = new Estado();
        $type = new EstadoType($this->generateUrl('post_estados'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Estado.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $datos = $request->get($type->getName(), false);
            $herencias = $estado->getHerencias();
            if(isset($datos['herencia']) && is_array($herencias) && array_key_exists($datos['herencia'],$herencias)){
                $datos['herencia'] = ucfirst(strtolower($datos['herencia']));
                $estadoHerencia = $herencias[$datos['herencia']];
                $estado = new $estadoHerencia();
                $type = new EstadoType($this->generateUrl('post_estados'), 'POST', array(), $estadoHerencia);
            }
            $form = $this->getForm($type, $estado, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estado, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Estado.
     *
     * @Route("/estados/{slug}", name="get_estados_slug")
     * @Route("/estados/{slug}/", name="get_estados_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEstadoAction(Request $request, $slug)
    {
        $estado = null;
        switch($slug){
            case 'params':
                $datos = $request->get('estado', false);
                if($datos){
                    $estado = $this->getEstadoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $estado = $this->getEstadoRepository()->find($slug);
                break;
        }
        if (!$estado) {
            $estado = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Estado no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($estado, $request);
    }

    /**
     * Regresa el formulario para poder editar Estado existente.
     *
     * @Route("/estados/{slug}/edit", name="edit_estados")
     * @Route("/estados/{slug}/edit/", name="edit_estados_")
     * @Template()
     * @Method("GET")
     */
    public function editEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estado no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EstadoType($this->generateUrl('put_estados', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $estado );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Estado existente.
     *
     * @Route("/estados/{slug}", name="put_estados")
     * @Route("/estados/{slug}/", name="put_estados_")
     * @Template()
     * @Method("PUT")
     */
    public function putEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);
        $type = new EstadoType($this->generateUrl('put_estados', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Estado.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estado, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estado, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estado existente.
     *
     * @Route("/estados/{slug}", name="patch_estados")
     * @Route("/estados/{slug}/", name="patch_estados_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstadoAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);
        $type = new EstadoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Estado.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $estado){
            $repo = $this->getEstadoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($estado));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($estado, $id) !== $dato){
                            $accessor->setValue($estado, $id, $dato);
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
                                if(method_exists($estado,$set)){
                                    //$collection = new ArrayCollection($collection);
                                    $estado->$set($collection);
                                }
                                $isModify = true;
                            }
                        }else{
                            $dato = $repo->sanearDato($dato, 'guid');
                            $accessor = PropertyAccess::createPropertyAccessor();
                            $dato_ = $accessor->getValue($estado, $id);
                            if($dato && (!$dato_ || (is_object($dato_) && method_exists($dato_,'getId') && $dato_->getId() !== $dato))){
                                $association = $this->getManager()->getRepository($metadata->getAssociationTargetClass($id))->find($dato);
                                if($association && $association->getId()){
                                    $accessor->setValue($estado, $id, $association);
                                    $isModify = true;
                                }
                            }
                        }
                    }
                }
            }
            if($isModify){
                $estado = $this->captureErrorFlush($em, $estado, 'editar');
            }
            $rta = $estado;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Estados..
     *
     * @Route("/estados/{slug}/remove", name="remove_estados")
     * @Route("/estados/{slug}/remove/", name="remove_estados_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstadosAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estado no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estado){
            $form = $this->createDeleteForm($slug,'delete_estados');
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
     * Elimina Estados
     *
     * @Route("/estados/{slug}", name="delete_estados")
     * @Route("/estados/{slug}/", name="delete_estados_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstadosAction(Request $request, $slug)
    {
        $estado = $this->getEstadoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estado no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estado){
            $form = $this->createDeleteForm($slug,'delete_estados');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estado){
                $em = $this->getManager();
                $em->remove($estado);
                $estado = $this->captureErrorFlush($em, $estado, 'borrar');
                $rta = $estado;
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
