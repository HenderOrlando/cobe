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

use cobe\CommonBundle\Entity\Rol;
use cobe\CommonBundle\Form\RolType;
use cobe\CommonBundle\Repository\RolRepository;

/**
 * API Rol Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiRolController extends ApiController
{
    /**
     * Retorna el repositorio de Rol
     *
     * @return RolRepository
     */
    public function getRolRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Rol');
    }

    /**
     * Regresa opciones de API para Roles.
     *
     * @Route("/roles", name="options_roles")
     * @Route("/roles/", name="options_roles_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsRolesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/roles',
                'method'        => 'GET',
                'description'   => 'Lista todos los roles.',
                'examples'       => array(
                    '/roles',
                    '/roles/',
                ),
            ),
            array(
                'route'         => '/roles/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los roles.',
                'examples'       => array(
                    '/roles/{id}',
                    '/roles/{id}/',
                ),
            ),
            array(
                'route'         => '/roles/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/roles/params/?rol[nombre]=Ecuador',
                    '/roles/params/?rol[descripcion]=Suramérica',
                    '/roles/params/?rol[descripcion]=País-Suraméricano',
                    '/roles/params/?rol[nombre]=República-Bolivariana-de-Venezuela&rol[descripcion]=suramerica',
                    '/roles/params/?rol[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/roles/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/roles/o1/',
                    '/roles/o10',
                ),
            ),
            array(
                'route'         => '/roles/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/roles/l2/',
                    '/roles/l10',
                ),
            ),
            array(
                'route'         => '/roles/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/roles/o1/l2/',
                    '/roles/o10/l10',
                ),
            ),
            array(
                'route'         => '/roles/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/roles/new/',
                    '/roles/new',
                ),
            ),
            array(
                'route'         => '/roles',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/roles/',
                    '/roles',
                ),
            ),
            array(
                'route'         => '/roles/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de rol para editar.',
                'examples'       => array(
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/roles/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los etributos de rol.',
                'examples'       => array(
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/roles/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de rol',
                'examples'       => array(
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/roles/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar rol.',
                'examples'       => array(
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/roles/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra rol.',
                'examples'       => array(
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/roles/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_roles', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Roles.
     *
     * @Route("/roles", name="get_roles")
     * @Route("/roles/", name="get_roles_")
     * @Template()
     * @Method("GET")
     */
    public function getRolesAction(Request $request)
    {
        $repository = $this->getRolRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Roles
     *
     * @Route("/roles/new", name="new_roles")
     * @Route("/roles/new/", name="new_roles_")
     * @Template()
     * @Method("GET")
     */
    public function newRolesAction(Request $request)
    {
        $type = new RolType($this->generateUrl('post_roles'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Roles.
     *
     * @Route("/roles", name="post_roles")
     * @Route("/roles/", name="post_roles_")
     * @Template()
     * @Method("POST")
     */
    public function postRolesAction(Request $request)
    {
        $rol = new Rol();
        $type = new RolType($this->generateUrl('post_roles'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $rol, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($rol, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Rol.
     *
     * @Route("/roles/{slug}", name="get_roles_slug")
     * @Route("/roles/{slug}/", name="get_roles_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getRolAction(Request $request, $slug)
    {
        $rol = null;
        switch($slug){
            case 'params':
                $datos = $request->get('rol', false);
                if($datos){
                    $rol = $this->getRolRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $rol = $this->getRolRepository()->find($slug);
                break;
        }
        if (!$rol) {
            $rol = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($rol, $request);
    }

    /**
     * Regresa el formulario para poder editar Rol existente.
     *
     * @Route("/roles/{slug}/edit", name="edit_roles")
     * @Route("/roles/{slug}/edit/", name="edit_roles_")
     * @Template()
     * @Method("GET")
     */
    public function editRolAction(Request $request, $slug)
    {
        $rol = $this->getRolRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new RolType($this->generateUrl('put_roles', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $rol );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Rol existente.
     *
     * @Route("/roles/{slug}", name="put_roles")
     * @Route("/roles/{slug}/", name="put_roles_")
     * @Template()
     * @Method("PUT")
     */
    public function putRolAction(Request $request, $slug)
    {
        $rol = $this->getRolRepository()->find($slug);
        $type = new RolType($this->generateUrl('put_roles', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $rol, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($rol, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Rol existente.
     *
     * @Route("/roles/{slug}", name="patch_roles")
     * @Route("/roles/{slug}/", name="patch_roles_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchRolAction(Request $request, $slug)
    {
        $rol = $this->getRolRepository()->find($slug);
        $type = new RolType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $rol){
            $repo = $this->getRolRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($rol));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($rol, $id) !== $dato){
                        $accessor->setValue($rol, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($rol));
                    $name = $name[count($name)-1];
                    $rol = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $rol;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Roles..
     *
     * @Route("/roles/{slug}/remove", name="remove_roles")
     * @Route("/roles/{slug}/remove/", name="remove_roles_")
     * @Template()
     * @Method("GET")
     */
    public function removeRolesAction(Request $request, $slug)
    {
        $rol = $this->getRolRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($rol){
            $form = $this->createDeleteForm($slug,'delete_roles');
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
     * Elimina Roles
     *
     * @Route("/roles/{slug}", name="delete_roles")
     * @Route("/roles/{slug}/", name="delete_roles_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteRolesAction(Request $request, $slug)
    {
        $rol = $this->getRolRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($rol){
            $form = $this->createDeleteForm($slug,'delete_roles');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $rol){
                $em = $this->getManager();
                $em->remove($rol);
                $em->flush();
                $rta = $rol;
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
