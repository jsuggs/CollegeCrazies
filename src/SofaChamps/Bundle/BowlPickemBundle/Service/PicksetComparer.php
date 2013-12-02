<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;

/**
 * PicksetComparer
 *
 * @DI\Service("sofachamps.bp.pickset_comparer")
 */
class PicksetComparer
{
    /**
     * Return 1 if $a has more points, -1 if b has more points, 0 if they are exactly equal
     *
     * @param PickSet $a The first PickSet to compare
     * @param PickSet $b The second PickSet to compare
     *
     * @return int
     */
    public function comparePicksets(PickSet $a, PickSet $b)
    {
        $aPoints = $a->getPoints();
        $bPoints = $b->getPoints();

        // If same points, fall back first to points possible
        if ($aPoints === $bPoints) {
            $aPointsPossible = $a->getPointsPossible();
            $bPointsPossible = $b->getPointsPossible();

            if ($aPointsPossible === $bPointsPossible) {
                // TODO - Check the tiebreaker
                return 0;
            }

            return $aPointsPossible >= $bPointsPossible ? -1 : 1;
        }

        return $aPoints >= $bPoints ? -1 : 1;
    }
}
