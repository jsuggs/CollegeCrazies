<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\BowlPickemBundle\Form\UserFormType;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends Controller
{
    /**
     * @Route("/admin/users-incomplete-picksets", name="admin_user_incomplete_picksets")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SofaChampsBowlPickemBundle:Admin:users-incomplete-picksets.html.twig")
     */
    public function incompletePicksetsAction()
    {
        $users = $this->get('doctrine.orm.entity_manager')->getRepository('SofaChampsCoreBundle:User')->getUsersWithIncompletePicksets();
        $emailList = array_map(function($user) { return $user['email']; }, $users);

        return array(
            'users' => $users,
            'emailList' => $emailList,
        );
    }

    /**
     * @Route("/admin/users-noleague", name="admin_user_noleague")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SofaChampsBowlPickemBundle:Admin:users-noleague.html.twig")
     */
    public function noleagueAction()
    {
        $users = $this->get('doctrine.orm.entity_manager')->getRepository('SofaChampsCoreBundle:User')->getUsersWithNoLeague();
        $emailList = array_map(function($user) { return $user['email']; }, $users);

        return array(
            'users' => $users,
            'emailList' => $emailList,
        );
    }

    /**
     * @Route("/user/create", name="user_create")
     * @Template("SofaChampsBowlPickemBundle:User:new.html.twig")
     */
    public function createAction()
    {
        $user = new User();
        $form = $this->getUserForm($user);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $user = $form->getData();
            $user->setSalt(sha1(rand() . 'lets-get-random' . time()));

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('login'));
        } else {
            return array('form' => $form);
        }

        return $this->redirect($this->generateUrl('team_list'));
    }

    private function getUserForm(User $user)
    {
        return $this->createForm(new UserFormType(), $user);
    }

    private function findUser($id)
    {
        $user = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsCoreBundle:User')
            ->find($id);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('There was no user with id = %s', $id));
        }

        return $user;
    }
}
