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

use cobe\UsuariosBundle\Entity\RepresentanteEmpresa;
use cobe\UsuariosBundle\Form\RepresentanteEmpresaType;
use cobe\UsuariosBundle\Repository\RepresentanteEmpresaRepository;

/**
 * API RepresentanteEmpresa Controller.
 *
 * @package cobe\UsuariosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiRepresentanteEmpresaController extends ApiController
{
    /**
     * Retorna el repositorio de RepresentanteEmpresa
     *
     * @return RepresentanteEmpresaRepository
     */
    public function getRepresentanteEmpresaRepository()
    {
        return $this->getManager()->getRepository('cobeUsuariosBundle:RepresentanteEmpresa');
    }

    /**
     * Regresa opciones de API para RepresentantesEmpresas.
     *
     * @Route("/representantesEmpresas", name="options_representantesEmpresas")
     * @Route("/representantesEmpresas/", name="options_representantesEmpresas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsRepresentantesEmpresasAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/representantesEmpresas',
                'method'        => 'GET',
                'description'   => 'Lista todos los representantesEmpresas.',
                'examples'       => array(
                    '/representantesEmpresas',
                    '/representantesEmpresas/',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos los representantesEmpresas.',
                'examples'       => array(
                    '/representantesEmpresas/{id}',
                    '/representantesEmpresas/{id}/',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/params',
                'method'        => 'GET',
                'description'   => 'Lista los representantesEmpresas que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/representantesEmpresas/params/?representanteEmpresa[nombre]=Ecuador',
                    '/representantesEmpresas/params/?representanteEmpresa[descripcion]=Suramérica',
                    '/representantesEmpresas/params/?representanteEmpresa[descripcion]=RepresentanteEmpresa-Suraméricano',
                    '/representantesEmpresas/params/?representanteEmpresa[nombre]=República-Bolivariana-de-Venezuela&representanteEmpresa[descripcion]=suramerica',
                    '/representantesEmpresas/params/?representanteEmpresa[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los representantesEmpresas iniciando en el Offset.',
                'examples'       => array(
                    '/representantesEmpresas/o1/',
                    '/representantesEmpresas/o10',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los representantesEmpresas iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/representantesEmpresas/l2/',
                    '/representantesEmpresas/l10',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los representantesEmpresas iniciando en offset hasta limit.',
                'examples'       => array(
                    '/representantesEmpresas/o1/l2/',
                    '/representantesEmpresas/o10/l10',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un representanteEmpresa.',
                'examples'       => array(
                    '/representantesEmpresas/new/',
                    '/representantesEmpresas/new',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea representantesEmpresas. Puede recibir datos de varios representantesEmpresas.',
                'examples'       => array(
                    '/representantesEmpresas/',
                    '/representantesEmpresas',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de representanteEmpresa para editar.',
                'examples'       => array(
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de representanteEmpresa.',
                'examples'       => array(
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de representanteEmpresa',
                'examples'       => array(
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar representanteEmpresa.',
                'examples'       => array(
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/representantesEmpresas/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra representanteEmpresa.',
                'examples'       => array(
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/representantesEmpresas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_representantesEmpresas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de RepresentantesEmpresas.
     *
     * @Route("/representantesEmpresas", name="get_representantesEmpresas")
     * @Route("/representantesEmpresas/", name="get_representantesEmpresas_")
     * @Template()
     * @Method("GET")
     */
    public function getRepresentantesEmpresasAction(Request $request)
    {
        $repository = $this->getRepresentanteEmpresaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear RepresentantesEmpresas
     *
     * @Route("/representantesEmpresas/new", name="new_representantesEmpresas")
     * @Route("/representantesEmpresas/new/", name="new_representantesEmpresas_")
     * @Template()
     * @Method("GET")
     */
    public function newRepresentantesEmpresasAction(Request $request)
    {
        $type = new RepresentanteEmpresaType($this->generateUrl('post_representantesEmpresas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea RepresentantesEmpresas.
     *
     * @Route("/representantesEmpresas", name="post_representantesEmpresas")
     * @Route("/representantesEmpresas/", name="post_representantesEmpresas_")
     * @Template()
     * @Method("POST")
     */
    public function postRepresentantesEmpresasAction(Request $request)
    {
        $representanteEmpresa = new RepresentanteEmpresa();
        $type = new RepresentanteEmpresaType($this->generateUrl('post_representantesEmpresas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la RepresentanteEmpresa.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $representanteEmpresa, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($representanteEmpresa, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa RepresentanteEmpresa.
     *
     * @Route("/representantesEmpresas/{slug}", name="get_representantesEmpresas_slug")
     * @Route("/representantesEmpresas/{slug}/", name="get_representantesEmpresas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getRepresentanteEmpresaAction(Request $request, $slug)
    {
        $representanteEmpresa = null;
        switch($slug){
            case 'params':
                $datos = $request->get('representanteEmpresa', false);
                if($datos){
                    $representanteEmpresa = $this->getRepresentanteEmpresaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $representanteEmpresa = $this->getRepresentanteEmpresaRepository()->find($slug);
                break;
        }
        if (!$representanteEmpresa) {
            $representanteEmpresa = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'RepresentanteEmpresa no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($representanteEmpresa, $request);
    }

    /**
     * Regresa el formulario para poder editar RepresentanteEmpresa existente.
     *
     * @Route("/representantesEmpresas/{slug}/edit", name="edit_representantesEmpresas")
     * @Route("/representantesEmpresas/{slug}/edit/", name="edit_representantesEmpresas_")
     * @Template()
     * @Method("GET")
     */
    public function editRepresentanteEmpresaAction(Request $request, $slug)
    {
        $representanteEmpresa = $this->getRepresentanteEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'RepresentanteEmpresa no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new RepresentanteEmpresaType($this->generateUrl('put_representantesEmpresas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $representanteEmpresa );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe RepresentanteEmpresa existente.
     *
     * @Route("/representantesEmpresas/{slug}", name="put_representantesEmpresas")
     * @Route("/representantesEmpresas/{slug}/", name="put_representantesEmpresas_")
     * @Template()
     * @Method("PUT")
     */
    public function putRepresentanteEmpresaAction(Request $request, $slug)
    {
        $representanteEmpresa = $this->getRepresentanteEmpresaRepository()->find($slug);
        $type = new RepresentanteEmpresaType($this->generateUrl('put_representantesEmpresas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la RepresentanteEmpresa.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $representanteEmpresa, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($representanteEmpresa, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de RepresentanteEmpresa existente.
     *
     * @Route("/representantesEmpresas/{slug}", name="patch_representantesEmpresas")
     * @Route("/representantesEmpresas/{slug}/", name="patch_representantesEmpresas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchRepresentanteEmpresaAction(Request $request, $slug)
    {
        $representanteEmpresa = $this->getRepresentanteEmpresaRepository()->find($slug);
        $type = new RepresentanteEmpresaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la RepresentanteEmpresa.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $representanteEmpresa){
            $repo = $this->getRepresentanteEmpresaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($representanteEmpresa));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($representanteEmpresa, $id) !== $dato){
                        $accessor->setValue($representanteEmpresa, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($representanteEmpresa));
                    $name = $name[count($name)-1];
                    $representanteEmpresa = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $representanteEmpresa;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar RepresentantesEmpresas..
     *
     * @Route("/representantesEmpresas/{slug}/remove", name="remove_representantesEmpresas")
     * @Route("/representantesEmpresas/{slug}/remove/", name="remove_representantesEmpresas_")
     * @Template()
     * @Method("GET")
     */
    public function removeRepresentantesEmpresasAction(Request $request, $slug)
    {
        $representanteEmpresa = $this->getRepresentanteEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'RepresentanteEmpresa no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($representanteEmpresa){
            $form = $this->createDeleteForm($slug,'delete_representantesEmpresas');
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
     * Elimina RepresentantesEmpresas
     *
     * @Route("/representantesEmpresas/{slug}", name="delete_representantesEmpresas")
     * @Route("/representantesEmpresas/{slug}/", name="delete_representantesEmpresas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteRepresentantesEmpresasAction(Request $request, $slug)
    {
        $representanteEmpresa = $this->getRepresentanteEmpresaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'RepresentanteEmpresa no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($representanteEmpresa){
            $form = $this->createDeleteForm($slug,'delete_representantesEmpresas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $representanteEmpresa){
                $em = $this->getManager();
                $em->remove($representanteEmpresa);
                $em->flush();
                $rta = $representanteEmpresa;
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
