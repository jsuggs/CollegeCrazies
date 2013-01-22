<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT u from CollegeCrazies\Bundle\MainBundle\Entity\User u ORDER BY u.id');
        $users = $query->getResult();

        return array(
            'users' => $users
        );
    }
}
