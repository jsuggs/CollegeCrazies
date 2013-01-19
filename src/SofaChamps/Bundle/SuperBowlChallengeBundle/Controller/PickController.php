<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;

/**
 * @Route("/pick")
 */
class PickController extends BaseController
{
    /**
     * @Route("/{year}/new", name="sbc_pick_new")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function newAction($year)
    {
        $pick = new Pick();
        $pick->setYear($year);

        $form = $this->getPickForm($pick);

        return array(
            'form' => $form->createView(),
            'year' => $year,
        );
    }

    /**
     * @Route("/create", name="sbc_pick_create")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsSuperBowlChallengeBundle:Pick:new.html.twig")
     */
    public function createAction()
    {
        $form = $this->getPickForm();
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $pick = $form->getData();
            $pick->setUser($this->getUser());

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($pick);
            $em->flush($pick);

            $this->get('session')->getFlashBag()->set('success', 'Pick Saved');

            return $this->redirect($this->generateUrl('sbc_pick_edit', array(
                'pickId' => $pick->getId(),
            )));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/edit/{pickId}", name="sbc_pick_edit")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function editAction($pickId)
    {
        $pick = $this->findPick($pickId);
        $form = $this->getPickForm($pick);

        return array(
            'pick' => $pick,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{pickId}", name="sbc_pick_update")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsSuperBowlChallengeBundle:Pick:edit.html.twig")
     */
    public function updateAction($pickId)
    {
        $pick = $this->findPick($pickId);
        $form = $this->getPickForm($pick);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($pick);
            $em->flush($pick);

            $this->get('session')->getFlashBag()->set('success', 'Pick updated');

            return $this->redirect($this->generateUrl('sbc_pick_edit', array(
                'pickId' => $pickId,
            )));
        }

        return array(
            'pick' => $pick,
            'form' => $form->createView(),
        );
    }
}
