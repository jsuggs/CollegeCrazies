<?php

namespace SofaChamps\Bundle\SquaresBundle\Tests\Game;

use SofaChamps\Bundle\SquaresBundle\Game\GameManager;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;

class GameManagerTest extends SofaChampsTest
{
    protected $om;
    protected $manager;

    protected function setUp()
    {
        $this->om = $this->buildMock('Doctrine\Common\Persistence\ObjectManager');

        $this->manager = new GameManager($this->om);
    }

    public function testCreateGame()
    {
        $game = $this->manager->createGame();
        $this->assertEquals(100, $game->getSquares()->count());
    }
}


