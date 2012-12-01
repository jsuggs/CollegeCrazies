<?php

namespace CollegeCrazies\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends BaseController
{
    /**
     * Show the user
     *
     * @Secure(roles="ROLE_USER")
     * @Template("FOSUserBundle:Profile:show.html.twig")
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        return array(
            'user' => $user,
        );
    }
}
