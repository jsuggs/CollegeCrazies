<?php

namespace SofaChamps\Bundle\BracketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BracketController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction($name)
    {
        $brackets = array();
        return array(
            'brackets' => $brackets,
        );
    }
}
