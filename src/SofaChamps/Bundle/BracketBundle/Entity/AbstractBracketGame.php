<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractGame;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;

/**
 * The basis for a BracketGame
 * The id and the relationships for bracket, parent and child need to be defined in the concrete class
 */
abstract class AbstractBracketGame extends AbstractGame implements BracketGameInterface
{
    protected $bracket;
    protected $parent;
    protected $child;

    public function __construct(BracketInterface $bracket, BracketGameInterface $parent = null)
    {
        $this->bracket = $bracket;
        $bracket->addGame($this);
        if ($parent) {
            $this->parent = $parent;
        }
        $this->children = new ArrayCollection();
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
