<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/pick")
 */
class PickController extends BaseController
{
    /**
     * @Route("/new")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->getUser();
        $form = $this->getPickForm();

        return array(
            'form' => $form->createView(),
        );
    }
}
