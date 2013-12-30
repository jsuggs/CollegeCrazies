<?php

namespace SofaChamps\Bundle\BracketBundle\Tests;

use SofaChamps\Bundle\BracketBundle\Bracket\AbstractBracketManager;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;

abstract class BracketBundleTest extends SofaChampsTest
{
}

class Bracket extends AbstractBracket
{
}

class BracketGame extends AbstractBracketGame
{
}

class BracketManager extends AbstractBracketManager
{
    public function getGameClass()
    {
        return get_class(new BracketGame(new Bracket(), rand(1,5)));
    }
}
