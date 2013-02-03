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
     * @Route("/{year}/pick", name="sbc_pick")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function pickAction($year)
    {
        $user = $this->getUser();
        $pick = $this->getUserPick($user, $year);
        $form = $this->getPickForm($pick);

        $request = $this->getRequest();

        if ($request->getMethod() === 'POST') {
            $config = $this->getConfig($year);
            $now = new \DateTime();
            if ($now > $config->getCloseTime()) {
                $this->get('session')->getFlashBag()->set('warning', 'Picks are now locked');
                return $this->redirect($this->generateUrl('sbc_home'));
            }

            $form->bindRequest($request);
            if ($form->isValid()) {
                $pick->setYear($year);
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($pick);
                $em->flush();

                $this->get('session')->getFlashBag()->set('success', 'Pick Saved');

                return $this->redirect($this->generateUrl('sbc_pick', array(
                    'year' => $year,
                )));
            }
        }

        $config = $this->getConfig($year);

        return array(
            'pick' => $pick,
            'year' => $year,
            'config' => $config,
            'form' => $form->createView(),
            'user' => $user,
        );
    }
}
