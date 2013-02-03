<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/", name="mm_admin")
     * @Template
     */
    public function indexAction()
    {
        $games = $this->getGames();

        return array(
            'games' => $games,
        );
    }
}
