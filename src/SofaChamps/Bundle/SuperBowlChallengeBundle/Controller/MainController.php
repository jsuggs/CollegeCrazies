<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/{year}")
 */
class MainController extends BaseController
{
    /**
     * @Route("/", name="sbc_home")
     * @Template
     */
    public function homeAction($year)
    {
        return array(
            'config' => $this->getConfig($year),
            'user' => $this->getUser(),
            'year' => $year,
        );
    }

    /**
     * @Route("/leaderboard", name="sbc_leaderboard")
     * @Template
     */
    public function leaderboardAction($year)
    {
        $picks = $this->getRepository('SofaChampsSuperBowlChallengeBundle:Pick')
            ->findPicksForYearOrderedByScore($year);

        return array(
            'config' => $this->getConfig($year),
            'picks' => $picks,
            'user' => $this->getUser(),
            'year' => $year,
        );
    }
}
