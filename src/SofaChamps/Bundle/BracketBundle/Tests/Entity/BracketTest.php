<?php

namespace SofaChamps\Bundle\BracketBundle\Tests\Entity;

use SofaChamps\Bundle\BracketBundle\Tests\Bracket;
use SofaChamps\Bundle\BracketBundle\Tests\BracketGame;
use SofaChamps\Bundle\BracketBundle\Tests\BracketBundleTest;

class BracketTest extends BracketBundleTest
{
    public function testBracket()
    {
        $bracket = new Bracket();

        $game = new BracketGame($bracket);

        $this->assertContains($game, $bracket->getGames());
    }
}
