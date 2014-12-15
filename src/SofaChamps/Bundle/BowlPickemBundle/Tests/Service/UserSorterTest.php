<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Service;

use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Service\UserSorter;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;

class UserSorterTest extends SofaChampsTest
{
    protected $pickSetSorter;
    protected $userSorter;

    protected function setUp()
    {
        $this->pickSetSorter = $this->buildMock('SofaChamps\Bundle\BowlPickemBundle\Service\PickSetSorter');
        $this->userSorter = new UserSorter($this->pickSetSorter);
    }

    public function testSortUsersByPoints()
    {
        $users = array();
        $pickSets = array();

        $season = new Season(2000);
        $league = new League();
        $league->setSeason($season);
        $users[] = $user3 = $this->newUser(3, $league);
        $users[] = $user1 = $this->newUser(1, $league);
        $users[] = $user2 = $this->newUser(2, $league);

        $pickSets[] = $user1PickSet = $this->newPickSet($league, $user1);
        $pickSets[] = $user2PickSet = $this->newPickSet($league, $user2);
        $pickSets[] = $user3PickSet = $this->newPickSet($league, $user3);

        $validPickSets = array($user3PickSet, $user1PickSet, $user2PickSet);
        $sortedPickSets = array($user1PickSet, $user2PickSet, $user3PickSet);

        $this->pickSetSorter->expects($this->any())
            ->method('sortPickSets')
            ->with($validPickSets)
            ->will($this->returnValue($sortedPickSets));

        $sortedUsers = $this->userSorter->sortUsersByPoints($users, $league);
    }

    protected function newUser($id, League $league)
    {
        $user = new User();
        $user->setId($id);
        $league->addUser($user);

        return $user;
    }

    protected function newPickSet(League $league, User $user)
    {
        $pickSet = new PickSet();
        $pickSet->setUser($user);
        $pickSet->addLeague($league);

        return $pickSet;
    }
}

