<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/stats")
 */
class StatController extends BaseController
{
    /**
     * @Route("/important-games", name="site_important_games")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:Stat:importance.html.twig")
     */
    public function importantGamesAction()
    {
        if (!$this->picksLocked()) {
            $this->get('session')->setFlash('warning', 'Feature not available until picks lock');
            return $this->redirect('/');
        }

        $games = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:Game')
            ->gamesByImportance();

        return array(
            'games' => $games,
        );
    }
}
