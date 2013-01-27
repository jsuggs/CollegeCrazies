<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Service;

use SofaChamps\Bundle\BowlPickemBundle\Entity\League;

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
}
