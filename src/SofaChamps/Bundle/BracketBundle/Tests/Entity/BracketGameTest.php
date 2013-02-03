<?php

namespace SofaChamps\Bundle\BracketBundle\Tests\Entity;

use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;

class BracketGameTest extends BracketBundleTest
{
    public function testBracket()
    {
        $bracket = new Bracket();

        $game = new BracketGame();
        $this->assertNull($game->getBracket());

        $game->setBracket($bracket);
        $this->assertEquals($bracket, $game->getBracket());
    }

    public function testParent()
    {
        $game = new BracketGame();
        $this->assertNull($game->getParent());

        $parent = new BracketGame();
        $game->setParent($parent);
        $this->assertEquals($parent, $game->getParent());
    }

    public function testChild()
    {
        $game = new BracketGame();
        $this->assertNull($game->getChild());

        $child = new BracketGame();
        $game->setChild($child);
        $this->assertEquals($child, $game->getChild());
    }
}
