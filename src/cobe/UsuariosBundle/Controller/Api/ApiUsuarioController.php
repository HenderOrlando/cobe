<?php

namespace cobe\UsuariosBundle\Controller\Api;

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

use cobe\UsuariosBundle\Entity\Usuario;
use cobe\UsuariosBundle\Form\UsuarioType;
use cobe\UsuariosBundle\Repository\UsuarioRepository;

/**
 * API Usuario Controller.
 *
 * @package cobe\UsuariosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiUsuarioController extends ApiController
{
    /**
     * Retorna el repositorio de Usuario
     *
     * @return UsuarioRepository
     */
    public function getUsuarioRepository()
    {
        return $this->getManager()->getRepository('cobeUsuariosBundle:Usuario');
    }

    /**
     * Regresa herencias de API para Usuarios.
     *
     * @Route("/usuarios/aplicaciones", name="aplicaciones_usuarios")
     * @Route("/usuarios/aplicaciones/", name="aplicaciones_usuarios_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function herenciasUsuariosAction(Request $request){
        $herencias = array(
            "Usuario"=>"Usuario de la Plataforma",
            "Persona"=>"Usuario de la Plataforma con datos personales"
        );
        return $this->getJsonResponse($herencias, $request);
    }

    /**
     * Regresa opciones de API para Usuarios.
     *
     * @Route("/usuarios", name="options_usuarios")
     * @Route("/usuarios/", name="options_usuarios_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsUsuariosAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/usuarios',
                'method'        => 'GET',
                'description'   => 'Lista todos los usuarios.',
                'examples'       => array(
                    '/usuarios',
                    '/usuarios/',
                ),
            ),
            array(
                'route'         => '/usuarios/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los usuarios.',
                'examples'       => array(
                    '/usuarios/{id}',
                    '/usuarios/{id}/',
                ),
            ),
            array(
                'route'         => '/usuarios/params',
                'method'        => 'GET',
                'description'   => 'Lista los usuarios que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/usuarios/params/?usuario[nombre]=Ecuador',
                    '/usuarios/params/?usuario[descripcion]=Suramérica',
                    '/usuarios/params/?usuario[descripcion]=Usuario-Suraméricano',
                    '/usuarios/params/?usuario[nombre]=República-Bolivariana-de-Venezuela&usuario[descripcion]=suramerica',
                    '/usuarios/params/?usuario[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/usuarios/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los usuarios iniciando en el Offset.',
                'examples'       => array(
                    '/usuarios/o1/',
                    '/usuarios/o10',
                ),
            ),
            array(
                'route'         => '/usuarios/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los usuarios iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/usuarios/l2/',
                    '/usuarios/l10',
                ),
            ),
            array(
                'route'         => '/usuarios/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los usuarios iniciando en offset hasta limit.',
                'examples'       => array(
                    '/usuarios/o1/l2/',
                    '/usuarios/o10/l10',
                ),
            ),
            array(
                'route'         => '/usuarios/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un usuario.',
                'examples'       => array(
                    '/usuarios/new/',
                    '/usuarios/new',
                ),
            ),
            array(
                'route'         => '/usuarios',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea usuarios. Puede recibir datos de varios usuarios.',
                'examples'       => array(
                    '/usuarios/',
                    '/usuarios',
                ),
            ),
            array(
                'route'         => '/usuarios/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de usuario para editar.',
                'examples'       => array(
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/usuarios/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de usuario.',
                'examples'       => array(
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/usuarios/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de usuario',
                'examples'       => array(
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/usuarios/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar usuario.',
                'examples'       => array(
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/usuarios/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra usuario.',
                'examples'       => array(
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/usuarios/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/usuarios/aplicaciones',
                'method'        => 'OPTIONS',
                'description'   => 'Ver las aplicaciones de Usuario.',
                'examples'       => array(
                    '/usuarios/aplicaciones/',
                    '/usuarios/aplicaciones',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_usuarios', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Valida la clave y el email de un Usuario
     *
     * @Route("/validaUsuarios", name="valida_usuarios")
     * @Route("/validaUsuarios/", name="valida_usuarios_")
     * @Template()
     * @Method("GET")
     */
    public function getValidaUsuarioAction(Request $request)
    {
        $email = $request->get('email');
        $username = $request->get('username');
        $clave = $request->get('clave');

        $repository = $this->getUsuarioRepository();
        $valido = $repository->validaUsuario($clave, $email, $username);
        if(!$valido){
            $list = array(
                'errors' => array(
                    '400' => array(
                        'message'   => 'El Usuario no es válido.',
                        'code'      => '400',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa la lista de Usuarios.
     *
     * @Route("/usuarios", name="get_usuarios")
     * @Route("/usuarios/", name="get_usuarios_")
     * @Template()
     * @Method("GET")
     */
    public function getUsuariosAction(Request $request)
    {
        $repository = $this->getUsuarioRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Usuarios
     *
     * @Route("/usuarios/new", name="new_usuarios")
     * @Route("/usuarios/new/", name="new_usuarios_")
     * @Template()
     * @Method("GET")
     */
    public function newUsuariosAction(Request $request)
    {
        $type = new UsuarioType($this->generateUrl('post_usuarios'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Usuarios.
     *
     * @Route("/usuarios", name="post_usuarios")
     * @Route("/usuarios/", name="post_usuarios_")
     * @Template()
     * @Method("POST")
     */
    public function postUsuariosAction(Request $request)
    {
        $usuario = new Usuario();
        $type = new UsuarioType($this->generateUrl('post_usuarios'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Usuario.',
                    'code'      => '400',
                ),
            ),
        );
        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $usuario, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($usuario, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Usuario.
     *
     * @Route("/usuarios/{slug}", name="get_usuarios_slug")
     * @Route("/usuarios/{slug}/", name="get_usuarios_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getUsuarioAction(Request $request, $slug)
    {
        $usuario = null;
        switch($slug){
            case 'params':
                $datos = $request->get('usuario', false);
                if($datos){
                    $usuario = $this->getUsuarioRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $usuario = $this->getUsuarioRepository()->find($slug);
                break;
        }
        if (!$usuario) {
            $usuario = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Usuario no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($usuario, $request);
    }

    /**
     * Regresa el formulario para poder editar Usuario existente.
     *
     * @Route("/usuarios/{slug}/edit", name="edit_usuarios")
     * @Route("/usuarios/{slug}/edit/", name="edit_usuarios_")
     * @Template()
     * @Method("GET")
     */
    public function editUsuarioAction(Request $request, $slug)
    {
        $usuario = $this->getUsuarioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Usuario no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new UsuarioType($this->generateUrl('put_usuarios', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $usuario );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Usuario existente.
     *
     * @Route("/usuarios/{slug}", name="put_usuarios")
     * @Route("/usuarios/{slug}/", name="put_usuarios_")
     * @Template()
     * @Method("PUT")
     */
    public function putUsuarioAction(Request $request, $slug)
    {
        $usuario = $this->getUsuarioRepository()->find($slug);
        $type = new UsuarioType($this->generateUrl('put_usuarios', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Usuario.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $usuario, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($usuario, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Usuario existente.
     *
     * @Route("/usuarios/{slug}", name="patch_usuarios")
     * @Route("/usuarios/{slug}/", name="patch_usuarios_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchUsuarioAction(Request $request, $slug)
    {
        $usuario = $this->getUsuarioRepository()->find($slug);
        $type = new UsuarioType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el Usuario.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $usuario){
            $repo = $this->getUsuarioRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($usuario));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($usuario, $id) !== $dato){
                        $accessor->setValue($usuario, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($usuario));
                    $name = $name[count($name)-1];
                    $usuario = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $usuario;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Usuarios..
     *
     * @Route("/usuarios/{slug}/remove", name="remove_usuarios")
     * @Route("/usuarios/{slug}/remove/", name="remove_usuarios_")
     * @Template()
     * @Method("GET")
     */
    public function removeUsuariosAction(Request $request, $slug)
    {
        $usuario = $this->getUsuarioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Usuario no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($usuario){
            $form = $this->createDeleteForm($slug,'delete_usuarios');
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
     * Elimina Usuarios
     *
     * @Route("/usuarios/{slug}", name="delete_usuarios")
     * @Route("/usuarios/{slug}/", name="delete_usuarios_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteUsuariosAction(Request $request, $slug)
    {
        $usuario = $this->getUsuarioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Usuario no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($usuario){
            $form = $this->createDeleteForm($slug,'delete_usuarios');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $usuario){
                $em = $this->getManager();
                $em->remove($usuario);
                $em->flush();
                $rta = $usuario;
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
