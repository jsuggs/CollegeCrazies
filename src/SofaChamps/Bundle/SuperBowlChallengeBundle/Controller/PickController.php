<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
                $this->addMessage('warning', 'Picks are now locked');
                return $this->redirect($this->generateUrl('sbc_home'));
            }

            $form->bind($request);
            if ($form->isValid()) {
                $pick->setYear($year);
                $em = $this->getEntityManager();
                $em->persist($pick);
                $em->flush();

                $this->addMessage('success', 'Pick Saved');

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

    /**
     * @Route("/{year}/{pickId}/view", name="sbc_pick_view")
     * @ParamConverter("pick", class="SofaChampsSuperBowlChallengeBundle:Pick", options={"id" = "pickId"})
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function viewAction(Pick $pick, $year)
    {
        $manager = $this->get('sofachamps.superbowlchallenge.pickmanager');

        if (!$manager->picksViewable($year)) {
            $this->addMessage('warning', 'Picks cannot be viewed until after picks are closed');
            return $this->redirect($this->generateUrl('sbc_home'));
        }

        $config = $this->getConfig($year);

        return array(
            'pick' => $pick,
            'year' => $year,
            'config' => $config,
        );
    }
}
