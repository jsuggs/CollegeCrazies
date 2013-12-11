<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use Symfony\Component\EventDispatcher\Event;

class PickSetEvent extends Event
{
    protected $pickSet;

    public function __construct(PickSet $pickSet)
    {
        $this->pickSet = $pickSet;
    }

    public function getPickSet()
    {
        return $this->pickSet;
    }
}
