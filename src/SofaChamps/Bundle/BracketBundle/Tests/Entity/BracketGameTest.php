<?php

namespace SofaChamps\Bundle\BracketBundle\Tests\Entity;

use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;

class BracketGameTest extends BracketBundleTest
{
    public function testConstructor()
    {
        $bracket = new Bracket();
        $round = rand(0, 100);
        $parent = $this->getNewBracketGame();

        $game = new BracketGame($bracket, $round, $parent);

        $this->assertEquals($bracket, $game->getBracket());
        $this->assertEquals($round, $game->getRound());
        $this->assertEquals($parent, $game->getParent());
    }

    public function testChild()
    {
        $game = $this->getNewBracketGame();
        $this->assertEquals(0, $game->getChildren()->count());

        $child = $this->getNewBracketGame();
        $game->addChild($child);
        $this->assertContains($child, $game->getChildren());
    }

    protected function getNewBracketGame()
    {
        return new BracketGame(new Bracket(), rand(0, 100));
    }
}
