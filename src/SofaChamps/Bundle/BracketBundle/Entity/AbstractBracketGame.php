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
    protected $children;

    /**
     * ORM\Column(type="integer")
     */
    protected $season;

    /**
     * ORM\Column(type="integer")
     */
    protected $round;

    public function __construct(AbstractBracket $bracket, $round, BracketGameInterface $parent = null)
    {
        $this->bracket = $bracket;
        $this->round = $round;
        if ($parent) {
            $this->parent = $parent;
        }
        $this->children = new ArrayCollection();
    }

    public function getRound()
    {
        return $this->round;
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

    public function addChild(BracketGameInterface $child)
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
        }
    }

    public function getChildren()
    {
        return $this->children;
    }
}
