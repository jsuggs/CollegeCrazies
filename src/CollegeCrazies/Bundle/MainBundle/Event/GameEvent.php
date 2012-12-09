<?php

namespace CollegeCrazies\Bundle\MainBundle\Event;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
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
