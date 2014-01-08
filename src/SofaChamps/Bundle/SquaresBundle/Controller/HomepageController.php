<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomepageController extends BaseController
{
    /**
     * @Route("/", name="squares_home")
     * @Template
     */
    public function homepageAction()
    {
        return array();
    }
}
