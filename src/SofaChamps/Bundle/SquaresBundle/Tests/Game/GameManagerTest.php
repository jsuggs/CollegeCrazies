<?php

namespace SofaChamps\Bundle\SquaresBundle\Tests\Game;

use SofaChamps\Bundle\SquaresBundle\Game\GameManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
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
        $user = new User();

        $game = $this->manager->createGame($user);

        $this->assertEquals(100, $game->getSquares()->count());
        $this->assertEquals($user, $game->getUser());
    }
}


