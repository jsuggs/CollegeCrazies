<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="core_home")
     */
    public function homepageAction()
    {
        $user = $this->getUser();

        $template = $this->get('security.context')->isGranted('ROLE_USER')
            ? 'SofaChampsCoreBundle::homepage.auth.html.twig'
            : 'SofaChampsCoreBundle::homepage.unauth.html.twig';

        return $this->render($template , array(
            'user' => $user,
            'year' => $this->get('config.curyear'),
        ));
    }
}
