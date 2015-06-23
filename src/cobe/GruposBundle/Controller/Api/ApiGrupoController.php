<?php

namespace cobe\GruposBundle\Controller\Api;

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

use cobe\GruposBundle\Entity\Grupo;
use cobe\GruposBundle\Form\GrupoType;
use cobe\GruposBundle\Repository\GrupoRepository;

/**
 * API Grupo Controller.
 *
 * @package cobe\GruposBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiGrupoController extends ApiController
{
    /**
     * Retorna el repositorio de Grupo
     *
     * @return GrupoRepository
     */
    public function getGrupoRepository()
    {
        return $this->getManager()->getRepository('cobeGruposBundle:Grupo');
    }

    /**
     * Regresa opciones de API para Grupos.
     *
     * @Route("/grupos/attributes", name="options_grupos_validate")
     * @Route("/grupos/attributes/", name="options_grupos_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Grupo();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa herencias de API para Grupos.
     *
     * @Route("/grupos/aplicaciones", name="aplicaciones_grupos")
     * @Route("/grupos/aplicaciones/", name="aplicaciones_grupos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function herenciasGruposAction(Request $request){
        $herencias = array(
            "Grupo"     => "Grupo",
            "Editor"    => "Grupo Editor de una Publicación"
        );
        return $this->getJsonResponse($herencias, $request);
    }

    /**
     * Regresa opciones de API para Grupos.
     *
     * @Route("/grupos", name="options_grupos")
     * @Route("/grupos/", name="options_grupos_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsGruposAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/grupos',
                'method'        => 'GET',
                'description'   => 'Lista todos los grupos.',
                'examples'       => array(
                    '/grupos',
                    '/grupos/',
                ),
            ),
            array(
                'route'         => '/grupos/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los grupos.',
                'examples'       => array(
                    '/grupos/{id}',
                    '/grupos/{id}/',
                ),
            ),
            array(
                'route'         => '/grupos/params',
                'method'        => 'GET',
                'description'   => 'Lista los grupos que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/grupos/params/?grupo[nombre]=Ecuador',
                    '/grupos/params/?grupo[descripcion]=Suramérica',
                    '/grupos/params/?grupo[descripcion]=Grupo-Suraméricano',
                    '/grupos/params/?grupo[nombre]=República-Bolivariana-de-Venezuela&grupo[descripcion]=suramerica',
                    '/grupos/params/?grupo[nombre]=republica-bolivariana-de-venezuela',
                    '/grupos/params/?grupo[herencia]=usuario',
                ),
            ),
            array(
                'route'         => '/grupos/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los grupos iniciando en el Offset.',
                'examples'       => array(
                    '/grupos/o1/',
                    '/grupos/o10',
                ),
            ),
            array(
                'route'         => '/grupos/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los grupos iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/grupos/l2/',
                    '/grupos/l10',
                ),
            ),
            array(
                'route'         => '/grupos/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los grupos iniciando en offset hasta limit.',
                'examples'       => array(
                    '/grupos/o1/l2/',
                    '/grupos/o10/l10',
                ),
            ),
            array(
                'route'         => '/grupos/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un grupo.',
                'examples'       => array(
                    '/grupos/new/',
                    '/grupos/new',
                ),
            ),
            array(
                'route'         => '/grupos',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea grupos. Puede recibir datos de varios grupos.',
                'examples'       => array(
                    '/grupos/',
                    '/grupos',
                ),
            ),
            array(
                'route'         => '/grupos/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de grupo para editar.',
                'examples'       => array(
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/grupos/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de grupo.',
                'examples'       => array(
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/grupos/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de grupo',
                'examples'       => array(
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/grupos/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar grupo.',
                'examples'       => array(
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/grupos/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra grupo.',
                'examples'       => array(
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/grupos/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/grupos/aplicaciones',
                'method'        => 'OPTIONS',
                'description'   => 'Ver las aplicaciones de Grupo.',
                'examples'       => array(
                    '/grupos/aplicaciones/',
                    '/grupos/aplicaciones',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_grupos', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Grupos.
     *
     * @Route("/grupos", name="get_grupos")
     * @Route("/grupos/", name="get_grupos_")
     * @Template()
     * @Method("GET")
     */
    public function getGruposAction(Request $request)
    {
        $repository = $this->getGrupoRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Grupos
     *
     * @Route("/grupos/new", name="new_grupos")
     * @Route("/grupos/new/", name="new_grupos_")
     * @Template()
     * @Method("GET")
     */
    public function newGruposAction(Request $request)
    {
        $type = new GrupoType($this->generateUrl('post_grupos'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Grupos.
     *
     * @Route("/grupos", name="post_grupos")
     * @Route("/grupos/", name="post_grupos_")
     * @Template()
     * @Method("POST")
     */
    public function postGruposAction(Request $request)
    {
        $grupo = new Grupo();
        $type = new GrupoType($this->generateUrl('post_grupos'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Grupo.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $datos = $request->get($type->getName(), false);
            $herencias = $grupo->getHerencias();
            $datos['herencia'] = ucfirst(strtolower($datos['herencia']));
            if(isset($datos['herencia']) && is_array($herencias) && array_key_exists($datos['herencia'],$herencias)){
                $grupoHerencia = $herencias[$datos['herencia']];
                $grupo = new $grupoHerencia();
                $type = new GrupoType($this->generateUrl('post_grupos'), 'POST', array(), $grupoHerencia);
            }
            $form = $this->getForm($type, $grupo, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($grupo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Grupo.
     *
     * @Route("/grupos/{slug}", name="get_grupos_slug")
     * @Route("/grupos/{slug}/", name="get_grupos_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getGrupoAction(Request $request, $slug)
    {
        $grupo = null;
        switch($slug){
            case 'params':
                $datos = $request->get('grupo', false);
                if($datos){
                    $grupo = $this->getGrupoRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $grupo = $this->getGrupoRepository()->find($slug);
                break;
        }
        if (!$grupo) {
            $grupo = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Grupo no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($grupo, $request);
    }

    /**
     * Regresa el formulario para poder editar Grupo existente.
     *
     * @Route("/grupos/{slug}/edit", name="edit_grupos")
     * @Route("/grupos/{slug}/edit/", name="edit_grupos_")
     * @Template()
     * @Method("GET")
     */
    public function editGrupoAction(Request $request, $slug)
    {
        $grupo = $this->getGrupoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Grupo no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new GrupoType($this->generateUrl('put_grupos', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $grupo );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Grupo existente.
     *
     * @Route("/grupos/{slug}", name="put_grupos")
     * @Route("/grupos/{slug}/", name="put_grupos_")
     * @Template()
     * @Method("PUT")
     */
    public function putGrupoAction(Request $request, $slug)
    {
        $grupo = $this->getGrupoRepository()->find($slug);
        $type = new GrupoType($this->generateUrl('put_grupos', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Grupo.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $grupo, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($grupo, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Grupo existente.
     *
     * @Route("/grupos/{slug}", name="patch_grupos")
     * @Route("/grupos/{slug}/", name="patch_grupos_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchGrupoAction(Request $request, $slug)
    {
        $grupo = $this->getGrupoRepository()->find($slug);
        $type = new GrupoType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Grupo.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $grupo){
            $repo = $this->getGrupoRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($grupo));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($grupo, $id) !== $dato){
                        $accessor->setValue($grupo, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                $grupo = $this->captureErrorFlush($em, $grupo, 'editar');
            }
            $rta = $grupo;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Grupos..
     *
     * @Route("/grupos/{slug}/remove", name="remove_grupos")
     * @Route("/grupos/{slug}/remove/", name="remove_grupos_")
     * @Template()
     * @Method("GET")
     */
    public function removeGruposAction(Request $request, $slug)
    {
        $grupo = $this->getGrupoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Grupo no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($grupo){
            $form = $this->createDeleteForm($slug,'delete_grupos');
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
     * Elimina Grupos
     *
     * @Route("/grupos/{slug}", name="delete_grupos")
     * @Route("/grupos/{slug}/", name="delete_grupos_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteGruposAction(Request $request, $slug)
    {
        $grupo = $this->getGrupoRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Grupo no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($grupo){
            $form = $this->createDeleteForm($slug,'delete_grupos');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $grupo){
                $em = $this->getManager();
                $em->remove($grupo);
                $grupo = $this->captureErrorFlush($em, $grupo, 'borrar');
                $rta = $grupo;
                if(!$grupo){
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
