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

use cobe\CurriculosBundle\Entity\CentroEstudio;
use cobe\CurriculosBundle\Form\CentroEstudioType;
use cobe\CurriculosBundle\Repository\CentroEstudioRepository;

/**
 * API CentroEstudio Controller.
 *
 * @package cobe\CommonBundle\Controller
 * @author Hender Orlando Puello Rincón <hender.puello@gmail.com>
 * @Route("/api-v1")
 */
class ApiCentroEstudioController extends ApiController
{
    /**
     * Retorna el repositorio de CentroEstudio
     *
     * @return CentroEstudioRepository
     */
    public function getCentroEstudioRepository()
    {
        return $this->getManager()->getRepository('cobeCurriculosBundle:CentroEstudio');
    }

    /**
     * Regresa opciones de API para CentrosEstudios.
     *
     * @Route("/centrosestudios", name="options_centrosestudios")
     * @Route("/centrosestudios/", name="options_centrosestudios_")
     * @Template()
     * @Method("OPTIONS")
     */
    public function optionsCentrosEstudiosAction(Request $request)
    {
        $opciones = array(
            '/centrosestudios' => array(
                'route'         => '/centrosestudios',
                'method'        => 'GET',
                'description'   => 'Lista todos los centrosestudios.',
                'examples'       => array(
                    '/centrosestudios',
                    '/centrosestudios/',
                ),
            ),
            '/centrosestudios/params' => array(
                'route'         => '/centrosestudios/params',
                'method'        => 'GET',
                'description'   => 'Lista los países que cumplan con los parametros enviados.',
                'examples'       => array(
                    '/centrosestudios/params/?centroestudio[nombre]=Ecuador',
                    '/centrosestudios/params/?centroestudio[descripcion]=Suramérica',
                    '/centrosestudios/params/?centroestudio[descripcion]=País-Suraméricano',
                    '/centrosestudios/params/?centroestudio[nombre]=República-Bolivariana-de-Venezuela&centroestudio[descripcion]=suramerica',
                    '/centrosestudios/params/?centroestudio[nombre]=republica-bolivariana-de-venezuela',
                ),
            ),
            '/centrosestudios/o{offset}/' => array(
                'route'         => '/centrosestudios/o{offset}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en el Offset.',
                'examples'       => array(
                    '/centrosestudios/o1/',
                    '/centrosestudios/o10',
                ),
            ),
            '/centrosestudios/l{limit}/' => array(
                'route'         => '/centrosestudios/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en 1 hasta limit.',
                'examples'       => array(
                    '/centrosestudios/l2/',
                    '/centrosestudios/l10',
                ),
            ),
            '/centrosestudios/0{offset}/l{limit}' => array(
                'route'         => '/centrosestudios/0{offset}/l{limit}',
                'method'        => 'GET',
                'description'   => 'Lista los países iniciando en offset hasta limit.',
                'examples'       => array(
                    '/centrosestudios/o1/l2/',
                    '/centrosestudios/o10/l10',
                ),
            ),
            '/centrosestudios/new' => array(
                'route'         => '/centrosestudios/new',
                'method'        => 'GET',
                'description'   => 'Carga el formulario para agregar un país.',
                'examples'       => array(
                    '/centrosestudios/new/',
                    '/centrosestudios/new',
                ),
            ),
            '/centrosestudios' => array(
                'route'         => '/centrosestudios',
                'method'        => 'POST',
                'description'   => 'Valida los datos y crea países. Puede recibir datos de varios países.',
                'examples'       => array(
                    '/centrosestudios/',
                    '/centrosestudios',
                ),
            ),
        );

        //$opts = $this->getPagerfanta($opciones, 'options_centrosestudios', true);

        return $this->getJsonResponse($opciones, $request);
    }

    /**
     * Regresa la lista de CentrosEstudios.
     *
     * @Route("/centrosestudios", name="get_centrosestudios")
     * @Route("/centrosestudios/", name="get_centrosestudios_")
     * @Template()
     * @Method("GET")
     */
    public function getCentrosEstudiosAction(Request $request)
    {
        $repository = $this->getCentroEstudioRepository();
        $list = $repository->getAll();

        return $this->getJsonResponse($list, $request);
    }

    /**
     * Regresa el formulario para crear CentrosEstudios
     *
     * @Route("/centrosestudios/new", name="new_centrosestudios")
     * @Route("/centrosestudios/new/", name="new_centrosestudios_")
     * @Template()
     * @Method("GET")
     */
    public function newCentrosEstudiosAction(Request $request)
    {
        $type = new CentroEstudioType($this->generateUrl('post_centrosestudios'), 'POST');
        return $this->getJsonResponse($this->getForm($type), $request);
    }

    /**
     * Valida los datos y crea CentrosEstudios.
     *
     * @Route("/centrosestudios", name="post_centrosestudios")
     * @Route("/centrosestudios/", name="post_centrosestudios_")
     * @Template()
     * @Method("POST")
     */
    public function postCentrosEstudiosAction(Request $request)
    {
        $centroestudio = new CentroEstudio();
        $type = new CentroEstudioType($this->generateUrl('post_centrosestudios'), 'POST');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $centroestudio, $request,true);
        }

        if (isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($centroestudio, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de CentrosEstudios.
     *
     * @Route("/centrosestudios", name="patch_centrosestudios")
     * @Route("/centrosestudios/", name="patch_centrosestudios_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCentrosEstudiosAction()
    {
        return array(
            // ...
        );
    }

    /**
     * Regresa CentroEstudio.
     *
     * @Route("/centrosestudios/{slug}", name="get_centrosestudios_slug")
     * @Route("/centrosestudios/{slug}/", name="get_centrosestudios_slug_")
     * @Template()
     * @Method("GET")
     */
    public function getCentroEstudioAction(Request $request, $slug)
    {
        $centroestudio = null;
        switch($slug){
            case 'params':
                $datos = $request->get('centroestudio', false);
                if($datos){
                    $centroestudio = $this->getCentroEstudioRepository()->getBy($datos, $this->getManager());
                }
                break;
            default:
                $centroestudio = $this->getCentroEstudioRepository()->find($slug);
                break;
        }
        if (!$centroestudio) {
            $centroestudio = array(
                'errors' => array(
                    '404' => array(
                        'message'   => 'País no encontrado.',
                        'code'      => '404',
                    ),
                ),
            );
        }

        return $this->getJsonResponse($centroestudio, $request);
    }

    /**
     * Regresa el formulario para poder editar CentroEstudio existente.
     *
     * @Route("/centrosestudios/{slug}/edit", name="edit_centrosestudios")
     * @Route("/centrosestudios/{slug}/edit/", name="edit_centrosestudios_")
     * @Template()
     * @Method("GET")
     */
    public function editCentroEstudioAction(Request $request, $slug)
    {
        $centroestudio = $this->getCentroEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        $type = new CentroEstudioType($this->generateUrl('put_centrosestudios', array('slug' => $slug)), 'PUT');
        $form = $this->getForm( $type, $centroestudio );

        $rta = $this->getJsonResponse( $form, $request );
        return $rta;
    }

    /**
     * Valida los datos y sobreescribe CentroEstudio existente.
     *
     * @Route("/centrosestudios/{slug}", name="put_centrosestudios")
     * @Route("/centrosestudios/{slug}/", name="put_centrosestudios_")
     * @Template()
     * @Method("PUT")
     */
    public function putCentroEstudioAction(Request $request, $slug)
    {
        $centroestudio = $this->getCentroEstudioRepository()->find($slug);
        $type = new CentroEstudioType($this->generateUrl('put_centrosestudios', array('slug' => $slug)), 'PUT');
        $form = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($request->get($type->getName(), false)){
            $form = $this->getForm($type, $centroestudio, $request,true);
        }

        if (isset($form['metadata']) && isset($form['metadata']['form']) && isset($form['metadata']['form']['saved']) && $form['metadata']['form']['saved']) {
            return $this->getJsonResponse($centroestudio, $request);
        }

        return $this->getJsonResponse($form, $request);
    }

    /**
     * Valida los datos y modifica atributos de CentroEstudio existente.
     *
     * @Route("/centroestudio/{slug}", name="patch_centroestudio")
     * @Route("/centroestudio/{slug}/", name="patch_centroestudio_")
     * @Template()
     * @Method("PATCH")
     */
    public function patchCentroEstudioAction(Request $request, $slug)
    {
        $centroestudio = $this->getCentroEstudioRepository()->find($slug);
        $type = new CentroEstudioType();
        $datos = $request->get($type->getName(), false);

        $rta = array(
            'errors' => array(
                '400' => array(
                    'message'   => 'No se encuentran los datos para crear el País.',
                    'code'      => '400',
                ),
            ),
        );

        if($datos && $centroestudio){
            $repo = $this->getCentroEstudioRepository();
            $em = $this->getManager();
            $metadata = $em->getClassMetadata(get_class($centroestudio));
            $isModify = false;
            foreach($datos as $id => $dato){
                /*
                 * Falta modificar asociaciones
                */
                if($metadata->hasField($id)){
                    $tipo = $metadata->getTypeOfField($id);
                    $dato = $repo->sanearDato($dato, $tipo);
                    $accessor = PropertyAccess::createPropertyAccessor();
                    if($accessor->getValue($centroestudio, $id) !== $dato){
                        $accessor->setValue($centroestudio, $id, $dato);
                        $isModify = true;
                    }
                }
            }
            if($isModify){
                try{
                    $em->flush();
                }catch(\Exception $e){
                    $name = explode('\\',get_class($centroestudio));
                    $name = $name[count($name)-1];
                    $centroestudio = array(
                        'errors' => array(
                            '400' => array(
                                'message'   => 'No se pudo actualizar "'.$id.'" del recurso "'.$name,
                                'code'      => "400",
                            ),
                        ),
                    );
                }
            }
            $rta = $centroestudio;
        }
        return $this->getJsonResponse($centroestudio, $request);
    }

    /**
     * Regresa formulario para Eliminar CentrosEstudios..
     *
     * @Route("/centrosestudios/{slug}/remove", name="remove_centrosestudios")
     * @Route("/centrosestudios/{slug}/remove/", name="remove_centrosestudios_")
     * @Template()
     * @Method("GET")
     */
    public function removeCentrosEstudiosAction(Request $request, $slug)
    {
        $centroestudio = $this->getCentroEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($centroestudio){
            $form = $this->createDeleteForm($slug,'delete_centrosestudios');
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
     * Regresa formulario para Eliminar CentroEstudio.
     *
     * @Route("/centroestudio/{slug}/remove", name="remove_centroestudio")
     * @Route("/centroestudio/{slug}/remove/", name="remove_centroestudio_")
     * @Template()
     * @Method("GET")
     */
    public function removeCentroEstudioAction(Request $request, $slug)
    {
        $centroestudio = $this->getCentroEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($centroestudio){
            $form = $this->createDeleteForm($slug,'delete_centroestudio');
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
     * Elimina CentrosEstudios
     *
     * @Route("/centrosestudios/{slug}", name="delete_centrosestudios")
     * @Route("/centrosestudios/{slug}/", name="delete_centrosestudios_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCentrosEstudiosAction(Request $request, $slug)
    {
        $centroestudio = $this->getCentroEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($centroestudio){
            $form = $this->createDeleteForm($slug,'delete_centrosestudios');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $centroestudio){
                $em = $this->getManager();
                $em->remove($centroestudio);
                $em->flush();
                $rta = $centroestudio;
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
     * Elimina CentroEstudio
     *
     * @Route("/centroestudio/{slug}", name="delete_centroestudio")
     * @Route("/centroestudio/{slug}/", name="delete_centroestudio_")
     * @Template()
     * @Method("DELETE")
     */
    public function deleteCentroEstudioAction(Request $request, $slug)
    {
        $centroestudio = $this->getCentroEstudioRepository()->find($slug);

        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'País no encontrado.',
                    'code'      => '404',
                ),
            ),
        );
        if($centroestudio){
            $form = $this->createDeleteForm($slug,'delete_centroestudio');
            $form->handleRequest($request);
            //$isValid = $form->isValid();
            $deleted = false;
            $isValid = true;
            if($isValid && $centroestudio){
                $em = $this->getManager();
                $em->remove($centroestudio);
                $em->flush();
                $rta = $centroestudio;
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
