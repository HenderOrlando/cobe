<?php

namespace cobe\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\CommonBundle\Entity\Ciudad;
use cobe\CommonBundle\Form\CiudadType;

/**
 * Ciudad controller.
 *
 * @Route("/common_ciudad")
 */
class CiudadController extends Controller
{

    /**
     * Lists all Ciudad entities.
     *
     * @Route("/", name="common_ciudad")
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
    /**
     * Creates a new Ciudad entity.
     *
     * @Route("/", name="common_ciudad_create")
     * @Method("POST")
     * @Template("cobeCommonBundle:Ciudad:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Ciudad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('common_ciudad_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Ciudad entity.
     *
     * @param Ciudad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ciudad $entity)
    {
        $form = $this->createForm(new CiudadType(), $entity, array(
            'action' => $this->generateUrl('common_ciudad_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Ciudad entity.
     *
     * @Route("/new", name="common_ciudad_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ciudad();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Ciudad entity.
     *
     * @Route("/{id}", name="common_ciudad_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Ciudad entity.
     *
     * @Route("/{id}/edit", name="common_ciudad_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
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
    * Creates a form to edit a Ciudad entity.
    *
    * @param Ciudad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ciudad $entity)
    {
        $form = $this->createForm(new CiudadType(), $entity, array(
            'action' => $this->generateUrl('common_ciudad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Ciudad entity.
     *
     * @Route("/{id}", name="common_ciudad_update")
     * @Method("PUT")
     * @Template("cobeCommonBundle:Ciudad:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('common_ciudad_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Ciudad entity.
     *
     * @Route("/{id}", name="common_ciudad_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeCommonBundle:Ciudad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ciudad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('common_ciudad'));
    }

    /**
     * Creates a form to delete a Ciudad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('common_ciudad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
