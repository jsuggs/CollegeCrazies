<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends BaseController
{
    /**
     * @Route("/", name="sbc_home")
     * @Template
     */
    public function homeAction()
    {
        return array(
            'config' => $this->getConfig(),
            'user' => $this->getUser(),
            'year' => $this->get('config.curyear'),
        );
    }

    /**
     * @Route("/leaderboard/{year}", name="sbc_leaderboard")
     * @Template
     */
    public function leaderboardAction($year)
    {
        $picks = $this->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Pick')
            ->findPicksForYearOrderedByScore($year);

        return array(
            'config' => $this->getConfig(),
            'picks' => $picks,
            'user' => $this->getUser(),
            'year' => $this->get('config.curyear'),
        );
    }
}
