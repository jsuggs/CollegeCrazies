<?php

namespace SofaChamps\Bundle\SquaresBundle\Tests\Game;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use SofaChamps\Bundle\SquaresBundle\Game\GameManager;

class GameManagerTest extends SofaChampsTest
{
    protected $om;
    protected $playerManager;
    protected $logManager;
    protected $manager;

    protected function setUp()
    {
        $this->om = $this->buildMock('Doctrine\Common\Persistence\ObjectManager');
        $this->playerManager = $this->buildMock('SofaChamps\Bundle\SquaresBundle\Game\PlayerManager', array('createPlayer'));
        $this->logManager = $this->buildMock('SofaChamps\Bundle\SquaresBundle\Game\LogManager');

        $this->manager = new GameManager($this->om, $this->playerManager, $this->logManager);
    }

    public function testCreateGame()
    {
        $this->markTestIncomplete('Something weird happening here');
        $user = new User();

        $game = $this->manager->createGame($user);

        $this->playerManager->expects($this->once())
            ->method('createPlayer')
            ->with($user, $game, true)
            ->will($this->returnValue(new Player($user, $game)));

        $this->assertEquals(100, $game->getSquares()->count());
        $this->assertEquals($user, $game->getUser());
    }
}


