<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
    /**
     * @Route("/user/profile/{username}", name="user_profile")
     * @ParamConverter("user", class="SofaChampsCoreBundle:User", options={"username" = "username"})
     * @Template
     */
    public function profileAction(User $user)
    {
        return array(
            'user' => $user,
        );
    }
}
