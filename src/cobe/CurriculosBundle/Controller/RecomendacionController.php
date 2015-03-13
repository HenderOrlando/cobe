<?php

namespace cobe\CurriculosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\CurriculosBundle\Entity\Recomendacion;
use cobe\CurriculosBundle\Form\RecomendacionType;

/**
 * Recomendacion controller.
 *
 * @Route("/curriculos_recomendacion")
 */
class RecomendacionController extends Controller
{

    /**
     * Lists all Recomendacion entities.
     *
     * @Route("/", name="curriculos_recomendacion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeCurriculosBundle:Recomendacion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Recomendacion entity.
     *
     * @Route("/", name="curriculos_recomendacion_create")
     * @Method("POST")
     * @Template("cobeCurriculosBundle:Recomendacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Recomendacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('curriculos_recomendacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Recomendacion entity.
     *
     * @param Recomendacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Recomendacion $entity)
    {
        $form = $this->createForm(new RecomendacionType(), $entity, array(
            'action' => $this->generateUrl('curriculos_recomendacion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Recomendacion entity.
     *
     * @Route("/new", name="curriculos_recomendacion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Recomendacion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Recomendacion entity.
     *
     * @Route("/{id}", name="curriculos_recomendacion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:Recomendacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recomendacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Recomendacion entity.
     *
     * @Route("/{id}/edit", name="curriculos_recomendacion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:Recomendacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recomendacion entity.');
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
    * Creates a form to edit a Recomendacion entity.
    *
    * @param Recomendacion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Recomendacion $entity)
    {
        $form = $this->createForm(new RecomendacionType(), $entity, array(
            'action' => $this->generateUrl('curriculos_recomendacion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Recomendacion entity.
     *
     * @Route("/{id}", name="curriculos_recomendacion_update")
     * @Method("PUT")
     * @Template("cobeCurriculosBundle:Recomendacion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeCurriculosBundle:Recomendacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recomendacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('curriculos_recomendacion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Recomendacion entity.
     *
     * @Route("/{id}", name="curriculos_recomendacion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeCurriculosBundle:Recomendacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Recomendacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('curriculos_recomendacion'));
    }

    /**
     * Creates a form to delete a Recomendacion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('curriculos_recomendacion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
