<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Service;

use Doctrine\Common\Collections\ArrayCollection;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Pick;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksetComparer;
use SofaChamps\Bundle\BowlPickemBundle\Service\UserSorter;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use SofaChamps\Bundle\NCAABundle\Entity\Team;

class PickSetComparerTest extends SofaChampsTest
{
    protected $om;
    protected $gameRepo;
    protected $tiebreakerGame;
    protected $pickSetComparer;

    protected function setUp()
    {
        $this->om = $this->buildMock('Doctrine\Common\Persistence\ObjectManager');
        $this->gameRepo = $this->buildMock('SofaChamps\Bundle\BowlPickemBundle\Entity\GameRepository');
        $this->om->expects($this->any())
            ->method('getRepository')
            ->with('SofaChampsBowlPickemBundle:Game')
            ->will($this->returnValue($this->gameRepo));
        $this->tiebreakerGame = new Game();
        $this->tiebreakerGame->setHomeTeamScore(42);
        $this->tiebreakerGame->setAwayTeamScore(30);

        $this->gameRepo->expects($this->any())
            ->method('findTiebreakerGamesForSeason')
            ->will($this->returnValue(new ArrayCollection(array($this->tiebreakerGame))));

        $this->pickSetComparer = new PickSetComparer($this->om);
    }

    /**
     * @dataProvider getPickSets
     */
    public function testComparePicksets($a, $b, $expectedWinner, $season)
    {
        $this->assertEquals($expectedWinner, $this->pickSetComparer->comparePicksets($a, $b, $season));
    }

    public function getPickSets()
    {
        $year = rand(2000, 2020);
        $season = new Season($year);
        $users = array();
        $users[] = $user1 = new User();
        $users[] = $user2 = new User();
        $users[] = $user3 = new User();
        $users[] = $user4 = new User();
        $users[] = $user5 = new User();
        $users[] = $user6 = new User();

        $game1Winner = new Team();
        $game1Winner->setId(1);
        $game1Loser = new Team();
        $game1Loser->setId(2);

        $game1 = new Game();
        $game1->setHomeTeam($game1Winner);
        $game1->setAwayTeam($game1Loser);
        $game1->setHomeTeamScore(21);
        $game1->setAwayTeamScore(14);

        $game2 = new Game();
        $game2->setHomeTeam($game1Winner);
        $game2->setAwayTeam($game1Loser);
        $game2->setHomeTeamScore(21);
        $game2->setAwayTeamScore(14);

        $league = new League();
        $league->addUser($user1);
        $league->addUser($user2);
        $league->addUser($user3);
        $league->addUser($user4);
        $league->setSeason($season);

        $pickWinner3Conf = new Pick();
        $pickWinner3Conf->setGame($game1);
        $pickWinner3Conf->setTeam($game1Winner);
        $pickWinner3Conf->setConfidence(3);
        $pickWinner2Conf = new Pick();
        $pickWinner2Conf->setGame($game1);
        $pickWinner2Conf->setTeam($game1Winner);
        $pickWinner2Conf->setConfidence(2);
        $pickLoser3Conf = new Pick();
        $pickLoser3Conf->setGame($game1);
        $pickLoser3Conf->setTeam($game1Loser);
        $pickLoser3Conf->setConfidence(3);

        $user1PickSet = new PickSet();
        $user1PickSet->setUser($user1);
        $user1PickSet->addPick($pickWinner3Conf);
        $user1PickSet->addLeague($league);
        $user1PickSet->setTiebreakerHomeTeamScore(38);
        $user1PickSet->setTiebreakerAwayTeamScore(28);

        $user2PickSet = new PickSet();
        $user2PickSet->setUser($user2);
        $user2PickSet->addPick($pickWinner2Conf);
        $user2PickSet->addLeague($league);

        $user3PickSet = new PickSet();
        $user3PickSet->setUser($user3);
        $user3PickSet->addPick($pickLoser3Conf);
        $user3PickSet->addLeague($league);

        $user4PickSet = new PickSet();
        $user4PickSet->setUser($user4);
        $user4PickSet->addPick($pickWinner3Conf);
        $user4PickSet->addLeague($league);

        $user5PickSet = new PickSet();
        $user5PickSet->setUser($user5);
        $user5PickSet->addPick($pickWinner3Conf);
        $user5PickSet->addLeague($league);
        $user5PickSet->setTiebreakerHomeTeamScore(38);
        $user5PickSet->setTiebreakerAwayTeamScore(28);

        $user6PickSet = new PickSet();
        $user6PickSet->setUser($user6);
        $user6PickSet->addPick($pickWinner3Conf);
        $user6PickSet->addLeague($league);
        $user5PickSet->setTiebreakerHomeTeamScore(18);
        $user5PickSet->setTiebreakerAwayTeamScore(28);

        return array(
            array($user1PickSet, $user2PickSet, -1,  $season),
            array($user2PickSet, $user1PickSet, 1, $season),
            array($user1PickSet, $user4PickSet, -1,  $season),
            array($user5PickSet, $user6PickSet, -1,  $season),
            array($user6PickSet, $user5PickSet, 1, $season),
        );
    }
}

