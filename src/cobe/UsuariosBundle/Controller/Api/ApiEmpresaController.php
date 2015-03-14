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

use cobe\UsuariosBundle\Entity\Empresa;
use cobe\UsuariosBundle\Form\EmpresaType;
use cobe\UsuariosBundle\Repository\EmpresaRepository;

/**
 * API Empresa Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEmpresaController extends ApiController
{
    /**
     * Retorna el repositorio de Empresa
     *
     * @return EmpresaRepository
     */
    public function getEmpresaRepository()
    {
        return $this->getManager()->getRepository('cobeUsuariosBundle:Empresa');
    }

    /**
     * Regresa opciones de API para Empresas.
     *
     * @Route("/empresas", name="options_empresas")
     * @Route("/empresas/", name="options_empresas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEmpresasAction(Request $request)
    {
        $opciones = array(
            '/empresas' => array(
                'route'         => '/empresas',
                'method'        => 'GET',
                'description'   => 'Lista todos los empresas.',
                'examples'       => array(
                    '/empresas',
                    '/empresas/',
                ),
            ),
            '/empresas/params' => array(
                'route'         => '/empresas/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/empresas/params/?empresa[nombre]=Ecuador',
                    '/empresas/params/?empresa[descripcion]=Suramérica',
                    '/empresas/params/?empresa[descripcion]=País-Suraméricano',
                    '/empresas/params/?empresa[nombre]=República-Bolivariana-de-Venezuela&empresa[descripcion]=suramerica',
                    '/empresas/params/?empresa[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/empresas/o{offset}/' => array(
                'route'         => '/empresas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/empresas/o1/',
                    '/empresas/o10',
                ),
            ),
            '/empresas/l{limit}/' => array(
                'route'         => '/empresas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/empresas/l2/',
                    '/empresas/l10',
                ),
            ),
            '/empresas/0{offset}/l{limit}' => array(
                'route'         => '/empresas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/empresas/o1/l2/',
                    '/empresas/o10/l10',
                ),
            ),
            '/empresas/new' => array(
                'route'         => '/empresas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/empresas/new/',
                    '/empresas/new',
                ),
            ),
            '/empresas' => array(
                'route'         => '/empresas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/empresas/',
                    '/empresas',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_empresas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Empresas.
     *
     * @Route("/empresas", name="get_empresas")
     * @Route("/empresas/", name="get_empresas_")
     * @Template()
     * @Method("GET")
     */
    public function getEmpresasAction(Request $request)
    {
        $repository = $this->getEmpresaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Empresas
     *
     * @Route("/empresas/new", name="new_empresas")
     * @Route("/empresas/new/", name="new_empresas_")
     * @Template()
     * @Method("GET")
     */
    public function newEmpresasAction(Request $request)
    {
        $type = new EmpresaType($this->generateUrl('post_empresas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Empresas.
     *
     * @Route("/empresas", name="post_empresas")
     * @Route("/empresas/", name="post_empresas_")
     * @Template()
     * @Method("POST")
     */
    public function postEmpresasAction(Request $request)
    {
        $empresa = new Empresa();
        $type = new EmpresaType($this->generateUrl('post_empresas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $empresa, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($empresa, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Empresas.
     *
     * @Route("/empresas", name="patch_empresas")
     * @Route("/empresas/", name="patch_empresas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEmpresasAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Empresa.
     *
     * @Route("/empresas/{slug}", name="get_empresas_slug")
     * @Route("/empresas/{slug}/", name="get_empresas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEmpresaAction(Request $request, $slug)
    {
        $empresa = null;
        switch($slug){
            case 'params':
                $datos = $request->get('empresa', false);
                if($datos){
                    $empresa = $this->getEmpresaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $empresa = $this->getEmpresaRepository()->find($slug);
                break;
        }
        if (!$empresa) {
            $empresa = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($empresa, $request);
    }

    /**
     * Regresa el formulario para poder editar Empresa existente.
     *
     * @Route("/empresas/{slug}/edit", name="edit_empresas")
     * @Route("/empresas/{slug}/edit/", name="edit_empresas_")
     * @Template()
     * @Method("GET")
     */
    public function editEmpresaAction(Request $request, $slug)
    {
        $empresa = $this->getEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EmpresaType($this->generateUrl('put_empresas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $empresa );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Empresa existente.
     *
     * @Route("/empresas/{slug}", name="put_empresas")
     * @Route("/empresas/{slug}/", name="put_empresas_")
     * @Template()
     * @Method("PUT")
     */
    public function putEmpresaAction(Request $request, $slug)
    {
        $empresa = $this->getEmpresaRepository()->find($slug);
        $type = new EmpresaType($this->generateUrl('put_empresas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $empresa, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($empresa, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Empresa existente.
     *
     * @Route("/empresa/{slug}", name="patch_empresa")
     * @Route("/empresa/{slug}/", name="patch_empresa_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEmpresaAction(Request $request, $slug)
    {
        $empresa = $this->getEmpresaRepository()->find($slug);
        $type = new EmpresaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $empresa){
            $repo = $this->getEmpresaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($empresa));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($empresa, $id) !== $dato){
                        $accessor->setValue($empresa, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($empresa));
                    $name = $name[count($name)-1];
                    $empresa = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $empresa;
        }
        return $this->getJsonResponse($empresa, $request);
    }

    /**
     * Regresa formulario para Eliminar Empresas..
     *
     * @Route("/empresas/{slug}/remove", name="remove_empresas")
     * @Route("/empresas/{slug}/remove/", name="remove_empresas_")
     * @Template()
     * @Method("GET")
     */
    public function removeEmpresasAction(Request $request, $slug)
    {
        $empresa = $this->getEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($empresa){
            $form = $this->createDeleteForm($slug,'delete_empresas');
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
     * Regresa formulario para Eliminar Empresa.
     *
     * @Route("/empresa/{slug}/remove", name="remove_empresa")
     * @Route("/empresa/{slug}/remove/", name="remove_empresa_")
     * @Template()
     * @Method("GET")
     */
    public function removeEmpresaAction(Request $request, $slug)
    {
        $empresa = $this->getEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($empresa){
            $form = $this->createDeleteForm($slug,'delete_empresa');
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
     * Elimina Empresas
     *
     * @Route("/empresas/{slug}", name="delete_empresas")
     * @Route("/empresas/{slug}/", name="delete_empresas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEmpresasAction(Request $request, $slug)
    {
        $empresa = $this->getEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($empresa){
            $form = $this->createDeleteForm($slug,'delete_empresas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $empresa){
                $em = $this->getManager();
                $em->remove($empresa);
                $em->flush();
                $rta = $empresa;
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
     * Elimina Empresa
     *
     * @Route("/empresa/{slug}", name="delete_empresa")
     * @Route("/empresa/{slug}/", name="delete_empresa_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEmpresaAction(Request $request, $slug)
    {
        $empresa = $this->getEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($empresa){
            $form = $this->createDeleteForm($slug,'delete_empresa');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $empresa){
                $em = $this->getManager();
                $em->remove($empresa);
                $em->flush();
                $rta = $empresa;
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