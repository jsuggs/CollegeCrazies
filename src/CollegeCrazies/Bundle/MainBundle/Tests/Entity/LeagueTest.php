<?php

namespace CollegeCrazies\Bundle\MainBundle\Tests\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\League;

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
