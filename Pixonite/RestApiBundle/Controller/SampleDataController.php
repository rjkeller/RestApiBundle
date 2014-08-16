<?php

namespace Pixonite\RestApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pixonite\RestApiBundle\Entity\SampleData;
use Pixonite\RestApiBundle\Form\SampleDataType;

/**
 * This is the sample controller for a REST api that I quickly cooked up.
 *
 * @author R.J. Keller <rjkeller-fun@pixonite.com>
 * @Route("/api/v1/sample-data")
 */
class SampleDataController extends Controller
{

    /**
     * Lists all SampleData entities.
     *
     * @Route("/", name="api_sample-data")
     * @Route("/", name="api_sample-data2")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PixoniteRestApiBundle:SampleData')->findAll();

        return new Response(json_encode(array(
            'entities' => $entities,
        )));
    }
    /**
     * Creates a new SampleData entity.
     *
     * @Route("/", name="api_sample-data_create")
     * @Route("", name="api_sample-data_create2")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity = new SampleData();
        $form = $this->createCreateForm($entity);
        $form->submit($_POST, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return new Response(json_encode(array(
                "response" => "Success!",
                "entity" => $entity,
            )));
        }


        return new Response(json_encode(array(
            'entity' => $entity,
        )));
    }

    /**
     * Finds and displays a SampleData entity.
     *
     * @Route("/{id}", name="api_sample-data_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PixoniteRestApiBundle:SampleData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SampleData entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return new Response(json_encode(array(
            'entity'      => $entity,
        )));
    }


    /**
     * Deletes a SampleData entity.
     *
     * @Route("/{id}", name="api_sample-data_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PixoniteRestApiBundle:SampleData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SampleData entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response(json_encode([
            "response" => "Success!",
            "id" => $id,
        ]));
    }

    //----------- FORMS FOR VARIOUS REST FUNCTIONS -----------//


    /**
     * Creates a form to create a SampleData entity.
     *
     * @param SampleData $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SampleData $entity)
    {
        $form = $this->createForm(new SampleDataType(), $entity, array(
            'action' => $this->generateUrl('api_sample-data_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to delete a SampleData entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(null, array('csrf_protection' => false))
            ->setAction($this->generateUrl('api_sample-data_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
