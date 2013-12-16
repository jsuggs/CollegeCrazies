<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Service;

use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use SofaChamps\Bundle\NCAABundle\Entity\Team;

class GameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider gameData
     */
    public function testGetFavorite(Game $game, Team $favorite, Team $underdog)
    {
        $this->assertEquals($favorite, $game->getFavorite());
        $this->assertEquals($underdog, $game->getUnderdog());
    }

    public function gameData()
    {
        $homeTeam = new Team();
        $homeTeam->setId('home');
        $homeTeam->setName('home');

        $awayTeam = new Team();
        $awayTeam->setId('away');
        $awayTeam->setName('away');

        $game1 = new Game();
        $game1->setName('Game 1');
        $game1->setHomeTeam($homeTeam);
        $game1->setAwayTeam($awayTeam);
        $game1->setSpread(1);

        $game2 = new Game();
        $game2->setName('Game 2');
        $game2->setHomeTeam($homeTeam);
        $game2->setAwayTeam($awayTeam);
        $game2->setSpread(-1);

        $game3 = new Game();
        $game3->setName('Game 3');
        $game3->setHomeTeam($homeTeam);
        $game3->setAwayTeam($awayTeam);
        $game3->setSpread(0);

        return array(
            array($game1, $awayTeam, $homeTeam),
            array($game2, $homeTeam, $awayTeam),
            array($game3, $homeTeam, $awayTeam),
        );
    }
}

