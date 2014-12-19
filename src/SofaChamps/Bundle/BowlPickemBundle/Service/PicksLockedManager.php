<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Listener\PicksLockedListener;
use SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * PicksLockedManager
 *
 * @DI\Service("sofachamps.bp.picks_locked_manager")
 */
class PicksLockedManager
{
    private $om;
    private $seasonManager;
    private $session;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "seasonManager" = @DI\Inject("sofachamps.bp.season_manager"),
     *      "session" = @DI\Inject("session"),
     * })
     */
    public function __construct(ObjectManager $om, SeasonManager $seasonManager, Session $session)
    {
        $this->om      = $om;
        $this->seasonManager = $seasonManager;
        $this->session = $session;
    }

    public function arePickLocked(Season $season)
    {
        if ($season->isLocked()) {
            return true;
        }

        return $this->getLockTime($season) < new \DateTime();
    }

    public function getLockTime(Season $season)
    {
        return $season->getPicksLockAt();
        $firstLockedString = $this->om->getRepository('SofaChampsBowlPickemBundle:Game')->getFirstGameDateForSeason($season);
        $firstLocked = new \DateTime($firstLockedString);
        $firstLocked->modify('-5 minutes');

        return $firstLocked;
    }
}
