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

        $game = new BracketGame($bracket);

        $this->assertEquals($bracket, $game->getBracket());
    }

    protected function getNewBracketGame()
    {
        return new BracketGame(new Bracket());
    }
}
