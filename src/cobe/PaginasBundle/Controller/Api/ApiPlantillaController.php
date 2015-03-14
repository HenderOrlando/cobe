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

use cobe\PaginasBundle\Entity\Plantilla;
use cobe\PaginasBundle\Form\PlantillaType;
use cobe\PaginasBundle\Repository\PlantillaRepository;

/**
 * API Plantilla Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiPlantillaController extends ApiController
{
    /**
     * Retorna el repositorio de Plantilla
     *
     * @return PlantillaRepository
     */
    public function getPlantillaRepository()
    {
        return $this->getManager()->getRepository('cobePaginasBundle:Plantilla');
    }

    /**
     * Regresa opciones de API para Plantillas.
     *
     * @Route("/plantillas", name="options_plantillas")
     * @Route("/plantillas/", name="options_plantillas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsPlantillasAction(Request $request)
    {
        $opciones = array(
            '/plantillas' => array(
                'route'         => '/plantillas',
                'method'        => 'GET',
                'description'   => 'Lista todos los plantillas.',
                'examples'       => array(
                    '/plantillas',
                    '/plantillas/',
                ),
            ),
            '/plantillas/params' => array(
                'route'         => '/plantillas/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/plantillas/params/?plantilla[nombre]=Ecuador',
                    '/plantillas/params/?plantilla[descripcion]=Suramérica',
                    '/plantillas/params/?plantilla[descripcion]=País-Suraméricano',
                    '/plantillas/params/?plantilla[nombre]=República-Bolivariana-de-Venezuela&plantilla[descripcion]=suramerica',
                    '/plantillas/params/?plantilla[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/plantillas/o{offset}/' => array(
                'route'         => '/plantillas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/plantillas/o1/',
                    '/plantillas/o10',
                ),
            ),
            '/plantillas/l{limit}/' => array(
                'route'         => '/plantillas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/plantillas/l2/',
                    '/plantillas/l10',
                ),
            ),
            '/plantillas/0{offset}/l{limit}' => array(
                'route'         => '/plantillas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/plantillas/o1/l2/',
                    '/plantillas/o10/l10',
                ),
            ),
            '/plantillas/new' => array(
                'route'         => '/plantillas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/plantillas/new/',
                    '/plantillas/new',
                ),
            ),
            '/plantillas' => array(
                'route'         => '/plantillas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/plantillas/',
                    '/plantillas',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_plantillas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Plantillas.
     *
     * @Route("/plantillas", name="get_plantillas")
     * @Route("/plantillas/", name="get_plantillas_")
     * @Template()
     * @Method("GET")
     */
    public function getPlantillasAction(Request $request)
    {
        $repository = $this->getPlantillaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Plantillas
     *
     * @Route("/plantillas/new", name="new_plantillas")
     * @Route("/plantillas/new/", name="new_plantillas_")
     * @Template()
     * @Method("GET")
     */
    public function newPlantillasAction(Request $request)
    {
        $type = new PlantillaType($this->generateUrl('post_plantillas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Plantillas.
     *
     * @Route("/plantillas", name="post_plantillas")
     * @Route("/plantillas/", name="post_plantillas_")
     * @Template()
     * @Method("POST")
     */
    public function postPlantillasAction(Request $request)
    {
        $plantilla = new Plantilla();
        $type = new PlantillaType($this->generateUrl('post_plantillas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $plantilla, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($plantilla, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Plantillas.
     *
     * @Route("/plantillas", name="patch_plantillas")
     * @Route("/plantillas/", name="patch_plantillas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchPlantillasAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Plantilla.
     *
     * @Route("/plantillas/{slug}", name="get_plantillas_slug")
     * @Route("/plantillas/{slug}/", name="get_plantillas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getPlantillaAction(Request $request, $slug)
    {
        $plantilla = null;
        switch($slug){
            case 'params':
                $datos = $request->get('plantilla', false);
                if($datos){
                    $plantilla = $this->getPlantillaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $plantilla = $this->getPlantillaRepository()->find($slug);
                break;
        }
        if (!$plantilla) {
            $plantilla = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($plantilla, $request);
    }

    /**
     * Regresa el formulario para poder editar Plantilla existente.
     *
     * @Route("/plantillas/{slug}/edit", name="edit_plantillas")
     * @Route("/plantillas/{slug}/edit/", name="edit_plantillas_")
     * @Template()
     * @Method("GET")
     */
    public function editPlantillaAction(Request $request, $slug)
    {
        $plantilla = $this->getPlantillaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new PlantillaType($this->generateUrl('put_plantillas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $plantilla );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Plantilla existente.
     *
     * @Route("/plantillas/{slug}", name="put_plantillas")
     * @Route("/plantillas/{slug}/", name="put_plantillas_")
     * @Template()
     * @Method("PUT")
     */
    public function putPlantillaAction(Request $request, $slug)
    {
        $plantilla = $this->getPlantillaRepository()->find($slug);
        $type = new PlantillaType($this->generateUrl('put_plantillas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $plantilla, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($plantilla, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Plantilla existente.
     *
     * @Route("/plantilla/{slug}", name="patch_plantilla")
     * @Route("/plantilla/{slug}/", name="patch_plantilla_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchPlantillaAction(Request $request, $slug)
    {
        $plantilla = $this->getPlantillaRepository()->find($slug);
        $type = new PlantillaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $plantilla){
            $repo = $this->getPlantillaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($plantilla));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($plantilla, $id) !== $dato){
                        $accessor->setValue($plantilla, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($plantilla));
                    $name = $name[count($name)-1];
                    $plantilla = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $plantilla;
        }
        return $this->getJsonResponse($plantilla, $request);
    }

    /**
     * Regresa formulario para Eliminar Plantillas..
     *
     * @Route("/plantillas/{slug}/remove", name="remove_plantillas")
     * @Route("/plantillas/{slug}/remove/", name="remove_plantillas_")
     * @Template()
     * @Method("GET")
     */
    public function removePlantillasAction(Request $request, $slug)
    {
        $plantilla = $this->getPlantillaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($plantilla){
            $form = $this->createDeleteForm($slug,'delete_plantillas');
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
     * Regresa formulario para Eliminar Plantilla.
     *
     * @Route("/plantilla/{slug}/remove", name="remove_plantilla")
     * @Route("/plantilla/{slug}/remove/", name="remove_plantilla_")
     * @Template()
     * @Method("GET")
     */
    public function removePlantillaAction(Request $request, $slug)
    {
        $plantilla = $this->getPlantillaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($plantilla){
            $form = $this->createDeleteForm($slug,'delete_plantilla');
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
     * Elimina Plantillas
     *
     * @Route("/plantillas/{slug}", name="delete_plantillas")
     * @Route("/plantillas/{slug}/", name="delete_plantillas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deletePlantillasAction(Request $request, $slug)
    {
        $plantilla = $this->getPlantillaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($plantilla){
            $form = $this->createDeleteForm($slug,'delete_plantillas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $plantilla){
                $em = $this->getManager();
                $em->remove($plantilla);
                $em->flush();
                $rta = $plantilla;
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
     * Elimina Plantilla
     *
     * @Route("/plantilla/{slug}", name="delete_plantilla")
     * @Route("/plantilla/{slug}/", name="delete_plantilla_")
     * @Template()
     * @Method("DELETE")
     */
    public function deletePlantillaAction(Request $request, $slug)
    {
        $plantilla = $this->getPlantillaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($plantilla){
            $form = $this->createDeleteForm($slug,'delete_plantilla');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $plantilla){
                $em = $this->getManager();
                $em->remove($plantilla);
                $em->flush();
                $rta = $plantilla;
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