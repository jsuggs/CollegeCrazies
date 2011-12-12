<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomePageController extends Controller
{
    /**
     * @Route("/")
     * @Template("CollegeCraziesMainBundle::homepage.html.twig")
     */
    public function homepageAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if ($user == 'anon.') {
        }

        $form = $this->createForm(new UserFormType());
        return array(
            'form' => $form->createView(),
            'user' => $user
        );
    }
}
