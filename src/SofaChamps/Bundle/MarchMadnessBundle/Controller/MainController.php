<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/{season}")
 */
class MainController extends BaseController
{
    /**
     * @Route("/", name="mm_home")
     * @Template
     */
    public function indexAction($season)
    {
        return array();
    }
}
