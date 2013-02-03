<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends Controller
{
    /**
     * @Route("/", name="mm_home")
     * @Template
     */
    public function indexAction()
    {
        return array();
    }
}
