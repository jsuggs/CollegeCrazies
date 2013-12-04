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
    protected $listener;

    protected function setUp()
    {
        $this->router = $this->buildMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $this->seasonManager = $this->buildMock('SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager');
        $this->listener = new RegistrationListener($this->router, $this->seasonManager);
    }

    public function testOnRegistrationSuccess()
    {
        $form = $this->buildMock('Symfony\Component\Form\Form');
        $request = $this->buildMock('Symfony\Component\HttpFoundation\Request');

        $event = new FormEvent($form, $request);
        $this->listener->onRegistrationSuccess($event);
    }
}
