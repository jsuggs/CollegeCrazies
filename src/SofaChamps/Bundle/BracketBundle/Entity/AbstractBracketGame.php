<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

use SofaChamps\Bundle\CoreBundle\Entity\AbstractGame;

/**
 * The basis for a BracketGame
 * The id needs to be defined in the concrete class
 */
abstract class AbstractBracketGame extends AbstractGame implements BracketGameInterface
{
    protected $bracket;

    public function __construct(BracketInterface $bracket)
    {
        $this->bracket = $bracket;
        $bracket->addGame($this);
    }

    public function getBracket()
    {
        return $this->bracket;
    }
}
