<?php

namespace cobe\CommonBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
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
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Configuration\Route as HateoasRoute;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Tipo controller.
 *
 * @Route("/common_tipo")
 */
class ErrorController extends Controller
{
    protected $page = 1;
    protected $offset = 1;
    protected $limit = 5;
    /**
     * Lists all Errors.
     */
    public function showAction(Request $request)
    {
        $rta = array(
            'errors' => array(
                '404' => array(
                    'message'   => 'Página no encontrada.',
                    'code'      => '404',
                ),
            ),
        );
        //return new JsonResponse($rta, 200, array('Content-Type'));
        return $this->getJsonResponse($rta, $request);
    }

    public function getRta($objs, $format = 'json', $route = ''){
        if(is_array($objs) || is_a($objs, 'Doctrine\ORM\QueryBuilder') || is_a($objs,'Hateoas\Representation\PaginatedRepresentation') && $route){
            $objs = $this->getPagerfanta($objs, $route);
        }
        $datos = $this->container->get('serializer')->serialize($objs, $format);

        /*foreach($objs as $id => $dato){
            if(is_object($dato) && method_exists($dato,'getId')){
                $hateoas = HateoasBuilder::create()->build();
                $datos[$dato->getId()] = $hateoas->serialize($dato, $format);
            }else{
                $datos[$id] = $dato;
            }
        }*/

        return $datos;
    }

    public function getJson($objs, $route = ''){
        return $this->getRta($objs, 'json', $route);
    }

    public function getJsonResponse($objs, Request $request){
        $this->offset = intval($request->get('offset', $this->offset));
        $this->limit = intval($request->get('limit', $this->limit));
        //$this->page = floor($offset / $limit) + 1;
        $this->page = intval($request->get('page',$this->page));

        //$code = 200;

        $rta = $this->getJson($objs, array(
            'route' => 'admin_index',
            'params' => array(),
        ));

        /*$decode = json_decode($rta);
        return new JsonResponse($decode);*/
        return new Response($rta, 200, array('Content-Type' => 'application/json'));
    }

    public function getPagerfanta($list, $route = null){
        if(is_string($route)){
            $route = array(
                'route' => $route,
                'params' => array(),
            );
        }elseif(is_array($route) && isset($route['route'])){
            if(!isset($route['params'])){
                $route['params'] = array();
            }
        }else{
            $route = null;
        }

        if(is_array($list)){
            $adapter = new ArrayAdapter($list);
        }else{
            $adapter = new DoctrineORMAdapter($list);
        }
        $pager = new Pagerfanta($adapter);
        $pager
            ->setMaxPerPage($this->limit)
            ->setCurrentPage($this->page);

        $pagerfantaFactory = new PagerfantaFactory();

        $rta = $pagerfantaFactory->createRepresentation(
            $pager,
            new HateoasRoute($route['route'], $route['params'],true)
        );
        return $rta;
    }
}
