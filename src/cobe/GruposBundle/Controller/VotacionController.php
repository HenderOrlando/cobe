<?php

namespace cobe\GruposBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use cobe\GruposBundle\Entity\Votacion;
use cobe\GruposBundle\Form\VotacionType;

/**
 * Votacion controller.
 *
 * @Route("/grupos_votacion")
 */
class VotacionController extends Controller
{

    /**
     * Lists all Votacion entities.
     *
     * @Route("/", name="grupos_votacion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('cobeGruposBundle:Votacion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Votacion entity.
     *
     * @Route("/", name="grupos_votacion_create")
     * @Method("POST")
     * @Template("cobeGruposBundle:Votacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Votacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grupos_votacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Votacion entity.
     *
     * @param Votacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Votacion $entity)
    {
        $form = $this->createForm(new VotacionType(), $entity, array(
            'action' => $this->generateUrl('grupos_votacion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Votacion entity.
     *
     * @Route("/new", name="grupos_votacion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Votacion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Votacion entity.
     *
     * @Route("/{id}", name="grupos_votacion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeGruposBundle:Votacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Votacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Votacion entity.
     *
     * @Route("/{id}/edit", name="grupos_votacion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeGruposBundle:Votacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Votacion entity.');
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
    * Creates a form to edit a Votacion entity.
    *
    * @param Votacion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Votacion $entity)
    {
        $form = $this->createForm(new VotacionType(), $entity, array(
            'action' => $this->generateUrl('grupos_votacion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Votacion entity.
     *
     * @Route("/{id}", name="grupos_votacion_update")
     * @Method("PUT")
     * @Template("cobeGruposBundle:Votacion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('cobeGruposBundle:Votacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Votacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grupos_votacion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Votacion entity.
     *
     * @Route("/{id}", name="grupos_votacion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('cobeGruposBundle:Votacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Votacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grupos_votacion'));
    }

    /**
     * Creates a form to delete a Votacion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupos_votacion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
