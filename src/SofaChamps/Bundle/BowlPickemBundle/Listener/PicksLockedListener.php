<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * PicksLockedListener
 *
 * @DI\Service
 * @DI\Tag("kernel.event_listener", attributes={"event"="kernel.request"})
 */
class PicksLockedListener
{
    private $manager;
    private $session;

    /**
     * @DI\InjectParams({
     *      "manager" = @DI\Inject("sofachamps.bp.picks_locked_manager"),
     *      "session" = @DI\Inject("session")
     * })
     */
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
