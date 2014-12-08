<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Season;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * SeasonManager
 *
 * @DI\Service("sofachamps.bp.season_manager")
 */
class SeasonManager
{
    public function getCurrentSeason()
    {
        // TODO
        return 2014;
    }
}
