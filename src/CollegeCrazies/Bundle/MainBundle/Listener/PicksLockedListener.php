<?php

namespace CollegeCrazies\Bundle\MainBundle\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContextInterface;

class PicksLockedListener
{
    const PICKS_LOCK_SESSION_KEY = 'picks_locked_time';
    private $om;
    private $session;

    public function __construct(ObjectManager $om, Session $session)
    {
        $this->om      = $om;
        $this->session = $session;
    }

    public function onKernelRequest()
    {
        if (!$this->session->has(self::PICKS_LOCK_SESSION_KEY)) {
            $firstLockedString = $this->om->createQuery('SELECT min(g.gameDate) FROM CollegeCraziesMainBundle:Game g')->getSingleScalarResult();
            $firstLocked = new \DateTime($firstLockedString);
            $firstLocked->modify('-5 minutes');
            $this->session->set(self::PICKS_LOCK_SESSION_KEY, $firstLocked);
        }
    }
}
