<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

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
     * @Template("SofaChampsBowlPickemBundle:Stat:importance.html.twig")
     */
    public function importantGamesAction()
    {
        if (!$this->picksLocked()) {
            $this->get('session')->getFlashBag()->set('warning', 'Feature not available until picks lock');
            return $this->redirect('/');
        }

        $games = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:Game')
            ->gamesByImportance();

        return array(
            'games' => $games,
        );
    }

    /**
     * @Route("/leaderboard", name="site_leaderboard")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:Stat:leaderboard.html.twig")
     */
    public function leaderboardAction()
    {
        if (!$this->picksLocked()) {
            $this->get('session')->getFlashBag()->set('warning', 'Feature not available until picks lock');
            return $this->redirect('/');
        }

        $pickSets = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:PickSet')
            ->findAllOrderedByPoints();

        $pickSets = $this->get('pickset.sorter')->sortPickSets($pickSets);

        return array(
            'pickSets' => $pickSets,
        );
    }
}
