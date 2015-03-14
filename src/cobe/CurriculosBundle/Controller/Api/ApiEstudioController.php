<?php

namespace cobe\CurriculosBundle\Controller\Api;

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

use cobe\CurriculosBundle\Entity\Estudio;
use cobe\CurriculosBundle\Form\EstudioType;
use cobe\CurriculosBundle\Repository\EstudioRepository;

/**
 * API Estudio Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEstudioController extends ApiController
{
    /**
     * Retorna el repositorio de Estudio
     *
     * @return EstudioRepository
     */
    public function getEstudioRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:Estudio');
    }

    /**
     * Regresa opciones de API para Estudios.
     *
     * @Route("/estudios", name="options_estudios")
     * @Route("/estudios/", name="options_estudios_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEstudiosAction(Request $request)
    {
        $opciones = array(
            '/estudios' => array(
                'route'         => '/estudios',
                'method'        => 'GET',
                'description'   => 'Lista todos los estudios.',
                'examples'       => array(
                    '/estudios',
                    '/estudios/',
                ),
            ),
            '/estudios/params' => array(
                'route'         => '/estudios/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/estudios/params/?estudio[nombre]=Ecuador',
                    '/estudios/params/?estudio[descripcion]=Suramérica',
                    '/estudios/params/?estudio[descripcion]=País-Suraméricano',
                    '/estudios/params/?estudio[nombre]=República-Bolivariana-de-Venezuela&estudio[descripcion]=suramerica',
                    '/estudios/params/?estudio[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/estudios/o{offset}/' => array(
                'route'         => '/estudios/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/estudios/o1/',
                    '/estudios/o10',
                ),
            ),
            '/estudios/l{limit}/' => array(
                'route'         => '/estudios/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/estudios/l2/',
                    '/estudios/l10',
                ),
            ),
            '/estudios/0{offset}/l{limit}' => array(
                'route'         => '/estudios/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/estudios/o1/l2/',
                    '/estudios/o10/l10',
                ),
            ),
            '/estudios/new' => array(
                'route'         => '/estudios/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/estudios/new/',
                    '/estudios/new',
                ),
            ),
            '/estudios' => array(
                'route'         => '/estudios',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/estudios/',
                    '/estudios',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_estudios', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Estudios.
     *
     * @Route("/estudios", name="get_estudios")
     * @Route("/estudios/", name="get_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function getEstudiosAction(Request $request)
    {
        $repository = $this->getEstudioRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Estudios
     *
     * @Route("/estudios/new", name="new_estudios")
     * @Route("/estudios/new/", name="new_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function newEstudiosAction(Request $request)
    {
        $type = new EstudioType($this->generateUrl('post_estudios'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Estudios.
     *
     * @Route("/estudios", name="post_estudios")
     * @Route("/estudios/", name="post_estudios_")
     * @Template()
     * @Method("POST")
     */
    public function postEstudiosAction(Request $request)
    {
        $estudio = new Estudio();
        $type = new EstudioType($this->generateUrl('post_estudios'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estudio, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estudio, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estudios.
     *
     * @Route("/estudios", name="patch_estudios")
     * @Route("/estudios/", name="patch_estudios_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstudiosAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Estudio.
     *
     * @Route("/estudios/{slug}", name="get_estudios_slug")
     * @Route("/estudios/{slug}/", name="get_estudios_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEstudioAction(Request $request, $slug)
    {
        $estudio = null;
        switch($slug){
            case 'params':
                $datos = $request->get('estudio', false);
                if($datos){
                    $estudio = $this->getEstudioRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $estudio = $this->getEstudioRepository()->find($slug);
                break;
        }
        if (!$estudio) {
            $estudio = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($estudio, $request);
    }

    /**
     * Regresa el formulario para poder editar Estudio existente.
     *
     * @Route("/estudios/{slug}/edit", name="edit_estudios")
     * @Route("/estudios/{slug}/edit/", name="edit_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function editEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EstudioType($this->generateUrl('put_estudios', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $estudio );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Estudio existente.
     *
     * @Route("/estudios/{slug}", name="put_estudios")
     * @Route("/estudios/{slug}/", name="put_estudios_")
     * @Template()
     * @Method("PUT")
     */
    public function putEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);
        $type = new EstudioType($this->generateUrl('put_estudios', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estudio, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estudio, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estudio existente.
     *
     * @Route("/estudio/{slug}", name="patch_estudio")
     * @Route("/estudio/{slug}/", name="patch_estudio_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);
        $type = new EstudioType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $estudio){
            $repo = $this->getEstudioRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($estudio));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($estudio, $id) !== $dato){
                        $accessor->setValue($estudio, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($estudio));
                    $name = $name[count($name)-1];
                    $estudio = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $estudio;
        }
        return $this->getJsonResponse($estudio, $request);
    }

    /**
     * Regresa formulario para Eliminar Estudios..
     *
     * @Route("/estudios/{slug}/remove", name="remove_estudios")
     * @Route("/estudios/{slug}/remove/", name="remove_estudios_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstudiosAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudio){
            $form = $this->createDeleteForm($slug,'delete_estudios');
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
     * Regresa formulario para Eliminar Estudio.
     *
     * @Route("/estudio/{slug}/remove", name="remove_estudio")
     * @Route("/estudio/{slug}/remove/", name="remove_estudio_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudio){
            $form = $this->createDeleteForm($slug,'delete_estudio');
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
     * Elimina Estudios
     *
     * @Route("/estudios/{slug}", name="delete_estudios")
     * @Route("/estudios/{slug}/", name="delete_estudios_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstudiosAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudio){
            $form = $this->createDeleteForm($slug,'delete_estudios');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estudio){
                $em = $this->getManager();
                $em->remove($estudio);
                $em->flush();
                $rta = $estudio;
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
     * Elimina Estudio
     *
     * @Route("/estudio/{slug}", name="delete_estudio")
     * @Route("/estudio/{slug}/", name="delete_estudio_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstudioAction(Request $request, $slug)
    {
        $estudio = $this->getEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudio){
            $form = $this->createDeleteForm($slug,'delete_estudio');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estudio){
                $em = $this->getManager();
                $em->remove($estudio);
                $em->flush();
                $rta = $estudio;
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
