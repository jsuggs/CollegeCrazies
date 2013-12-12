<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Service;

use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\CoreBundle\Entity\User;

class LeagueTest extends \PHPUnit_Framework_TestCase
{
    public function testPublic()
    {
        $league = new League();
        $this->assertTrue($league->isPublic());
        $league->setPassword('test');
        $this->assertFalse($league->isPublic());
        $league->setPassword(' ');
        $this->assertTrue($league->isPublic());
    }

    public function testAddRemoveUsers()
    {
        $league = new League();
        $this->assertEquals(0, $league->getUsers()->count());

        $user1 = new User();
        $league->addUser($user1);
        $this->assertEquals(1, $league->getUsers()->count());
        $this->assertContains($user1, $league->getUsers());

        $user2 = new User();
        $league->addUser($user2);
        $this->assertEquals(2, $league->getUsers()->count());
        $this->assertContains($user2, $league->getUsers());

        $league->addUser($user1);
        $this->assertEquals(2, $league->getUsers()->count());

        $league->removeUser($user1);
        $this->assertEquals(1, $league->getUsers()->count());
        $this->assertContains($user2, $league->getUsers());
        $this->assertNotContains($user1, $league->getUsers());
    }
}
