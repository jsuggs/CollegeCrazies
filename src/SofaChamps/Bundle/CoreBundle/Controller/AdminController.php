<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    /**
     * @Route("/admin/user/list", name="core_admin_users")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function usersAction()
    {
        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:User')
            ->findAll();

        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/admin/user/makeadmin/{userId}", name="core_admin_user_admin")
     * @ParamConverter("user", class="CollegeCraziesMainBundle:User", options={"id" = "userId"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function makeAdminAction(User $user)
    {
        $roles = $user->getRoles();

        // Push the admin role onto the user
        array_push($roles, 'ROLE_ADMIN');
        $roles = array_unique($roles);

        // Set the new roles back on the user
        $user->setRoles($roles);

        $this->get('doctrine.orm.entity_manager')->flush($user);

        return $this->redirect($this->generateURL('core_admin_users'));
    }
}
