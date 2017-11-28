<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Season;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * SeasonManager
 *
 * @DI\Service("sofachamps.bp.season_manager")
 */
class SeasonManager
{
    private $em;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getCurrentSeason()
    {
        // TODO
        return $this->em->getRepository('SofaChampsBowlPickemBundle:Season')->find(2017);
    }
}
