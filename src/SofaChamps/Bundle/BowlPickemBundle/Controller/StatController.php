<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/{season}/stats")
 */
class StatController extends BaseController
{
    /**
     * @Route("/important-games", name="site_important_games")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:Stat:importance.html.twig")
     */
    public function importantGamesAction($season)
    {
        if (!$this->picksLocked($season)) {
            $this->addMessage('warning', 'Feature not available until picks lock');
            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season,
            )));
        }

        $games = $this->getRepository('SofaChampsBowlPickemBundle:Game')
            ->gamesByImportance($season);

        return array(
            'games' => $games,
            'season' => $season,
        );
    }

    /**
     * @Route("/leaderboard", name="site_leaderboard")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:Stat:leaderboard.html.twig")
     */
    public function leaderboardAction($season)
    {
        if (!$this->picksLocked($season)) {
            $this->addMessage('warning', 'Feature not available until picks lock');
            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season,
            )));
        }

        $pickSets = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')
            ->findAllOrderedByPoints($season);

        $pickSets = $this->getPicksetSorter()->sortPickSets($pickSets, $season);

        // Prefetch some data
        $this->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate($season);

        return array(
            'pickSets' => $pickSets,
            'season' => $season,
        );
    }

    protected function getPicksetSorter()
    {
        return $this->get('sofachamps.bp.pickset_sorter');
    }
}
