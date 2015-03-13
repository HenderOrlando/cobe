<?php

namespace cobe\EstadisticasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\EstadisticasBundle\Entity\Estadistica;
use cobe\EstadisticasBundle\Form\EstadisticaType;

/**
 * Estadistica controller.
 *
 * @Route("/estadisticas_Estadistica")
 */
class EstadisticaController extends Controller
{

    /**
     * Lists all Estadistica entities.
     *
     * @Route("/", name="estadisticas_Estadistica")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeEstadisticasBundle:Estadistica')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Estadistica entity.
     *
     * @Route("/", name="estadisticas_Estadistica_create")
     * @Method("POST")
     * @Template("cobeEstadisticasBundle:Estadistica:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Estadistica();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('estadisticas_Estadistica_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Estadistica entity.
     *
     * @param Estadistica $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Estadistica $entity)
    {
        $form = $this->createForm(new EstadisticaType(), $entity, array(
            'action' => $this->generateUrl('estadisticas_Estadistica_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Estadistica entity.
     *
     * @Route("/new", name="estadisticas_Estadistica_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Estadistica();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Estadistica entity.
     *
     * @Route("/{id}", name="estadisticas_Estadistica_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeEstadisticasBundle:Estadistica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estadistica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Estadistica entity.
     *
     * @Route("/{id}/edit", name="estadisticas_Estadistica_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeEstadisticasBundle:Estadistica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estadistica entity.');
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
    * Creates a form to edit a Estadistica entity.
    *
    * @param Estadistica $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Estadistica $entity)
    {
        $form = $this->createForm(new EstadisticaType(), $entity, array(
            'action' => $this->generateUrl('estadisticas_Estadistica_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Estadistica entity.
     *
     * @Route("/{id}", name="estadisticas_Estadistica_update")
     * @Method("PUT")
     * @Template("cobeEstadisticasBundle:Estadistica:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeEstadisticasBundle:Estadistica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estadistica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('estadisticas_Estadistica_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Estadistica entity.
     *
     * @Route("/{id}", name="estadisticas_Estadistica_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeEstadisticasBundle:Estadistica')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Estadistica entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('estadisticas_Estadistica'));
    }

    /**
     * Creates a form to delete a Estadistica entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('estadisticas_Estadistica_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
