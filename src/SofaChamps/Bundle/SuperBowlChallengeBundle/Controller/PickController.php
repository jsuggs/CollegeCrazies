<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;

/**
 * @Route("{year}/pick")
 */
class PickController extends BaseController
{
    /**
     * @Route(name="sbc_pick")
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template("SofaChampsSuperBowlChallengeBundle:Pick:edit.html.twig")
     */
    public function editPickAction($year)
    {
        $user = $this->getUser();
        $pick = $this->getUserPick($user, $year);
        $form = $this->getPickForm($pick);

        return array(
            'pick' => $pick,
            'year' => $year,
            'config' => $this->getConfig($year),
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     * @Route(name="sbc_pick_update")
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @Template("SofaChampsSuperBowlChallengeBundle:Pick:edit.html.twig")
     */
    public function updatePickAction($year)
    {
        $user = $this->getUser();
        $pick = $this->getUserPick($user, $year);
        $form = $this->getPickForm($pick);

        $config = $this->getConfig($year);
        $now = new \DateTime();
        if ($now > $config->getCloseTime()) {
            $this->addMessage('warning', 'Picks are now locked');
            return $this->redirect($this->generateUrl('sbc_home', array(
                'year' => $year,
            )));
        }

        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($pick);
            $em->flush();

            $this->addMessage('success', 'Pick Saved');

            return $this->redirect($this->generateUrl('sbc_pick', array(
                'year' => $year,
            )));
        }

        return array(
            'pick' => $pick,
            'year' => $year,
            'config' => $this->getConfig($year),
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     * @Route("/{pickId}/view", name="sbc_pick_view")
     * @ParamConverter("pick", class="SofaChampsSuperBowlChallengeBundle:Pick", options={"id" = "pickId"})
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function viewAction(Pick $pick, $year)
    {
        $manager = $this->getPickManager();

        if (!$manager->picksViewable($year)) {
            $this->addMessage('warning', 'Picks cannot be viewed until after picks are closed');
            return $this->redirect($this->generateUrl('sbc_home', array(
                'year' => $year,
            )));
        }

        return array(
            'pick' => $pick,
            'year' => $year,
            'config' => $this->getConfig($year),
        );
    }
}
