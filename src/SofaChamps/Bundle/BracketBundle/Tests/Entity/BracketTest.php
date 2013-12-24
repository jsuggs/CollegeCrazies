<?php

namespace SofaChamps\Bundle\BracketBundle\Tests\Entity;

use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;

class BracketTest extends BracketBundleTest
{
    public function testBracket()
    {
        $bracket = new Bracket();

        $game = new BracketGame($bracket, rand(0, 100));

        $this->assertContains($game, $bracket->getGames());
    }
}
