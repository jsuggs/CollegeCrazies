<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\UserFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends Controller
{
    /**
     * @Route("/user/profile/{username}", name="user_profile")
     * @Template("CollegeCraziesMainBundle:User:profile.html.twig")
     */
    public function profileAction($username)
    {
        $user = $this->findUserByUsername($username);
        return array(
            'user' => $user
        );
    }

    /**
     * @Route("/admin/user/list", name="user_list")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("CollegeCraziesMainBundle:Admin:users.html.twig")
     */
    public function listAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT u from CollegeCrazies\Bundle\MainBundle\Entity\User u ORDER BY u.id');
        $users = $query->getResult();

        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/admin/users-incomplete-picksets", name="admin_user_incomplete_picksets")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("CollegeCraziesMainBundle:Admin:users-incomplete-picksets.html.twig")
     */
    public function incompletePicksetsAction()
    {
        $users = $this->get('doctrine.orm.entity_manager')->getRepository('CollegeCraziesMainBundle:User')->getUsersWithIncompletePicksets();
        $emailList = array_map(function($user) { return $user['email']; }, $users);

        return array(
            'users' => $users,
            'emailList' => $emailList,
        );
    }

    /**
     * @Route("/admin/user/makeadmin/{id}", name="user_admin")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function makeAdminAction($id)
    {
        $user = $this->findUser($id);

        $roles = $user->getRoles();
        //die(var_dump($roles));
        array_push($roles, 'ROLE_ADMIN');
        $roles = array_unique($roles);
        $user->setRoles($roles);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateURL('user_list'));
    }

    /**
     * @Route("/user/create", name="user_create")
     * @Template("CollegeCraziesMainBundle:User:new.html.twig")
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
            ->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\User')
            ->find($id);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('There was no user with id = %s', $id));
        }

        return $user;
    }

    private function findUserByUsername($username)
    {
        $user = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\User')
            ->findOneByUsername($username);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('There was no user with username = %s', $username));
        }

        return $user;
    }
}
