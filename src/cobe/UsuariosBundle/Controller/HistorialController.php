<?php

namespace cobe\UsuariosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\UsuariosBundle\Entity\Historial;
use cobe\UsuariosBundle\Form\HistorialType;

/**
 * Historial controller.
 *
 * @Route("/usuarios_historial")
 */
class HistorialController extends Controller
{

    /**
     * Lists all Historial entities.
     *
     * @Route("/", name="usuarios_historial")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeUsuariosBundle:Historial')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Historial entity.
     *
     * @Route("/", name="usuarios_historial_create")
     * @Method("POST")
     * @Template("cobeUsuariosBundle:Historial:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Historial();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usuarios_historial_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Historial entity.
     *
     * @param Historial $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Historial $entity)
    {
        $form = $this->createForm(new HistorialType(), $entity, array(
            'action' => $this->generateUrl('usuarios_historial_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Historial entity.
     *
     * @Route("/new", name="usuarios_historial_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Historial();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Historial entity.
     *
     * @Route("/{id}", name="usuarios_historial_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeUsuariosBundle:Historial')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Historial entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Historial entity.
     *
     * @Route("/{id}/edit", name="usuarios_historial_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeUsuariosBundle:Historial')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Historial entity.');
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
    * Creates a form to edit a Historial entity.
    *
    * @param Historial $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Historial $entity)
    {
        $form = $this->createForm(new HistorialType(), $entity, array(
            'action' => $this->generateUrl('usuarios_historial_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Historial entity.
     *
     * @Route("/{id}", name="usuarios_historial_update")
     * @Method("PUT")
     * @Template("cobeUsuariosBundle:Historial:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeUsuariosBundle:Historial')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Historial entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('usuarios_historial_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Historial entity.
     *
     * @Route("/{id}", name="usuarios_historial_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeUsuariosBundle:Historial')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Historial entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usuarios_historial'));
    }

    /**
     * Creates a form to delete a Historial entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuarios_historial_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
