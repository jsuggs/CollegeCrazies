<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\RequestProcessor;

use FOS\UserBundle\Event\FormEvent;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use SofaChamps\Bundle\BowlPickemBundle\RequestProcessor\BowlPickemRequestProcessor;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use Symfony\Component\Form\FormInterface;

class BowlPickemRequestProcessorTest extends SofaChampsTest
{
    protected $router;
    protected $om;
    protected $seasonManager;
    protected $picksLockedManager;
    protected $processor;

    protected function setUp()
    {
        parent::setUp();

        $this->router = $this->buildMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $this->om = $this->buildMock('Doctrine\Common\Persistence\ObjectManager');
        $this->seasonManager = $this->buildMock('SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager');
        $this->picksLockedManager = $this->buildMock('SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager');

        $this->processor = new BowlPickemRequestProcessor($this->router, $this->om, $this->seasonManager, $this->picksLockedManager);
    }

    public function testOnRegistrationSuccess()
    {
        $this->markTestIncomplete('Not now');
        $form = $this->buildMock('Symfony\Component\Form\Form');
        $request = $this->buildMock('Symfony\Component\HttpFoundation\Request');
        $season = rand(2000, 2020);

        $this->router->expects($this->any())
            ->method('generate')
            ->will($this->returnValue($this->getFaker()->url()));

        $this->seasonManager->expects($this->any())
            ->method('getCurrentSeason')
            ->will($this->returnValue($season));

        $this->picksLockedManager->expects($this->any())
            ->method('arePickLocked')
            ->with($season)
            ->will($this->returnValue(false));

        $event = new FormEvent($form, $request);
        $this->processor->onRegistrationSuccess($event);
    }

    public function testOnRegistrationSuccessAfterPicksLockedDoesNotRedirect()
    {
        $this->markTestIncomplete('Not now');
        $season = rand(2000, 2020);

        $this->seasonManager->expects($this->any())
            ->method('getCurrentSeason')
            ->will($this->returnValue($season));

        $this->picksLockedManager->expects($this->any())
            ->method('arePickLocked')
            ->with($season)
            ->will($this->returnValue(true));

        $event = $this->buildMock('FOS\UserBundle\Event\FormEvent');

        $event->expects($this->never())
            ->method('setResponse');

        $this->processor->onRegistrationSuccess($event);
    }
}
