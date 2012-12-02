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

    /**
     * @Route("/how-to", name="howto")
     * @Template("CollegeCraziesMainBundle::howto.html.twig")
     */
    public function howtoAction()
    {
        return array();
    }

    /**
     * @Route("/about", name="about")
     * @Template("CollegeCraziesMainBundle::about.html.twig")
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Route("/donate", name="donate")
     * @Template("CollegeCraziesMainBundle::donate.html.twig")
     */
    public function donateAction()
    {
        return array();
    }

    /**
     * @Route("/distribution", name="distribution")
     * @Template("CollegeCraziesMainBundle::distribution.html.twig")
     */
    public function distributionAction()
    {
        $this->get('session')->setFlash('warning', 'Feature not available until picks lock');
        return $this->redirect('/');
        return array();
    }

    /**
     * @Route("/leaders", name="leaders")
     * @Template("CollegeCraziesMainBundle::leaders.html.twig")
     */
    public function leadersAction()
    {
        $this->get('session')->setFlash('warning', 'Feature not available until picks lock');
        return $this->redirect('/');
        return array();
    }
}
