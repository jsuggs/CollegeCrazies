<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\UserFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends Controller
{
    /**
     * @Route("/user/profile/{username}", name="user_profile")
     * @Template("CollegeCraziesMainBundle:User:profile.html.twig")
     */
    public function profileAction($username)
    {
        $user = $this->findByUsername($username);
        return array(
            'user' => $user
        );
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

    private function findUser($username)
    {
        $user = $this->getRepository('Team')->findByUsername($username);
        if (!$user) {
            throw new \NotFoundHttpException(sprintf('There was no user with username = %s', $username));
        }
        return $user;
    }
}
