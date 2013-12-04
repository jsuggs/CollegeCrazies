<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\CoreBundle\Util\Math\SigmaUtils;

/**
 * PicksetComparer
 *
 * @DI\Service("sofachamps.bp.pickset_comparer")
 */
class PicksetComparer
{
    private $om;
    private $tiebreakerGames = array();

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Return 1 if $a has more points, -1 if b has more points, 0 if they are exactly equal
     *
     * @param PickSet $a The first PickSet to compare
     * @param PickSet $b The second PickSet to compare
     *
     * @return int
     */
    public function comparePicksets(PickSet $a, PickSet $b, $season)
    {
        $aPoints = $a->getPoints();
        $bPoints = $b->getPoints();

        // If same points, fall back first to points possible
        if ($aPoints === $bPoints) {
            $aPointsPossible = $a->getPointsPossible();
            $bPointsPossible = $b->getPointsPossible();

            // If possible points are the same, check the tiebreakers
            if ($aPointsPossible === $bPointsPossible) {
                $tieBreakerGames = $this->getTiebreakerGamesForSeason($season);
                // TODO, we are currently only using one game for a tiebreaker
                $tieBreakerGame = $tieBreakerGames[0];

                $aTiebreakerPoints =
                    SigmaUtils::summation($a->getTiebreakerHomeTeamScore() - $tieBreakerGame->getHomeTeamScore())
                    +
                    SigmaUtils::summation($a->getTiebreakerAwayTeamScore() - $tieBreakerGame->getAwayTeamScore());

                $bTiebreakerPoints =
                    SigmaUtils::summation($b->getTiebreakerHomeTeamScore() - $tieBreakerGame->getHomeTeamScore())
                    +
                    SigmaUtils::summation($b->getTiebreakerAwayTeamScore() - $tieBreakerGame->getAwayTeamScore());

                if ($aTiebreakerPoints === $bTiebreakerPoints) {
                    return 0;
                } else {
                    return $aTiebreakerPoints > $bTiebreakerPoints ? -1 : 1;
                }
            }

            return $aPointsPossible > $bPointsPossible ? 1 : -1;
        }

        return $aPoints > $bPoints ? 1 : -1;
    }

    protected function getTiebreakerGamesForSeason($season)
    {
        if (!array_key_exists($season, $this->tiebreakerGames)) {
            $this->tiebreakerGames[$season] = $this->fetchTiebreakerGamesForSeason($season);
        }

        return $this->tiebreakerGames[$season];
    }

    private function fetchTiebreakerGamesForSeason($season)
    {
        return $this->om->getRepository('SofaChampsBowlPickemBundle:Game')->findTiebreakerGamesForSeason($season);
    }
}
