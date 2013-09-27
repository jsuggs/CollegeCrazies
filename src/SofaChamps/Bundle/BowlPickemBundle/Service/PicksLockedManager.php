<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Listener\PicksLockedListener;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * PicksLockedManager
 *
 * @DI\Service("sofachamps.bp.picks_locked_manager")
 */
class PicksLockedManager
{
    const PICKS_LOCK_SESSION_KEY = 'picks_locked';

    private $om;
    private $session;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "session" = @DI\Inject("session")
     * })
     */
    public function __construct(ObjectManager $om, Session $session)
    {
        $this->om      = $om;
        $this->session = $session;
    }

    public function arePickLocked()
    {
        if (!$this->session->has(self::PICKS_LOCK_SESSION_KEY)) {
            $lockTime = $this->getLockTime();
            $this->session->set(self::PICKS_LOCK_SESSION_KEY, $lockTime < new \DateTime());
        }

        return $this->session->get(self::PICKS_LOCK_SESSION_KEY);
    }

    public function getLockTime($year = null)
    {
        $firstLockedString = $this->om->createQuery('SELECT min(g.gameDate) FROM SofaChampsBowlPickemBundle:Game g')->getSingleScalarResult();
        $firstLocked = new \DateTime($firstLockedString);
        $firstLocked->modify('-5 minutes');

        return $firstLocked;
    }
}
