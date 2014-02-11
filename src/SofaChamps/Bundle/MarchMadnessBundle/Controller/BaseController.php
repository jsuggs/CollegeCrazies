<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Controller\CoreController;

class BaseController extends CoreController
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
