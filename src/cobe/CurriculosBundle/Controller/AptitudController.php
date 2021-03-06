<?php

namespace cobe\CurriculosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\CurriculosBundle\Entity\Aptitud;
use cobe\CurriculosBundle\Form\AptitudType;

/**
 * Aptitud controller.
 *
 * @Route("/curriculos_aptitud")
 */
class AptitudController extends Controller
{

    /**
     * Lists all Aptitud entities.
     *
     * @Route("/", name="curriculos_aptitud")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeCurriculosBundle:Aptitud')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Aptitud entity.
     *
     * @Route("/", name="curriculos_aptitud_create")
     * @Method("POST")
     * @Template("cobeCurriculosBundle:Aptitud:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Aptitud();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('curriculos_aptitud_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Aptitud entity.
     *
     * @param Aptitud $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Aptitud $entity)
    {
        $form = $this->createForm(new AptitudType(), $entity, array(
            'action' => $this->generateUrl('curriculos_aptitud_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Aptitud entity.
     *
     * @Route("/new", name="curriculos_aptitud_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Aptitud();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Aptitud entity.
     *
     * @Route("/{id}", name="curriculos_aptitud_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:Aptitud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aptitud entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Aptitud entity.
     *
     * @Route("/{id}/edit", name="curriculos_aptitud_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:Aptitud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aptitud entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Aptitud entity.
    *
    * @param Aptitud $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Aptitud $entity)
    {
        $form = $this->createForm(new AptitudType(), $entity, array(
            'action' => $this->generateUrl('curriculos_aptitud_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Aptitud entity.
     *
     * @Route("/{id}", name="curriculos_aptitud_update")
     * @Method("PUT")
     * @Template("cobeCurriculosBundle:Aptitud:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:Aptitud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aptitud entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('curriculos_aptitud_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Aptitud entity.
     *
     * @Route("/{id}", name="curriculos_aptitud_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeCurriculosBundle:Aptitud')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Aptitud entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('curriculos_aptitud'));
    }

    /**
     * Creates a form to delete a Aptitud entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('curriculos_aptitud_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
