<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function getGames()
    {
        return $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsMarchMadnessBundle:Game')
            ->findAll();
    }

    public function getBracket()
    {
        return $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsMarchMadnessBundle:Bracket')
            ->findAll();
    }
}
