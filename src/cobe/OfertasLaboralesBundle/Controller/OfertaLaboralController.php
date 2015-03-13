<?php

namespace cobe\OfertasLaboralesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\OfertasLaboralesBundle\Entity\OfertaLaboral;
use cobe\OfertasLaboralesBundle\Form\OfertaLaboralType;

/**
 * OfertaLaboral controller.
 *
 * @Route("/ofertasLaborales_ofertaLaboral")
 */
class OfertaLaboralController extends Controller
{

    /**
     * Lists all OfertaLaboral entities.
     *
     * @Route("/", name="ofertasLaborales_ofertaLaboral")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeOfertasLaboralesBundle:OfertaLaboral')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OfertaLaboral entity.
     *
     * @Route("/", name="ofertasLaborales_ofertaLaboral_create")
     * @Method("POST")
     * @Template("cobeOfertasLaboralesBundle:OfertaLaboral:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new OfertaLaboral();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ofertasLaborales_ofertaLaboral_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a OfertaLaboral entity.
     *
     * @param OfertaLaboral $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(OfertaLaboral $entity)
    {
        $form = $this->createForm(new OfertaLaboralType(), $entity, array(
            'action' => $this->generateUrl('ofertasLaborales_ofertaLaboral_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new OfertaLaboral entity.
     *
     * @Route("/new", name="ofertasLaborales_ofertaLaboral_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OfertaLaboral();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OfertaLaboral entity.
     *
     * @Route("/{id}", name="ofertasLaborales_ofertaLaboral_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeOfertasLaboralesBundle:OfertaLaboral')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OfertaLaboral entity.
     *
     * @Route("/{id}/edit", name="ofertasLaborales_ofertaLaboral_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeOfertasLaboralesBundle:OfertaLaboral')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
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
    * Creates a form to edit a OfertaLaboral entity.
    *
    * @param OfertaLaboral $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(OfertaLaboral $entity)
    {
        $form = $this->createForm(new OfertaLaboralType(), $entity, array(
            'action' => $this->generateUrl('ofertasLaborales_ofertaLaboral_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing OfertaLaboral entity.
     *
     * @Route("/{id}", name="ofertasLaborales_ofertaLaboral_update")
     * @Method("PUT")
     * @Template("cobeOfertasLaboralesBundle:OfertaLaboral:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeOfertasLaboralesBundle:OfertaLaboral')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ofertasLaborales_ofertaLaboral_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OfertaLaboral entity.
     *
     * @Route("/{id}", name="ofertasLaborales_ofertaLaboral_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeOfertasLaboralesBundle:OfertaLaboral')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ofertasLaborales_ofertaLaboral'));
    }

    /**
     * Creates a form to delete a OfertaLaboral entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ofertasLaborales_ofertaLaboral_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
