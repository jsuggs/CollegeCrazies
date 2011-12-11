<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * @Route("/game/list", name="game_list")
     * @Template("CollegeCraziesMainBundle:Game:list.html.twig")
     */
    public function listAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->findAll();
        return array('games' => $games);
    }
}
