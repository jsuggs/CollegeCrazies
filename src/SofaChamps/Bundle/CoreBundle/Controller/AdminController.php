<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends CoreController
{
    /**
     * @Route("/admin/user/list", name="core_admin_users")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function usersAction()
    {
        $users = $this->getRepository('SofaChampsCoreBundle:User')->findAll();

        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/admin/user/makeadmin/{userId}", name="core_admin_user_admin")
     * @ParamConverter("user", class="SofaChampsMainBundle:User", options={"id" = "userId"})
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

        $this->getEntityManager()->flush($user);

        return $this->redirect($this->generateURL('core_admin_users'));
    }
}
