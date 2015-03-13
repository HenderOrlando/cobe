<?php

namespace cobe\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\CommonBundle\Entity\Traduccion;
use cobe\CommonBundle\Form\TraduccionType;

/**
 * Traduccion controller.
 *
 * @Route("/common_traduccion")
 */
class TraduccionController extends Controller
{

    /**
     * Lists all Traduccion entities.
     *
     * @Route("/", name="common_traduccion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeCommonBundle:Traduccion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Traduccion entity.
     *
     * @Route("/", name="common_traduccion_create")
     * @Method("POST")
     * @Template("cobeCommonBundle:Traduccion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Traduccion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('common_traduccion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Traduccion entity.
     *
     * @param Traduccion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Traduccion $entity)
    {
        $form = $this->createForm(new TraduccionType(), $entity, array(
            'action' => $this->generateUrl('common_traduccion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Traduccion entity.
     *
     * @Route("/new", name="common_traduccion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Traduccion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Traduccion entity.
     *
     * @Route("/{id}", name="common_traduccion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Traduccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Traduccion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Traduccion entity.
     *
     * @Route("/{id}/edit", name="common_traduccion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Traduccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Traduccion entity.');
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
    * Creates a form to edit a Traduccion entity.
    *
    * @param Traduccion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Traduccion $entity)
    {
        $form = $this->createForm(new TraduccionType(), $entity, array(
            'action' => $this->generateUrl('common_traduccion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Traduccion entity.
     *
     * @Route("/{id}", name="common_traduccion_update")
     * @Method("PUT")
     * @Template("cobeCommonBundle:Traduccion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Traduccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Traduccion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('common_traduccion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Traduccion entity.
     *
     * @Route("/{id}", name="common_traduccion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeCommonBundle:Traduccion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Traduccion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('common_traduccion'));
    }

    /**
     * Creates a form to delete a Traduccion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('common_traduccion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
