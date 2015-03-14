<?php

namespace cobe\PaginasBundle\Controller\Api;

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

use cobe\PaginasBundle\Entity\Categoria;
use cobe\PaginasBundle\Form\CategoriaType;
use cobe\PaginasBundle\Repository\CategoriaRepository;

/**
 * API Categoria Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiCategoriaController extends ApiController
{
    /**
     * Retorna el repositorio de Categoria
     *
     * @return CategoriaRepository
     */
    public function getCategoriaRepository()
    {
        return $this->getManager()->getRepository('cobePaginasBundle:Categoria');
    }

    /**
     * Regresa opciones de API para Categorias.
     *
     * @Route("/categorias", name="options_categorias")
     * @Route("/categorias/", name="options_categorias_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsCategoriasAction(Request $request)
    {
        $opciones = array(
            '/categorias' => array(
                'route'         => '/categorias',
                'method'        => 'GET',
                'description'   => 'Lista todos los categorias.',
                'examples'       => array(
                    '/categorias',
                    '/categorias/',
                ),
            ),
            '/categorias/params' => array(
                'route'         => '/categorias/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/categorias/params/?categoria[nombre]=Ecuador',
                    '/categorias/params/?categoria[descripcion]=Suramérica',
                    '/categorias/params/?categoria[descripcion]=País-Suraméricano',
                    '/categorias/params/?categoria[nombre]=República-Bolivariana-de-Venezuela&categoria[descripcion]=suramerica',
                    '/categorias/params/?categoria[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/categorias/o{offset}/' => array(
                'route'         => '/categorias/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/categorias/o1/',
                    '/categorias/o10',
                ),
            ),
            '/categorias/l{limit}/' => array(
                'route'         => '/categorias/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/categorias/l2/',
                    '/categorias/l10',
                ),
            ),
            '/categorias/0{offset}/l{limit}' => array(
                'route'         => '/categorias/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/categorias/o1/l2/',
                    '/categorias/o10/l10',
                ),
            ),
            '/categorias/new' => array(
                'route'         => '/categorias/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/categorias/new/',
                    '/categorias/new',
                ),
            ),
            '/categorias' => array(
                'route'         => '/categorias',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/categorias/',
                    '/categorias',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_categorias', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Categorias.
     *
     * @Route("/categorias", name="get_categorias")
     * @Route("/categorias/", name="get_categorias_")
     * @Template()
     * @Method("GET")
     */
    public function getCategoriasAction(Request $request)
    {
        $repository = $this->getCategoriaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Categorias
     *
     * @Route("/categorias/new", name="new_categorias")
     * @Route("/categorias/new/", name="new_categorias_")
     * @Template()
     * @Method("GET")
     */
    public function newCategoriasAction(Request $request)
    {
        $type = new CategoriaType($this->generateUrl('post_categorias'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Categorias.
     *
     * @Route("/categorias", name="post_categorias")
     * @Route("/categorias/", name="post_categorias_")
     * @Template()
     * @Method("POST")
     */
    public function postCategoriasAction(Request $request)
    {
        $categoria = new Categoria();
        $type = new CategoriaType($this->generateUrl('post_categorias'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $categoria, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($categoria, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Categorias.
     *
     * @Route("/categorias", name="patch_categorias")
     * @Route("/categorias/", name="patch_categorias_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCategoriasAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Categoria.
     *
     * @Route("/categorias/{slug}", name="get_categorias_slug")
     * @Route("/categorias/{slug}/", name="get_categorias_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getCategoriaAction(Request $request, $slug)
    {
        $categoria = null;
        switch($slug){
            case 'params':
                $datos = $request->get('categoria', false);
                if($datos){
                    $categoria = $this->getCategoriaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $categoria = $this->getCategoriaRepository()->find($slug);
                break;
        }
        if (!$categoria) {
            $categoria = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($categoria, $request);
    }

    /**
     * Regresa el formulario para poder editar Categoria existente.
     *
     * @Route("/categorias/{slug}/edit", name="edit_categorias")
     * @Route("/categorias/{slug}/edit/", name="edit_categorias_")
     * @Template()
     * @Method("GET")
     */
    public function editCategoriaAction(Request $request, $slug)
    {
        $categoria = $this->getCategoriaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new CategoriaType($this->generateUrl('put_categorias', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $categoria );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Categoria existente.
     *
     * @Route("/categorias/{slug}", name="put_categorias")
     * @Route("/categorias/{slug}/", name="put_categorias_")
     * @Template()
     * @Method("PUT")
     */
    public function putCategoriaAction(Request $request, $slug)
    {
        $categoria = $this->getCategoriaRepository()->find($slug);
        $type = new CategoriaType($this->generateUrl('put_categorias', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $categoria, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($categoria, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Categoria existente.
     *
     * @Route("/categoria/{slug}", name="patch_categoria")
     * @Route("/categoria/{slug}/", name="patch_categoria_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCategoriaAction(Request $request, $slug)
    {
        $categoria = $this->getCategoriaRepository()->find($slug);
        $type = new CategoriaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $categoria){
            $repo = $this->getCategoriaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($categoria));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($categoria, $id) !== $dato){
                        $accessor->setValue($categoria, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($categoria));
                    $name = $name[count($name)-1];
                    $categoria = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $categoria;
        }
        return $this->getJsonResponse($categoria, $request);
    }

    /**
     * Regresa formulario para Eliminar Categorias..
     *
     * @Route("/categorias/{slug}/remove", name="remove_categorias")
     * @Route("/categorias/{slug}/remove/", name="remove_categorias_")
     * @Template()
     * @Method("GET")
     */
    public function removeCategoriasAction(Request $request, $slug)
    {
        $categoria = $this->getCategoriaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($categoria){
            $form = $this->createDeleteForm($slug,'delete_categorias');
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
     * Regresa formulario para Eliminar Categoria.
     *
     * @Route("/categoria/{slug}/remove", name="remove_categoria")
     * @Route("/categoria/{slug}/remove/", name="remove_categoria_")
     * @Template()
     * @Method("GET")
     */
    public function removeCategoriaAction(Request $request, $slug)
    {
        $categoria = $this->getCategoriaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($categoria){
            $form = $this->createDeleteForm($slug,'delete_categoria');
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
     * Elimina Categorias
     *
     * @Route("/categorias/{slug}", name="delete_categorias")
     * @Route("/categorias/{slug}/", name="delete_categorias_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCategoriasAction(Request $request, $slug)
    {
        $categoria = $this->getCategoriaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($categoria){
            $form = $this->createDeleteForm($slug,'delete_categorias');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $categoria){
                $em = $this->getManager();
                $em->remove($categoria);
                $em->flush();
                $rta = $categoria;
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
     * Elimina Categoria
     *
     * @Route("/categoria/{slug}", name="delete_categoria")
     * @Route("/categoria/{slug}/", name="delete_categoria_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCategoriaAction(Request $request, $slug)
    {
        $categoria = $this->getCategoriaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($categoria){
            $form = $this->createDeleteForm($slug,'delete_categoria');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $categoria){
                $em = $this->getManager();
                $em->remove($categoria);
                $em->flush();
                $rta = $categoria;
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