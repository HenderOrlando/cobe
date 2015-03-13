<?php

namespace cobe\CurriculosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\CurriculosBundle\Entity\CentroEstudio;
use cobe\CurriculosBundle\Form\CentroEstudioType;

/**
 * CentroEstudio controller.
 *
 * @Route("/curriculos_centroEstudio")
 */
class CentroEstudioController extends Controller
{

    /**
     * Lists all CentroEstudio entities.
     *
     * @Route("/", name="curriculos_centroEstudio")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeCurriculosBundle:CentroEstudio')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new CentroEstudio entity.
     *
     * @Route("/", name="curriculos_centroEstudio_create")
     * @Method("POST")
     * @Template("cobeCurriculosBundle:CentroEstudio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CentroEstudio();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('curriculos_centroEstudio_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CentroEstudio entity.
     *
     * @param CentroEstudio $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CentroEstudio $entity)
    {
        $form = $this->createForm(new CentroEstudioType(), $entity, array(
            'action' => $this->generateUrl('curriculos_centroEstudio_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CentroEstudio entity.
     *
     * @Route("/new", name="curriculos_centroEstudio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CentroEstudio();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CentroEstudio entity.
     *
     * @Route("/{id}", name="curriculos_centroEstudio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:CentroEstudio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CentroEstudio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CentroEstudio entity.
     *
     * @Route("/{id}/edit", name="curriculos_centroEstudio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:CentroEstudio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CentroEstudio entity.');
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
    * Creates a form to edit a CentroEstudio entity.
    *
    * @param CentroEstudio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CentroEstudio $entity)
    {
        $form = $this->createForm(new CentroEstudioType(), $entity, array(
            'action' => $this->generateUrl('curriculos_centroEstudio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CentroEstudio entity.
     *
     * @Route("/{id}", name="curriculos_centroEstudio_update")
     * @Method("PUT")
     * @Template("cobeCurriculosBundle:CentroEstudio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:CentroEstudio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CentroEstudio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('curriculos_centroEstudio_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CentroEstudio entity.
     *
     * @Route("/{id}", name="curriculos_centroEstudio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeCurriculosBundle:CentroEstudio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CentroEstudio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('curriculos_centroEstudio'));
    }

    /**
     * Creates a form to delete a CentroEstudio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('curriculos_centroEstudio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
