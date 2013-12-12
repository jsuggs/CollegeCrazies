<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use Symfony\Component\EventDispatcher\Event;

class LeagueEvent extends Event
{
    protected $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    public function getLeague()
    {
        return $this->league;
    }
}
