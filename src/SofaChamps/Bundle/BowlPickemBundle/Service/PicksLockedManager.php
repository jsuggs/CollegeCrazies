<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use SofaChamps\Bundle\BowlPickemBundle\Listener\PicksLockedListener;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class PicksLockedManager
{
    const PICKS_LOCK_SESSION_KEY = 'picks_locked';

    private $om;
    private $session;

    public function __construct(ObjectManager $om, Session $session)
    {
        $this->om      = $om;
        $this->session = $session;
    }

    public function arePickLocked()
    {
        if (!$this->session->has(self::PICKS_LOCK_SESSION_KEY)) {
            $firstLockedString = $this->om->createQuery('SELECT min(g.gameDate) FROM SofaChampsBowlPickemBundle:Game g')->getSingleScalarResult();
            $firstLocked = new \DateTime($firstLockedString);
            $firstLocked->modify('-5 minutes');
            $this->session->set(self::PICKS_LOCK_SESSION_KEY, $firstLocked < new \DateTime());
        }

        return $this->session->get(self::PICKS_LOCK_SESSION_KEY);
    }
}
