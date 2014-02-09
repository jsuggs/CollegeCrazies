<?php

namespace SofaChamps\Bundle\BracketBundle\Tests\Entity;

use SofaChamps\Bundle\BracketBundle\Tests\Bracket;
use SofaChamps\Bundle\BracketBundle\Tests\BracketGame;
use SofaChamps\Bundle\BracketBundle\Tests\BracketBundleTest;

class BracketGameTest extends BracketBundleTest
{
    public function testConstructor()
    {
        $bracket = new Bracket();
        $parent = $this->getNewBracketGame();

        $game = new BracketGame($bracket, $parent);

        $this->assertEquals($bracket, $game->getBracket());
        $this->assertEquals($parent, $game->getParent());
    }

    public function testChild()
    {
        $game = $this->getNewBracketGame();
        $this->assertNull($game->getChild());

        $child = $this->getNewBracketGame();
        $game->setChild($child);
        $this->assertEquals($child, $game->getChild());
    }

    protected function getNewBracketGame()
    {
        return new BracketGame(new Bracket());
    }
}
