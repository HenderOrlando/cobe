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

use cobe\UsuariosBundle\Entity\Estudiante;
use cobe\UsuariosBundle\Form\EstudianteType;
use cobe\UsuariosBundle\Repository\EstudianteRepository;

/**
 * API Estudiante Controller.
 *
 * @package cobe\UsuariosBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiEstudianteController extends ApiController
{
    /**
     * Retorna el repositorio de Estudiante
     *
     * @return EstudianteRepository
     */
    public function getEstudianteRepository()
    {
        return $this->getManager()->getRepository('cobeUsuariosBundle:Estudiante');
    }

    /**
     * Regresa opciones de API para Estudiantes.
     *
     * @Route("/estudiantes/attributes", name="options_estudiantes_validate")
     * @Route("/estudiantes/attributes/", name="options_estudiantes_validate_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function getAtributesAction(Request $request){
        $obj = new Estudiante();
        $herencia = $request->get('herencia', false);
        return $this->getJsonResponse($this->getConfigObject($obj, $herencia), $request);
    }

    /**
     * Regresa opciones de API para Estudiantes.
     *
     * @Route("/estudiantes", name="options_estudiantes")
     * @Route("/estudiantes/", name="options_estudiantes_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsEstudiantesAction(Request $request)
    {
        $opciones = array(
            array(
                'route'         => '/estudiantes',
                'method'        => 'GET',
                'description'   => 'Lista todas las estudiantes.',
                'examples'       => array(
                    '/estudiantes',
                    '/estudiantes/',
                ),
            ),
            array(
                'route'         => '/estudiantes/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todas las estudiantes.',
                'examples'       => array(
                    '/estudiantes/{id}',
                    '/estudiantes/{id}/',
                ),
            ),
            array(
                'route'         => '/estudiantes/params',
                'method'        => 'GET',
                'description'   => 'Lista las estudiantes que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/estudiantes/params/?estudiante[nombre]=Ecuador',
                    '/estudiantes/params/?estudiante[descripcion]=Suramérica',
                    '/estudiantes/params/?estudiante[descripcion]=Estudiante-Suraméricano',
                    '/estudiantes/params/?estudiante[nombre]=República-Bolivariana-de-Venezuela&estudiante[descripcion]=suramerica',
                    '/estudiantes/params/?estudiante[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            array(
                'route'         => '/estudiantes/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las estudiantes iniciando en el Offset.',
                'examples'       => array(
                    '/estudiantes/o1/',
                    '/estudiantes/o10',
                ),
            ),
            array(
                'route'         => '/estudiantes/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las estudiantes iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/estudiantes/l2/',
                    '/estudiantes/l10',
                ),
            ),
            array(
                'route'         => '/estudiantes/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las estudiantes iniciando en offset hasta limit.',
                'examples'       => array(
                    '/estudiantes/o1/l2/',
                    '/estudiantes/o10/l10',
                ),
            ),
            array(
                'route'         => '/estudiantes/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una estudiante.',
                'examples'       => array(
                    '/estudiantes/new/',
                    '/estudiantes/new',
                ),
            ),
            array(
                'route'         => '/estudiantes',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea estudiantes. Puede recibir datos de varias estudiantes.',
                'examples'       => array(
                    '/estudiantes/',
                    '/estudiantes',
                ),
            ),
            array(
                'route'         => '/estudiantes/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de estudiante para editar.',
                'examples'       => array(
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/estudiantes/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de estudiante.',
                'examples'       => array(
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estudiantes/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de estudiante',
                'examples'       => array(
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/estudiantes/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar estudiante.',
                'examples'       => array(
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/estudiantes/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra estudiante.',
                'examples'       => array(
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/estudiantes/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_estudiantes', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de Estudiantes.
     *
     * @Route("/estudiantes", name="get_estudiantes")
     * @Route("/estudiantes/", name="get_estudiantes_")
     * @Template()
     * @Method("GET")
     */
    public function getEstudiantesAction(Request $request)
    {
        $repository = $this->getEstudianteRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear Estudiantes
     *
     * @Route("/estudiantes/new", name="new_estudiantes")
     * @Route("/estudiantes/new/", name="new_estudiantes_")
     * @Template()
     * @Method("GET")
     */
    public function newEstudiantesAction(Request $request)
    {
        $type = new EstudianteType($this->generateUrl('post_estudiantes'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea Estudiantes.
     *
     * @Route("/estudiantes", name="post_estudiantes")
     * @Route("/estudiantes/", name="post_estudiantes_")
     * @Template()
     * @Method("POST")
     */
    public function postEstudiantesAction(Request $request)
    {
        $estudiante = new Estudiante();
        $type = new EstudianteType($this->generateUrl('post_estudiantes'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Estudiante.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estudiante, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estudiante, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Regresa Estudiante.
     *
     * @Route("/estudiantes/{slug}", name="get_estudiantes_slug")
     * @Route("/estudiantes/{slug}/", name="get_estudiantes_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getEstudianteAction(Request $request, $slug)
    {
        $estudiante = null;
        switch($slug){
            case 'params':
                $datos = $request->get('estudiante', false);
                if($datos){
                    $estudiante = $this->getEstudianteRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $estudiante = $this->getEstudianteRepository()->find($slug);
                break;
        }
        if (!$estudiante) {
            $estudiante = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'Estudiante no encontrada.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($estudiante, $request);
    }

    /**
     * Regresa el formulario para poder editar Estudiante existente.
     *
     * @Route("/estudiantes/{slug}/edit", name="edit_estudiantes")
     * @Route("/estudiantes/{slug}/edit/", name="edit_estudiantes_")
     * @Template()
     * @Method("GET")
     */
    public function editEstudianteAction(Request $request, $slug)
    {
        $estudiante = $this->getEstudianteRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estudiante no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new EstudianteType($this->generateUrl('put_estudiantes', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $estudiante );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe Estudiante existente.
     *
     * @Route("/estudiantes/{slug}", name="put_estudiantes")
     * @Route("/estudiantes/{slug}/", name="put_estudiantes_")
     * @Template()
     * @Method("PUT")
     */
    public function putEstudianteAction(Request $request, $slug)
    {
        $estudiante = $this->getEstudianteRepository()->find($slug);
        $type = new EstudianteType($this->generateUrl('put_estudiantes', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Estudiante.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $estudiante, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($estudiante, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de Estudiante existente.
     *
     * @Route("/estudiantes/{slug}", name="patch_estudiantes")
     * @Route("/estudiantes/{slug}/", name="patch_estudiantes_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchEstudianteAction(Request $request, $slug)
    {
        $estudiante = $this->getEstudianteRepository()->find($slug);
        $type = new EstudianteType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear la Estudiante.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $estudiante){
            $repo = $this->getEstudianteRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($estudiante));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($estudiante, $id) !== $dato){
                        $accessor->setValue($estudiante, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                $estudiante = $this->captureErrorFlush($em, $estudiante, 'editar');
            }
            $rta = $estudiante;
        }
        return $this->getJsonResponse($rta, $request);
    }

    /**
     * Regresa formulario para Eliminar Estudiantes..
     *
     * @Route("/estudiantes/{slug}/remove", name="remove_estudiantes")
     * @Route("/estudiantes/{slug}/remove/", name="remove_estudiantes_")
     * @Template()
     * @Method("GET")
     */
    public function removeEstudiantesAction(Request $request, $slug)
    {
        $estudiante = $this->getEstudianteRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estudiante no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudiante){
            $form = $this->createDeleteForm($slug,'delete_estudiantes');
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
     * Elimina Estudiantes
     *
     * @Route("/estudiantes/{slug}", name="delete_estudiantes")
     * @Route("/estudiantes/{slug}/", name="delete_estudiantes_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteEstudiantesAction(Request $request, $slug)
    {
        $estudiante = $this->getEstudianteRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Estudiante no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        if($estudiante){
            $form = $this->createDeleteForm($slug,'delete_estudiantes');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $estudiante){
                $em = $this->getManager();
                $em->remove($estudiante);
                $estudiante = $this->captureErrorFlush($em, $estudiante, 'borrar');
                $rta = $estudiante;
                if(!$estudiante['errors']){
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
