<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractGame;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;

/**
 * A BracketGame
 */
abstract class AbstractBracketGame extends AbstractGame implements BracketGameInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Bracket", inversedBy="games")
     */
    protected $bracket;

    /**
     * ORM\OneToOne(targetEntity="BracketGame")
     */
    protected $parent;

    /**
     * ORM\OneToOne(targetEntity="BracketGame")
     */
    protected $child;

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
