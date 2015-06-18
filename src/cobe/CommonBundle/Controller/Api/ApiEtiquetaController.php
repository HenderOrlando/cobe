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
     * Regresa herencias de API para Etiquetas.
     *
     * @Route("/etiquetas/aplicaciones", name="aplicaciones_etiquetas")
     * @Route("/etiquetas/aplicaciones/", name="aplicaciones_etiquetas_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function herenciasEtiquetasAction(Request $request){
        $herencias = array(
            "Etiqueta"      => "Etiqueta",
            "Interes"       => "Etiqueta de Interés",
            "Aptitud"       => "Etiqueta de Aptitud",
            "NivelIdioma"   => "Etiqueta de Nivel de Idioma",
            "Categoria"     => "Etiqueta de Categoría",
            "Caracteristica"=> "Etiqueta de Característica"
        );
        return $this->getJsonResponse($herencias, $request);
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
            array(
                'route'         => '/etiquetas',
                'method'        => 'GET',
                'description'   => 'Lista todos las etiquetas.',
                'examples'       => array(
                    '/etiquetas',
                    '/etiquetas/',
                ),
            ),
            array(
                'route'         => '/etiquetas/{id}',
                'method'        => 'GET',
                'description'   => 'Lista todos las etiquetas.',
                'examples'       => array(
                    '/etiquetas/{id}',
                    '/etiquetas/{id}/',
                ),
            ),
            array(
                'route'         => '/etiquetas/params',
                'method'        => 'GET',
                'description'   => 'Lista las etiquetas que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/etiquetas/params/?etiqueta[nombre]=Ecuador',
                    '/etiquetas/params/?etiqueta[descripcion]=Suramérica',
                    '/etiquetas/params/?etiqueta[descripcion]=Etiqueta-Suraméricano',
                    '/etiquetas/params/?etiqueta[nombre]=República-Bolivariana-de-Venezuela&etiqueta[descripcion]=suramerica',
                    '/etiquetas/params/?etiqueta[nombre]=republica-bolivariana-de-venezuela',
                    '/etiquetas/params/?etiqueta[herencia]=usuario',
                ),
            ),
            array(
                'route'         => '/etiquetas/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista las etiquetas iniciando en el Offset.',
                'examples'       => array(
                    '/etiquetas/o1/',
                    '/etiquetas/o10',
                ),
            ),
            array(
                'route'         => '/etiquetas/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las etiquetas iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/etiquetas/l2/',
                    '/etiquetas/l10',
                ),
            ),
            array(
                'route'         => '/etiquetas/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista las etiquetas iniciando en offset hasta limit.',
                'examples'       => array(
                    '/etiquetas/o1/l2/',
                    '/etiquetas/o10/l10',
                ),
            ),
            array(
                'route'         => '/etiquetas/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar una etiqueta.',
                'examples'       => array(
                    '/etiquetas/new/',
                    '/etiquetas/new',
                ),
            ),
            array(
                'route'         => '/etiquetas',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea etiquetas. Puede recibir datos de varias etiquetas.',
                'examples'       => array(
                    '/etiquetas/',
                    '/etiquetas',
                ),
            ),
            array(
                'route'         => '/etiquetas/{id}/edit',
                'method'        => 'GET',
                'description'   => 'Formulario de etiqueta para editar.',
                'examples'       => array(
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit/',
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/edit',
                ),
            ),
            array(
                'route'         => '/etiquetas/{id}',
                'method'        => 'PUT',
                'description'   => 'Sobreescribe los atributos de etiqueta.',
                'examples'       => array(
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/etiquetas/{id}',
                'method'        => 'PATCH',
                'description'   => 'Modifica un atributo de etiqueta',
                'examples'       => array(
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/etiquetas/{id}/remove',
                'method'        => 'PATCH',
                'description'   => 'Formulario para borrar etiqueta.',
                'examples'       => array(
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove/',
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/remove',
                ),
            ),
            array(
                'route'         => '/etiquetas/{id}',
                'method'        => 'DELETE',
                'description'   => 'Borra etiqueta.',
                'examples'       => array(
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2/',
                    '/etiquetas/038a3156-c9c1-11e4-b1eb-0022b003a0e2',
                ),
            ),
            array(
                'route'         => '/etiquetas/aplicaciones',
                'method'        => 'OPTIONS',
                'description'   => 'Ver las aplicaciones de Etiqueta.',
                'examples'       => array(
                    '/etiquetas/aplicaciones/',
                    '/etiquetas/aplicaciones',
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
                    'message'   => 'No se encuentran los datos para crear la Etiqueta.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $datos = $request->get($type->getName(), false);
            $herencias = $etiqueta->getHerencias();
            if(isset($datos['herencia']) && is_array($herencias) && array_key_exists($datos['herencia'],$herencias)){
                $etiquetaHerencia = $herencias[$datos['herencia']];
                $etiqueta = new $etiquetaHerencia();
                $type = new EtiquetaType($this->generateUrl('post_etiquetas'), 'POST', array(), $etiquetaHerencia);
            }
            $form = $this->getForm($type, $etiqueta, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($etiqueta, $request);
        }

        return $this->getJsonResponse($form, $request);
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
                        'message'   => 'Etiqueta no encontrada.',
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
                    'message'   => 'Etiqueta no encontrada.',
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
                    'message'   => 'No se encuentran los datos para crear la Etiqueta.',
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
     * @Route("/etiquetas/{slug}", name="patch_etiquetas")
     * @Route("/etiquetas/{slug}/", name="patch_etiquetas_")
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
                    'message'   => 'No se encuentran los datos para crear la Etiqueta.',
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
                $etiqueta = $this->captureErrorFlush($em, $etiqueta, 'editar');
            }
            $rta = $etiqueta;
        }
        return $this->getJsonResponse($rta, $request);
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
                    'message'   => 'Etiqueta no encontrada.',
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
                    'message'   => 'Etiqueta no encontrada.',
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
                $etiqueta = $this->captureErrorFlush($em, $etiqueta, 'borrar');
                $rta = $etiqueta;
                if(!$rta['errors']){
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
