<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\EventListener;

use FOS\UserBundle\Event\FormEvent;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use SofaChamps\Bundle\BowlPickemBundle\EventListener\RegistrationListener;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use Symfony\Component\Form\FormInterface;

class RegistrationListenerTest extends SofaChampsTest
{
    protected $router;
    protected $seasonManager;
    protected $picksLockedManager;
    protected $listener;

    protected function setUp()
    {
        parent::setUp();

        $this->router = $this->buildMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $this->seasonManager = $this->buildMock('SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager');
        $this->picksLockedManager = $this->buildMock('SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager');

        $this->listener = new RegistrationListener($this->router, $this->seasonManager, $this->picksLockedManager);
    }

    public function testOnRegistrationSuccess()
    {
        $form = $this->buildMock('Symfony\Component\Form\Form');
        $request = $this->buildMock('Symfony\Component\HttpFoundation\Request');

        $this->router->expects($this->any())
            ->method('generate')
            ->will($this->returnValue($this->getFaker()->url()));

        $this->picksLockedManager->expects($this->any())
            ->method('arePickLocked')
            ->will($this->returnValue(false));

        $event = new FormEvent($form, $request);
        $this->listener->onRegistrationSuccess($event);
    }

    public function testOnRegistrationSuccessAfterPicksLockedDoesNotRedirect()
    {
        $this->picksLockedManager->expects($this->any())
            ->method('arePickLocked')
            ->will($this->returnValue(true));

        $event = $this->buildMock('FOS\UserBundle\Event\FormEvent');

        $event->expects($this->never())
            ->method('setResponse');

        $this->listener->onRegistrationSuccess($event);
    }
}
