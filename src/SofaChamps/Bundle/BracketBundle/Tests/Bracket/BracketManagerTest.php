<?php

namespace SofaChamps\Bundle\BracketBundle\Tests\Bracket;

use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;
use SofaChamps\Bundle\BracketBundle\Tests\Bracket;
use SofaChamps\Bundle\BracketBundle\Tests\BracketBundleTest;
use SofaChamps\Bundle\BracketBundle\Tests\BracketManager;

class BracketManagerTest extends BracketBundleTest
{
    protected $om;
    protected $dispatcher;
    protected $bracketManager;

    protected function setUp()
    {
        parent::setUp();

        $this->om = $this->buildMock('Doctrine\Common\Persistence\ObjectManager');
        $this->dispatcher = $this->buildMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->bracketManager = new BracketManager($this->om, $this->dispatcher);
    }

    public function testCreateBracket()
    {
        $this->markTestIncomplete('Not now');
        $bracket = new Bracket();
        $this->bracketManager->createBracketGames($bracket, 5);

        $this->assertEquals(63, count($bracket->getGames()));
    }
}
