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

use cobe\CommonBundle\Entity\Etiqueta;
use cobe\CommonBundle\Form\EtiquetaType;
use cobe\CommonBundle\Repository\EtiquetaRepository;

/**
 * API Etiqueta Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEtiquetaController extends ApiController
{
    /**
     * Retorna el repositorio de Etiqueta
     *
     * @return EtiquetaRepository
     */
    public function getEtiquetaRepository()
    {
        return $this->getManager()->getRepository('cobeCommonBundle:Etiqueta');
    }

    /**
     * Regresa opciones de API para Etiquetas.
     *
     * @Route("/etiquetas", name="options_etiquetas")
     * @Route("/etiquetas/", name="options_etiquetas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEtiquetasAction(Request $request)
    {
        $opciones = array(
            '/etiquetas' => array(
                'route'         => '/etiquetas',
                'method'        => 'GET',
                'description'   => 'Lista todos los etiquetas.',
                'examples'       => array(
                    '/etiquetas',
                    '/etiquetas/',
                ),
            ),
            '/etiquetas/params' => array(
                'route'         => '/etiquetas/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/etiquetas/params/?etiqueta[nombre]=Ecuador',
                    '/etiquetas/params/?etiqueta[descripcion]=Suramérica',
                    '/etiquetas/params/?etiqueta[descripcion]=País-Suraméricano',
                    '/etiquetas/params/?etiqueta[nombre]=República-Bolivariana-de-Venezuela&etiqueta[descripcion]=suramerica',
                    '/etiquetas/params/?etiqueta[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/etiquetas/o{offset}/' => array(
                'route'         => '/etiquetas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/etiquetas/o1/',
                    '/etiquetas/o10',
                ),
            ),
            '/etiquetas/l{limit}/' => array(
                'route'         => '/etiquetas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/etiquetas/l2/',
                    '/etiquetas/l10',
                ),
            ),
            '/etiquetas/0{offset}/l{limit}' => array(
                'route'         => '/etiquetas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/etiquetas/o1/l2/',
                    '/etiquetas/o10/l10',
                ),
            ),
            '/etiquetas/new' => array(
                'route'         => '/etiquetas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/etiquetas/new/',
                    '/etiquetas/new',
                ),
            ),
            '/etiquetas' => array(
                'route'         => '/etiquetas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/etiquetas/',
                    '/etiquetas',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_etiquetas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Etiquetas.
     *
     * @Route("/etiquetas", name="get_etiquetas")
     * @Route("/etiquetas/", name="get_etiquetas_")
     * @Template()
     * @Method("GET")
     */
    public function getEtiquetasAction(Request $request)
    {
        $repository = $this->getEtiquetaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Etiquetas
     *
     * @Route("/etiquetas/new", name="new_etiquetas")
     * @Route("/etiquetas/new/", name="new_etiquetas_")
     * @Template()
     * @Method("GET")
     */
    public function newEtiquetasAction(Request $request)
    {
        $type = new EtiquetaType($this->generateUrl('post_etiquetas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Etiquetas.
     *
     * @Route("/etiquetas", name="post_etiquetas")
     * @Route("/etiquetas/", name="post_etiquetas_")
     * @Template()
     * @Method("POST")
     */
    public function postEtiquetasAction(Request $request)
    {
        $etiqueta = new Etiqueta();
        $type = new EtiquetaType($this->generateUrl('post_etiquetas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $etiqueta, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($etiqueta, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Etiquetas.
     *
     * @Route("/etiquetas", name="patch_etiquetas")
     * @Route("/etiquetas/", name="patch_etiquetas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEtiquetasAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Etiqueta.
     *
     * @Route("/etiquetas/{slug}", name="get_etiquetas_slug")
     * @Route("/etiquetas/{slug}/", name="get_etiquetas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEtiquetaAction(Request $request, $slug)
    {
        $etiqueta = null;
        switch($slug){
            case 'params':
                $datos = $request->get('etiqueta', false);
                if($datos){
                    $etiqueta = $this->getEtiquetaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $etiqueta = $this->getEtiquetaRepository()->find($slug);
                break;
        }
        if (!$etiqueta) {
            $etiqueta = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($etiqueta, $request);
    }

    /**
     * Regresa el formulario para poder editar Etiqueta existente.
     *
     * @Route("/etiquetas/{slug}/edit", name="edit_etiquetas")
     * @Route("/etiquetas/{slug}/edit/", name="edit_etiquetas_")
     * @Template()
     * @Method("GET")
     */
    public function editEtiquetaAction(Request $request, $slug)
    {
        $etiqueta = $this->getEtiquetaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EtiquetaType($this->generateUrl('put_etiquetas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $etiqueta );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Etiqueta existente.
     *
     * @Route("/etiquetas/{slug}", name="put_etiquetas")
     * @Route("/etiquetas/{slug}/", name="put_etiquetas_")
     * @Template()
     * @Method("PUT")
     */
    public function putEtiquetaAction(Request $request, $slug)
    {
        $etiqueta = $this->getEtiquetaRepository()->find($slug);
        $type = new EtiquetaType($this->generateUrl('put_etiquetas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $etiqueta, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($etiqueta, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Etiqueta existente.
     *
     * @Route("/etiqueta/{slug}", name="patch_etiqueta")
     * @Route("/etiqueta/{slug}/", name="patch_etiqueta_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEtiquetaAction(Request $request, $slug)
    {
        $etiqueta = $this->getEtiquetaRepository()->find($slug);
        $type = new EtiquetaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $etiqueta){
            $repo = $this->getEtiquetaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($etiqueta));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($etiqueta, $id) !== $dato){
                        $accessor->setValue($etiqueta, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($etiqueta));
                    $name = $name[count($name)-1];
                    $etiqueta = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $etiqueta;
        }
        return $this->getJsonResponse($etiqueta, $request);
    }

    /**
     * Regresa formulario para Eliminar Etiquetas..
     *
     * @Route("/etiquetas/{slug}/remove", name="remove_etiquetas")
     * @Route("/etiquetas/{slug}/remove/", name="remove_etiquetas_")
     * @Template()
     * @Method("GET")
     */
    public function removeEtiquetasAction(Request $request, $slug)
    {
        $etiqueta = $this->getEtiquetaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($etiqueta){
            $form = $this->createDeleteForm($slug,'delete_etiquetas');
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
     * Regresa formulario para Eliminar Etiqueta.
     *
     * @Route("/etiqueta/{slug}/remove", name="remove_etiqueta")
     * @Route("/etiqueta/{slug}/remove/", name="remove_etiqueta_")
     * @Template()
     * @Method("GET")
     */
    public function removeEtiquetaAction(Request $request, $slug)
    {
        $etiqueta = $this->getEtiquetaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($etiqueta){
            $form = $this->createDeleteForm($slug,'delete_etiqueta');
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
     * Elimina Etiquetas
     *
     * @Route("/etiquetas/{slug}", name="delete_etiquetas")
     * @Route("/etiquetas/{slug}/", name="delete_etiquetas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEtiquetasAction(Request $request, $slug)
    {
        $etiqueta = $this->getEtiquetaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($etiqueta){
            $form = $this->createDeleteForm($slug,'delete_etiquetas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $etiqueta){
                $em = $this->getManager();
                $em->remove($etiqueta);
                $em->flush();
                $rta = $etiqueta;
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
     * Elimina Etiqueta
     *
     * @Route("/etiqueta/{slug}", name="delete_etiqueta")
     * @Route("/etiqueta/{slug}/", name="delete_etiqueta_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEtiquetaAction(Request $request, $slug)
    {
        $etiqueta = $this->getEtiquetaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($etiqueta){
            $form = $this->createDeleteForm($slug,'delete_etiqueta');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $etiqueta){
                $em = $this->getManager();
                $em->remove($etiqueta);
                $em->flush();
                $rta = $etiqueta;
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
