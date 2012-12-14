<?php

namespace CollegeCrazies\Bundle\MainBundle\Listener;

use CollegeCrazies\Bundle\MainBundle\Service\PicksLockedManager;
use Symfony\Component\HttpFoundation\Session\Session;

class PicksLockedListener
{
    private $manager;
    private $session;

    public function __construct(PicksLockedManager $manager, Session $session)
    {
        $this->manager = $manager;
        $this->session = $session;
    }

    public function onKernelRequest()
    {
        if (!$this->session->has(PicksLockedManager::PICKS_LOCK_SESSION_KEY)) {
            $this->session->set(PicksLockedManager::PICKS_LOCK_SESSION_KEY, $this->manager->arePickLocked());
        }
    }
}
