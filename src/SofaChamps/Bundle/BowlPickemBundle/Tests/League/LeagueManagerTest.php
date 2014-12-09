<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\League;

use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Event\LeagueEvent;
use SofaChamps\Bundle\BowlPickemBundle\Event\LeagueEvents;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\League\LeagueManager;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;

class LeagueManagerTest extends SofaChampsTest
{
    protected $om;
    protected $dispatcher;
    protected $manager;

    protected function setUp()
    {
        $this->om = $this->buildMock('Doctrine\Common\Persistence\ObjectManager');
        $this->dispatcher = $this->buildMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->manager = new LeagueManager($this->om, $this->dispatcher);
    }

    public function testCreateLeague()
    {
        $year = rand(2000, 3000);
        $season = new Season($year);

        $league = new League();
        $league->setSeason($season);

        $this->om->expects($this->once())
            ->method('persist')
            ->with($league);

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with(LeagueEvents::LEAGUE_CREATED, new LeagueEvent($league));

        $this->manager->createLeague($season);
    }
}

