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
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($pick);
                $em->flush($pick);

                $this->get('session')->getFlashBag()->set('success', 'Pick Saved');
            }
        }

        $config = $this->getConfig($year);

        return array(
            'pick' => $pick,
            'year' => $year,
            'config' => $config,
            'form' => $form->createView(),
        );
    }
}
