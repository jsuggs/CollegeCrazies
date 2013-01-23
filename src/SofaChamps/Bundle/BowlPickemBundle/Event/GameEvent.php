<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use Symfony\Component\EventDispatcher\Event;

class GameEvent extends Event
{
    protected $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getGame()
    {
        return $this->game;
    }
}
