<?php

namespace cobe\MensajesBundle\Controller\Api;

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

use cobe\MensajesBundle\Entity\Mensaje;
use cobe\MensajesBundle\Form\MensajeType;
use cobe\MensajesBundle\Repository\MensajeRepository;

/**
 * API Mensaje Controller.
 *
 * @package cobe\MensajesBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiMensajeController extends ApiController
{
    /**
     * Retorna el repositorio de Mensaje
     *
     * @return MensajeRepository
     */
    public function getMensajeRepository()
    {
        return $this->getManager()->getRepository('cobeMensajesBundle:Mensaje');
    }

    /**
     * Regresa opciones de API para Mensajes.
     *
     * @Route("/mensajes/attributes", name="options_mensajes_validate")
     * @Route("/mensajes/attributes/", name="options_mensajes_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Mensaje();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa herencias de API para Mensajes.
     *
     * @Route("/mensajes/aplicaciones", name="aplicaciones_mensajes")
     * @Route("/mensajes/aplicacioens/", name="aplicaciones_mensajes_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function herenciasMensajesAction(Request $request){
        $herencias = array(
                "Mensaje"                   => "Mensaje",
                "Comentario"                => "Mensaje Comentario",
                "ComentarioUsuario"         => "Comentario a Usuario",
                "ComentarioGrupo"           => "Comentario a Grupo",
                "ComentarioArchivo"         => "Comentario a Archivo",
                "ComentarioPublicacion"     => "Comentario a Publicación",
                "ComentarioOfertaLaboral"   => "Comentario a Oferta Laboral"
        );
        return $this->getJsonResponse($herencias, $request);
    }

    /**
     * Regresa opciones de API para Mensajes.
     *
     * @Route("/mensajes", name="options_mensajes")
     * @Route("/mensajes/", name="options_mensajes_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsMensajesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/mensajes',
                'method'        => 'GET',
                'description'   => 'Lista todos los mensajes.',
                'examples'       => array(
                    '/mensajes',
                    '/mensajes/',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los mensajes.',
                'examples'       => array(
                    '/mensajes/{id}',
                    '/mensajes/{id}/',
                ),
            ),
            array(
                'route'         => '/mensajes/params',
                'method'        => 'GET',
                'description'   => 'Lista los mensajes que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/mensajes/params/?mensaje[nombre]=Ecuador',
                    '/mensajes/params/?mensaje[descripcion]=Suramérica',
                    '/mensajes/params/?mensaje[descripcion]=Mensaje-Suraméricano',
                    '/mensajes/params/?mensaje[nombre]=República-Bolivariana-de-Venezuela&mensaje[descripcion]=suramerica',
                    '/mensajes/params/?mensaje[nombre]=republica-bolivariana-de-venezuela',
                    '/mensajes/params/?mensaje[herencia]=usuario',
                ),
            ),
            array(
                'route'         => '/mensajes/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los mensajes iniciando en el Offset.',
                'examples'       => array(
                    '/mensajes/o1/',
                    '/mensajes/o10',
                ),
            ),
            array(
                'route'         => '/mensajes/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los mensajes iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/mensajes/l2/',
                    '/mensajes/l10',
                ),
            ),
            array(
                'route'         => '/mensajes/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los mensajes iniciando en offset hasta limit.',
                'examples'       => array(
                    '/mensajes/o1/l2/',
                    '/mensajes/o10/l10',
                ),
            ),
            array(
                'route'         => '/mensajes/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un mensaj.',
                'examples'       => array(
                    '/mensajes/new/',
                    '/mensajes/new',
                ),
            ),
            array(
                'route'         => '/mensajes',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea mensajes. Puede recibir datos de varios mensajes.',
                'examples'       => array(
                    '/mensajes/',
                    '/mensajes',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de mensaje para editar.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de mensaje.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de mensaje',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar mensaje.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/mensajes/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra mensaje.',
                'examples'       => array(
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/mensajes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/mensajes/aplicaciones',
                'method'        => 'OPTIONS',
                'description'   => 'Ver las aplicaciones de Mensaje.',
                'examples'       => array(
                    '/mensajes/aplicaciones/',
                    '/mensajes/aplicaciones',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_mensajes', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Mensajes.
     *
     * @Route("/mensajes", name="get_mensajes")
     * @Route("/mensajes/", name="get_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function getMensajesAction(Request $request)
    {
        $repository = $this->getMensajeRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Mensajes
     *
     * @Route("/mensajes/new", name="new_mensajes")
     * @Route("/mensajes/new/", name="new_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function newMensajesAction(Request $request)
    {
        $type = new MensajeType($this->generateUrl('post_mensajes'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Mensajes.
     *
     * @Route("/mensajes", name="post_mensajes")
     * @Route("/mensajes/", name="post_mensajes_")
     * @Template()
     * @Method("POST")
     */
    public function postMensajesAction(Request $request)
    {
        $mensaje = new Mensaje();
        $type = new MensajeType($this->generateUrl('post_mensajes'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Mensaje.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $datos = $request->get($type->getName(), false);
            $herencias = $mensaje->getHerencias();
            if(isset($datos['herencia']) && is_array($herencias) && array_key_exists($datos['herencia'],$herencias)){
                $datos['herencia'] = ucfirst(strtolower($datos['herencia']));
                $mensajeHerencia = $herencias[$datos['herencia']];
                $mensaje = new $mensajeHerencia();
                $type = new MensajeType($this->generateUrl('post_mensajes'), 'POST', array(), $mensajeHerencia);
            }
            $form = $this->getForm($type, $mensaje, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($mensaje, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Mensaje.
     *
     * @Route("/mensajes/{slug}", name="get_mensajes_slug")
     * @Route("/mensajes/{slug}/", name="get_mensajes_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getMensajeAction(Request $request, $slug)
    {
        $mensaje = null;
        switch($slug){
            case 'params':
                $datos = $request->get('mensaje', false);
                if($datos){
                    $mensaje = $this->getMensajeRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $mensaje = $this->getMensajeRepository()->find($slug);
                break;
        }
        if (!$mensaje) {
            $mensaje = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Mensaje no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($mensaje, $request);
    }

    /**
     * Regresa el formulario para poder editar Mensaje existente.
     *
     * @Route("/mensajes/{slug}/edit", name="edit_mensajes")
     * @Route("/mensajes/{slug}/edit/", name="edit_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function editMensajeAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Mensaje no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new MensajeType($this->generateUrl('put_mensajes', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $mensaje );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Mensaje existente.
     *
     * @Route("/mensajes/{slug}", name="put_mensajes")
     * @Route("/mensajes/{slug}/", name="put_mensajes_")
     * @Template()
     * @Method("PUT")
     */
    public function putMensajeAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);
        $type = new MensajeType($this->generateUrl('put_mensajes', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Mensaje.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $mensaje, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($mensaje, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Mensaje existente.
     *
     * @Route("/mensajes/{slug}", name="patch_mensajes")
     * @Route("/mensajes/{slug}/", name="patch_mensajes_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchMensajeAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);
        $type = new MensajeType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Mensaje.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $mensaje){
            $repo = $this->getMensajeRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($mensaje));
            $isModify = false;
            $noModify = array('id');
            foreach($datos as $id => $dato){
                if(!in_array($id, $noModify)){
                    if($metadata->hasField($id)){
                        $tipo = $metadata->getTypeOfField($id);
                        $dato = $repo->sanearDato($dato, $tipo);
                        $accessor = PropertyAccess::createPropertyAccessor();
                        if($accessor->getValue($mensaje, $id) !== $dato){
                            $accessor->setValue($mensaje, $id, $dato);
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
                                if(method_exists($mensaje,$set)){
                                    //$collection = new ArrayCollection($collection);
                                    $mensaje->$set($collection);
                                }
                                $isModify = true;
                            }
                        }else{
                            $dato = $repo->sanearDato($dato, 'guid');
                            $accessor = PropertyAccess::createPropertyAccessor();
                            $dato_ = $accessor->getValue($mensaje, $id);
                            if($dato && (!$dato_ || (is_object($dato_) && method_exists($dato_,'getId') && $dato_->getId() !== $dato))){
                                $association = $this->getManager()->getRepository($metadata->getAssociationTargetClass($id))->find($dato);
                                if($association && $association->getId()){
                                    $accessor->setValue($mensaje, $id, $association);
                                    $isModify = true;
                                }
                            }
                        }
                    }
                }
            }
            if($isModify){
                $mensaje = $this->captureErrorFlush($em, $mensaje, 'editar');
            }
            $rta = $mensaje;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Mensajes..
     *
     * @Route("/mensajes/{slug}/remove", name="remove_mensajes")
     * @Route("/mensajes/{slug}/remove/", name="remove_mensajes_")
     * @Template()
     * @Method("GET")
     */
    public function removeMensajesAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Mensaje no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($mensaje){
            $form = $this->createDeleteForm($slug,'delete_mensajes');
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
     * Elimina Mensajes
     *
     * @Route("/mensajes/{slug}", name="delete_mensajes")
     * @Route("/mensajes/{slug}/", name="delete_mensajes_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteMensajesAction(Request $request, $slug)
    {
        $mensaje = $this->getMensajeRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Mensaje no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($mensaje){
            $form = $this->createDeleteForm($slug,'delete_mensajes');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $mensaje){
                $em = $this->getManager();
                $em->remove($mensaje);
                $mensaje = $this->captureErrorFlush($em, $mensaje, 'borrar');
                $rta = $mensaje;
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
