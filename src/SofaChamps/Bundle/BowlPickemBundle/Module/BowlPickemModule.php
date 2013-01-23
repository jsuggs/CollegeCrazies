<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Module;

use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;

class BowlPickemModule implements ModuleInterface
{
    const MODULE_ID = 'bowlpickem';

    protected $year;
    protected $picksLockedManager;

    public function __construct($year, PicksLockedManager $picksLockedManager)
    {
        $this->year = $year;
        $this->picksLockedManager = $picksLockedManager;
    }

    public function getModuleId()
    {
        return self::MODULE_ID;
    }

    public function getActiveConfigId()
    {
        return $this->year;
    }

    public function getStartTime()
    {
        // TODO?
        return new \DateTime();
    }

    public function getEndTime()
    {
        return $this->picksLockedManager->getLockTime($this->year);
    }
}
