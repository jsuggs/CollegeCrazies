<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Entity\Person;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/person")
 */
class PersonController extends BaseController
{
    /**
     * @Route("/new", name="core_person_new")
     * @Method({"GET"})
     * @Template
     */
    public function newAction()
    {
        $form = $this->getPersonForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="core_person_create")
     * @Method({"POST"})
     * @Template("SofaChampsCoreBundle:Person:new.html.twig")
     */
    public function createAction()
    {
        $form = $this->getPersonForm();
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $person = $form->getData();
            $this->getEntityManager()->persist($person);
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('core_person_view', array(
                'id' => $person->getId(),
            )));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/view/{id}", name="core_person_view")
     * @Method({"GET"})
     * @ParamConverter("game", class="SofaChampsCoreBundle:Person", options={"id" = "id"})
     * @Template
     */
    public function viewAction(Person $person)
    {
        return array(
            'person' => $person,
        );
    }

    /**
     * @Route("/edit/{id}", name="core_person_edit")
     * @Method({"GET"})
     * @ParamConverter("game", class="SofaChampsCoreBundle:Person", options={"id" = "id"})
     * @Template("SofaChampsCoreBundle:Person:edit.html.twig")
     */
    public function editAction(Person $person)
    {
        $form = $this->getPersonForm($person);

        return array(
            'person' => $person,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{id}", name="core_person_update")
     * @Method({"POST"})
     * @ParamConverter("game", class="SofaChampsCoreBundle:Person", options={"id" = "id"})
     * @Template("SofaChampsCoreBundle:Person:edit.html.twig")
     */
    public function updateAction(Person $person)
    {
        $form = $this->getPersonForm($person);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('core_person_edit', array(
                'id' => $person->getId(),
            )));
        }

        return array(
            'person' => $person,
            'form' => $form->createView(),
        );
    }
}
