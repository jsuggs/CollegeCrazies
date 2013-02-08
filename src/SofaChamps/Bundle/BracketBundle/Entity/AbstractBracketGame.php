<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractGame;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;

/**
 * The basis for a BracketGame
 * The id and the relationships for bracket, parent and child need to be defined in the concrete class
 */
abstract class AbstractBracketGame extends AbstractGame implements BracketGameInterface
{
    public function setBracket(BracketInterface $bracket)
    {
        $this->bracket = $bracket;
    }

    public function getBracket()
    {
        return $this->bracket;
    }

    public function setParent(BracketGameInterface $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setChild(BracketGameInterface $child)
    {
        $this->child = $child;
    }

    public function getChild()
    {
        return $this->child;
    }
}
