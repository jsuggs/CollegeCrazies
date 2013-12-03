<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Module;

use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;
use SofaChamps\Bundle\CoreBundle\Module\ModuleInterface;

class BowlPickemModule implements ModuleInterface
{
    const MODULE_ID = 'bowlpickem';

    protected $season;
    protected $picksLockedManager;

    public function __construct($season, PicksLockedManager $picksLockedManager)
    {
        $this->season = $season;
        $this->picksLockedManager = $picksLockedManager;
    }

    public function getModuleId()
    {
        return self::MODULE_ID;
    }

    public function getActiveConfigId()
    {
        return $this->season;
    }

    public function getStartTime()
    {
        // TODO?
        return new \DateTime();
    }

    public function getEndTime()
    {
        return $this->picksLockedManager->getLockTime($this->season);
    }
}
