<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomePageController extends Controller
{
    /**
     * @Route("/")
     */
    public function homepageAction()
    {
        $user = $this->getUser();

        $template = $this->get('security.context')->isGranted('ROLE_USER')
            ? 'CollegeCraziesMainBundle::homepage.auth.html.twig'
            : 'CollegeCraziesMainBundle::homepage.unauth.html.twig';

        $form = $this->createForm(new UserFormType());
        return $this->render($template , array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }
}
