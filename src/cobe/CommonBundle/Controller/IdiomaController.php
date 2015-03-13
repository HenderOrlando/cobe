<?php

namespace cobe\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\CommonBundle\Entity\Idioma;
use cobe\CommonBundle\Form\IdiomaType;

/**
 * Idioma controller.
 *
 * @Route("/common_idioma")
 */
class IdiomaController extends Controller
{

    /**
     * Lists all Idioma entities.
     *
     * @Route("/", name="common_idioma")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeCommonBundle:Idioma')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Idioma entity.
     *
     * @Route("/", name="common_idioma_create")
     * @Method("POST")
     * @Template("cobeCommonBundle:Idioma:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Idioma();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('common_idioma_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Idioma entity.
     *
     * @param Idioma $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Idioma $entity)
    {
        $form = $this->createForm(new IdiomaType(), $entity, array(
            'action' => $this->generateUrl('common_idioma_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Idioma entity.
     *
     * @Route("/new", name="common_idioma_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Idioma();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Idioma entity.
     *
     * @Route("/{id}", name="common_idioma_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Idioma')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Idioma entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Idioma entity.
     *
     * @Route("/{id}/edit", name="common_idioma_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Idioma')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Idioma entity.');
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
    * Creates a form to edit a Idioma entity.
    *
    * @param Idioma $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Idioma $entity)
    {
        $form = $this->createForm(new IdiomaType(), $entity, array(
            'action' => $this->generateUrl('common_idioma_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Idioma entity.
     *
     * @Route("/{id}", name="common_idioma_update")
     * @Method("PUT")
     * @Template("cobeCommonBundle:Idioma:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCommonBundle:Idioma')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Idioma entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('common_idioma_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Idioma entity.
     *
     * @Route("/{id}", name="common_idioma_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeCommonBundle:Idioma')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Idioma entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('common_idioma'));
    }

    /**
     * Creates a form to delete a Idioma entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('common_idioma_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
