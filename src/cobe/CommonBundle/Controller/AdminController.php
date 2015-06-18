<?php

namespace cobe\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Ciudad controller.
 *
 * @Route("/Admin")
 */
class AdminController extends Controller
{

    /**
     * Lists all Acciones del SuperAdmin entities.
     *
     * @Route("/", name="admin_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeCommonBundle:Ciudad')->findAll();

        return array(
            'entities' => $entities,
        );
    }
}
