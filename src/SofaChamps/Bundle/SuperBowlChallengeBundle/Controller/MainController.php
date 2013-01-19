<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends BaseController
{
    /**
     * @Route("/")
     * @Template
     */
    public function homeAction()
    {
        $config = $this->getConfig();

        return array(
            'config' => $config,
        );
    }
}
