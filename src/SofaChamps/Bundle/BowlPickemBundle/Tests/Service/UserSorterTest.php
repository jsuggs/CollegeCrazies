<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Service;

use SofaChamps\Bundle\BowlPickemBundle\Service\PickSetSorter;
use SofaChamps\Bundle\BowlPickemBundle\Service\UserSorter;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Pick;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Team;
use SofaChamps\Bundle\BowlPickemBundle\Entity\User;

class UserSorterTest extends \PHPUnit_Framework_TestCase
{
    protected $userSorter;

    protected function setUp()
    {
        $this->userSorter = new UserSorter(new PickSetSorter());
    }

    public function testSortUsersByPoints()
    {
        $users = array();
        $users[] = $user1 = new User();
        $users[] = $user2 = new User();
        $users[] = $user3 = new User();

        $game1Winner = new Team();
        $game1Winner->setId(1);
        $game1Loser = new Team();
        $game1Loser->setId(2);

        $game1 = new Game();
        $game1->setHomeTeam($game1Winner);
        $game1->setAwayTeam($game1Loser);
        $game1->setHomeTeamScore(21);
        $game1->setAwayTeamScore(14);

        $league = new League();
        $league->addUser($user1);

        $pick1 = new Pick();
        $pick1->setGame($game1);
        $pick1->setTeam($game1Winner);
        $pick1->setConfidence(3);
        $user1PickSet = new PickSet();
        $user1PickSet->setUser($user1);
        $user1PickSet->addPick($pick1);
        $user1PickSet->addLeague($league);

        $pick2 = new Pick();
        $pick2->setGame($game1);
        $pick2->setTeam($game1Winner);
        $pick2->setConfidence(2);
        $user2PickSet = new PickSet();
        $user2PickSet->setUser($user2);
        $user2PickSet->addPick($pick2);
        $user2PickSet->addLeague($league);

        $pick3 = new Pick();
        $pick3->setGame($game1);
        $pick3->setTeam($game1Loser);
        $pick3->setConfidence(3);
        $user3PickSet = new PickSet();
        $user3PickSet->setUser($user3);
        $user3PickSet->addPick($pick3);
        $user3PickSet->addLeague($league);

        list($user1Rank, $x) = $this->userSorter->sortUsersByPoints($users, $user1, $league);
        $this->assertEquals(1, $user1Rank);

        list($user2Rank, $x) = $this->userSorter->sortUsersByPoints($users, $user2, $league);
        $this->assertEquals(2, $user2Rank);

        list($user3Rank, $x) = $this->userSorter->sortUsersByPoints($users, $user3, $league);
        $this->assertEquals(3, $user3Rank);
    }
}

