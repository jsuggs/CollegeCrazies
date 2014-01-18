<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Controller\CoreController;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use SofaChamps\Bundle\SquaresBundle\Form\GameEditFormType;
use SofaChamps\Bundle\SquaresBundle\Form\GameFormType;
use SofaChamps\Bundle\SquaresBundle\Form\GameInviteFormType;
use SofaChamps\Bundle\SquaresBundle\Form\GameMapFormType;
use SofaChamps\Bundle\SquaresBundle\Form\GamePayoutsFormType;
use SofaChamps\Bundle\SquaresBundle\Form\PlayerFormType;

class BaseController extends CoreController
{
    protected function getGameManager()
    {
        return $this->get('sofachamps.squares.game_manager');
    }

    protected function getInviteManager()
    {
        return $this->get('sofachamps.squares.invite_manager');
    }

    protected function getPlayerForm(Player $player)
    {
        return $this->createForm('sofachamps_squares_player', $player);
    }

    protected function getGameForm(Game $game = null)
    {
        return $this->createForm(new GameFormType(), $game);
    }

    protected function getGameEditForm(Game $game)
    {
        return $this->createForm(new GameEditFormType(), $game);
    }

    protected function getGameMapForm(Game $game = null)
    {
        return $this->createForm(new GameMapFormType(), $game);
    }

    protected function getGamePayoutsForm(Game $game = null)
    {
        return $this->createForm(new GamePayoutsFormType(), $game);
    }

    protected function getInviteForm(Game $game)
    {
        return $this->createForm(new GameInviteFormType());
    }
}
