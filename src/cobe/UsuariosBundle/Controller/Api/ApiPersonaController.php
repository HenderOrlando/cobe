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

use cobe\UsuariosBundle\Entity\Persona;
use cobe\UsuariosBundle\Form\PersonaType;
use cobe\UsuariosBundle\Repository\PersonaRepository;

/**
 * API Persona Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiPersonaController extends ApiController
{
    /**
     * Retorna el repositorio de Persona
     *
     * @return PersonaRepository
     */
    public function getPersonaRepository()
    {
        return $this->getManager()->getRepository('cobeUsuariosBundle:Persona');
    }

    /**
     * Regresa opciones de API para Personas.
     *
     * @Route("/personas", name="options_personas")
     * @Route("/personas/", name="options_personas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsPersonasAction(Request $request)
    {
        $opciones = array(
            '/personas' => array(
                'route'         => '/personas',
                'method'        => 'GET',
                'description'   => 'Lista todos los personas.',
                'examples'       => array(
                    '/personas',
                    '/personas/',
                ),
            ),
            '/personas/params' => array(
                'route'         => '/personas/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/personas/params/?persona[nombre]=Ecuador',
                    '/personas/params/?persona[descripcion]=Suramérica',
                    '/personas/params/?persona[descripcion]=País-Suraméricano',
                    '/personas/params/?persona[nombre]=República-Bolivariana-de-Venezuela&persona[descripcion]=suramerica',
                    '/personas/params/?persona[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/personas/o{offset}/' => array(
                'route'         => '/personas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/personas/o1/',
                    '/personas/o10',
                ),
            ),
            '/personas/l{limit}/' => array(
                'route'         => '/personas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/personas/l2/',
                    '/personas/l10',
                ),
            ),
            '/personas/0{offset}/l{limit}' => array(
                'route'         => '/personas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/personas/o1/l2/',
                    '/personas/o10/l10',
                ),
            ),
            '/personas/new' => array(
                'route'         => '/personas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/personas/new/',
                    '/personas/new',
                ),
            ),
            '/personas' => array(
                'route'         => '/personas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/personas/',
                    '/personas',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_personas', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Personas.
     *
     * @Route("/personas", name="get_personas")
     * @Route("/personas/", name="get_personas_")
     * @Template()
     * @Method("GET")
     */
    public function getPersonasAction(Request $request)
    {
        $repository = $this->getPersonaRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Personas
     *
     * @Route("/personas/new", name="new_personas")
     * @Route("/personas/new/", name="new_personas_")
     * @Template()
     * @Method("GET")
     */
    public function newPersonasAction(Request $request)
    {
        $type = new PersonaType($this->generateUrl('post_personas'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Personas.
     *
     * @Route("/personas", name="post_personas")
     * @Route("/personas/", name="post_personas_")
     * @Template()
     * @Method("POST")
     */
    public function postPersonasAction(Request $request)
    {
        $persona = new Persona();
        $type = new PersonaType($this->generateUrl('post_personas'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $persona, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($persona, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Personas.
     *
     * @Route("/personas", name="patch_personas")
     * @Route("/personas/", name="patch_personas_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchPersonasAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa Persona.
     *
     * @Route("/personas/{slug}", name="get_personas_slug")
     * @Route("/personas/{slug}/", name="get_personas_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getPersonaAction(Request $request, $slug)
    {
        $persona = null;
        switch($slug){
            case 'params':
                $datos = $request->get('persona', false);
                if($datos){
                    $persona = $this->getPersonaRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $persona = $this->getPersonaRepository()->find($slug);
                break;
        }
        if (!$persona) {
            $persona = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($persona, $request);
    }

    /**
     * Regresa el formulario para poder editar Persona existente.
     *
     * @Route("/personas/{slug}/edit", name="edit_personas")
     * @Route("/personas/{slug}/edit/", name="edit_personas_")
     * @Template()
     * @Method("GET")
     */
    public function editPersonaAction(Request $request, $slug)
    {
        $persona = $this->getPersonaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new PersonaType($this->generateUrl('put_personas', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $persona );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Persona existente.
     *
     * @Route("/personas/{slug}", name="put_personas")
     * @Route("/personas/{slug}/", name="put_personas_")
     * @Template()
     * @Method("PUT")
     */
    public function putPersonaAction(Request $request, $slug)
    {
        $persona = $this->getPersonaRepository()->find($slug);
        $type = new PersonaType($this->generateUrl('put_personas', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $persona, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($persona, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Persona existente.
     *
     * @Route("/persona/{slug}", name="patch_persona")
     * @Route("/persona/{slug}/", name="patch_persona_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchPersonaAction(Request $request, $slug)
    {
        $persona = $this->getPersonaRepository()->find($slug);
        $type = new PersonaType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $persona){
            $repo = $this->getPersonaRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($persona));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($persona, $id) !== $dato){
                        $accessor->setValue($persona, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($persona));
                    $name = $name[count($name)-1];
                    $persona = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $persona;
        }
        return $this->getJsonResponse($persona, $request);
    }

    /**
     * Regresa formulario para Eliminar Personas..
     *
     * @Route("/personas/{slug}/remove", name="remove_personas")
     * @Route("/personas/{slug}/remove/", name="remove_personas_")
     * @Template()
     * @Method("GET")
     */
    public function removePersonasAction(Request $request, $slug)
    {
        $persona = $this->getPersonaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($persona){
            $form = $this->createDeleteForm($slug,'delete_personas');
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
     * Regresa formulario para Eliminar Persona.
     *
     * @Route("/persona/{slug}/remove", name="remove_persona")
     * @Route("/persona/{slug}/remove/", name="remove_persona_")
     * @Template()
     * @Method("GET")
     */
    public function removePersonaAction(Request $request, $slug)
    {
        $persona = $this->getPersonaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($persona){
            $form = $this->createDeleteForm($slug,'delete_persona');
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
     * Elimina Personas
     *
     * @Route("/personas/{slug}", name="delete_personas")
     * @Route("/personas/{slug}/", name="delete_personas_")
     * @Template()
     * @Method("DELETE")
     */
    public function deletePersonasAction(Request $request, $slug)
    {
        $persona = $this->getPersonaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($persona){
            $form = $this->createDeleteForm($slug,'delete_personas');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $persona){
                $em = $this->getManager();
                $em->remove($persona);
                $em->flush();
                $rta = $persona;
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
     * Elimina Persona
     *
     * @Route("/persona/{slug}", name="delete_persona")
     * @Route("/persona/{slug}/", name="delete_persona_")
     * @Template()
     * @Method("DELETE")
     */
    public function deletePersonaAction(Request $request, $slug)
    {
        $persona = $this->getPersonaRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($persona){
            $form = $this->createDeleteForm($slug,'delete_persona');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $persona){
                $em = $this->getManager();
                $em->remove($persona);
                $em->flush();
                $rta = $persona;
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