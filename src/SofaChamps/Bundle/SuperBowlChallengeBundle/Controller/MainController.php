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
        $config = $this->getConfig();

        return array(
            'config' => $config,
        );
    }

    /**
     * @Route("/leaderboard", name="sbc_leaderboard")
     * @Template
     */
    public function leaderboardAction()
    {
        $picks = $this->getPicksOrderedByScore();

        return array(
            'picks' => $picks,
        );
    }
}
