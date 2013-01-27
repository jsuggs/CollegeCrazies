<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;

class PickSetSorter
{
    public function sortPickSets($pickSets, $tieBreakerHomeScore = null, $tieBreakerAwayScore = null)
    {
        $pickSets = array_filter($pickSets, function($pickSet) {
            return isset($pickSet);
        });

        usort($pickSets, function(PickSet $a, PickSet $b) {
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
        });

        return $pickSets;
    }
}
