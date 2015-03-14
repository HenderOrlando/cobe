<?php

namespace cobe\OfertasLaboralesBundle\Controller\Api;

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

use cobe\OfertasLaboralesBundle\Entity\OfertaLaboral;
use cobe\OfertasLaboralesBundle\Form\OfertaLaboralType;
use cobe\OfertasLaboralesBundle\Repository\OfertaLaboralRepository;

/**
 * API OfertaLaboral Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiOfertaLaboralController extends ApiController
{
    /**
     * Retorna el repositorio de OfertaLaboral
     *
     * @return OfertaLaboralRepository
     */
    public function getOfertaLaboralRepository()
    {
        return $this->getManager()->getRepository('cobeOfertasLaboralesBundle:OfertaLaboral');
    }

    /**
     * Regresa opciones de API para OfertasLaborales.
     *
     * @Route("/ofertaslaborales", name="options_ofertaslaborales")
     * @Route("/ofertaslaborales/", name="options_ofertaslaborales_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsOfertasLaboralesAction(Request $request)
    {
        $opciones = array(
            '/ofertaslaborales' => array(
                'route'         => '/ofertaslaborales',
                'method'        => 'GET',
                'description'   => 'Lista todos los ofertaslaborales.',
                'examples'       => array(
                    '/ofertaslaborales',
                    '/ofertaslaborales/',
                ),
            ),
            '/ofertaslaborales/params' => array(
                'route'         => '/ofertaslaborales/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/ofertaslaborales/params/?ofertalaboral[nombre]=Ecuador',
                    '/ofertaslaborales/params/?ofertalaboral[descripcion]=Suramérica',
                    '/ofertaslaborales/params/?ofertalaboral[descripcion]=País-Suraméricano',
                    '/ofertaslaborales/params/?ofertalaboral[nombre]=República-Bolivariana-de-Venezuela&ofertalaboral[descripcion]=suramerica',
                    '/ofertaslaborales/params/?ofertalaboral[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/ofertaslaborales/o{offset}/' => array(
                'route'         => '/ofertaslaborales/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/ofertaslaborales/o1/',
                    '/ofertaslaborales/o10',
                ),
            ),
            '/ofertaslaborales/l{limit}/' => array(
                'route'         => '/ofertaslaborales/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/ofertaslaborales/l2/',
                    '/ofertaslaborales/l10',
                ),
            ),
            '/ofertaslaborales/0{offset}/l{limit}' => array(
                'route'         => '/ofertaslaborales/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/ofertaslaborales/o1/l2/',
                    '/ofertaslaborales/o10/l10',
                ),
            ),
            '/ofertaslaborales/new' => array(
                'route'         => '/ofertaslaborales/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/ofertaslaborales/new/',
                    '/ofertaslaborales/new',
                ),
            ),
            '/ofertaslaborales' => array(
                'route'         => '/ofertaslaborales',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/ofertaslaborales/',
                    '/ofertaslaborales',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_ofertaslaborales', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de OfertasLaborales.
     *
     * @Route("/ofertaslaborales", name="get_ofertaslaborales")
     * @Route("/ofertaslaborales/", name="get_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function getOfertasLaboralesAction(Request $request)
    {
        $repository = $this->getOfertaLaboralRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear OfertasLaborales
     *
     * @Route("/ofertaslaborales/new", name="new_ofertaslaborales")
     * @Route("/ofertaslaborales/new/", name="new_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function newOfertasLaboralesAction(Request $request)
    {
        $type = new OfertaLaboralType($this->generateUrl('post_ofertaslaborales'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea OfertasLaborales.
     *
     * @Route("/ofertaslaborales", name="post_ofertaslaborales")
     * @Route("/ofertaslaborales/", name="post_ofertaslaborales_")
     * @Template()
     * @Method("POST")
     */
    public function postOfertasLaboralesAction(Request $request)
    {
        $ofertalaboral = new OfertaLaboral();
        $type = new OfertaLaboralType($this->generateUrl('post_ofertaslaborales'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $ofertalaboral, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($ofertalaboral, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de OfertasLaborales.
     *
     * @Route("/ofertaslaborales", name="patch_ofertaslaborales")
     * @Route("/ofertaslaborales/", name="patch_ofertaslaborales_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchOfertasLaboralesAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa OfertaLaboral.
     *
     * @Route("/ofertaslaborales/{slug}", name="get_ofertaslaborales_slug")
     * @Route("/ofertaslaborales/{slug}/", name="get_ofertaslaborales_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = null;
        switch($slug){
            case 'params':
                $datos = $request->get('ofertalaboral', false);
                if($datos){
                    $ofertalaboral = $this->getOfertaLaboralRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);
                break;
        }
        if (!$ofertalaboral) {
            $ofertalaboral = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($ofertalaboral, $request);
    }

    /**
     * Regresa el formulario para poder editar OfertaLaboral existente.
     *
     * @Route("/ofertaslaborales/{slug}/edit", name="edit_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/edit/", name="edit_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function editOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new OfertaLaboralType($this->generateUrl('put_ofertaslaborales', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $ofertalaboral );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe OfertaLaboral existente.
     *
     * @Route("/ofertaslaborales/{slug}", name="put_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/", name="put_ofertaslaborales_")
     * @Template()
     * @Method("PUT")
     */
    public function putOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);
        $type = new OfertaLaboralType($this->generateUrl('put_ofertaslaborales', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $ofertalaboral, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($ofertalaboral, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de OfertaLaboral existente.
     *
     * @Route("/ofertalaboral/{slug}", name="patch_ofertalaboral")
     * @Route("/ofertalaboral/{slug}/", name="patch_ofertalaboral_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);
        $type = new OfertaLaboralType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $ofertalaboral){
            $repo = $this->getOfertaLaboralRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($ofertalaboral));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($ofertalaboral, $id) !== $dato){
                        $accessor->setValue($ofertalaboral, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($ofertalaboral));
                    $name = $name[count($name)-1];
                    $ofertalaboral = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $ofertalaboral;
        }
        return $this->getJsonResponse($ofertalaboral, $request);
    }

    /**
     * Regresa formulario para Eliminar OfertasLaborales..
     *
     * @Route("/ofertaslaborales/{slug}/remove", name="remove_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/remove/", name="remove_ofertaslaborales_")
     * @Template()
     * @Method("GET")
     */
    public function removeOfertasLaboralesAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($ofertalaboral){
            $form = $this->createDeleteForm($slug,'delete_ofertaslaborales');
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
     * Regresa formulario para Eliminar OfertaLaboral.
     *
     * @Route("/ofertalaboral/{slug}/remove", name="remove_ofertalaboral")
     * @Route("/ofertalaboral/{slug}/remove/", name="remove_ofertalaboral_")
     * @Template()
     * @Method("GET")
     */
    public function removeOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($ofertalaboral){
            $form = $this->createDeleteForm($slug,'delete_ofertalaboral');
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
     * Elimina OfertasLaborales
     *
     * @Route("/ofertaslaborales/{slug}", name="delete_ofertaslaborales")
     * @Route("/ofertaslaborales/{slug}/", name="delete_ofertaslaborales_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteOfertasLaboralesAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($ofertalaboral){
            $form = $this->createDeleteForm($slug,'delete_ofertaslaborales');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $ofertalaboral){
                $em = $this->getManager();
                $em->remove($ofertalaboral);
                $em->flush();
                $rta = $ofertalaboral;
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
     * Elimina OfertaLaboral
     *
     * @Route("/ofertalaboral/{slug}", name="delete_ofertalaboral")
     * @Route("/ofertalaboral/{slug}/", name="delete_ofertalaboral_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteOfertaLaboralAction(Request $request, $slug)
    {
        $ofertalaboral = $this->getOfertaLaboralRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($ofertalaboral){
            $form = $this->createDeleteForm($slug,'delete_ofertalaboral');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $ofertalaboral){
                $em = $this->getManager();
                $em->remove($ofertalaboral);
                $em->flush();
                $rta = $ofertalaboral;
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
