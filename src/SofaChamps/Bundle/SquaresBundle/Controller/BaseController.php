<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Controller\CoreController;

class BaseController extends CoreController
{
    protected function getGameManager()
    {
        return $this->get('sofachamps.squares.game_manager');
    }
}
