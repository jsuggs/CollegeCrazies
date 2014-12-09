<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;

/**
 * @Route("{season}/game")
 */
class GameController extends BaseController
{
    /**
     * @Route("/list", name="_bp_game_sidebar")
     * @Template("SofaChampsBowlPickemBundle:Game:list.html.twig")
     * @Cache(expires="+5 minutes")
     */
    public function listAction(Season $season)
    {
        $em = $this->getEntityManager();

        //TODO - Move to repo methods
        $upcomingQuery = $em->createQuery('SELECT g FROM SofaChamps\Bundle\BowlPickemBundle\Entity\Game g
            WHERE g.homeTeamScore is null
            AND g.season = :season
            ORDER BY g.gameDate')->setParameter('season', $season)->setMaxResults(5);
        $upcoming = $upcomingQuery->getResult();

        $completedQuery = $em->createQuery('SELECT g FROM SofaChamps\Bundle\BowlPickemBundle\Entity\Game g
            WHERE g.homeTeamScore is not null
            AND g.season = :season
            ORDER BY g.gameDate desc')->setParameter('season', $season);
        $completed = $completedQuery->getResult();

        return array(
            'upcoming' => $upcoming,
            'completed' => $completed,
        );
    }
}
