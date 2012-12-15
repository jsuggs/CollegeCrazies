<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;

class PickSetSorter
{
    public function sortPickSets($pickSets, $tieBreakerHomeScore = null, $tieBreakerAwayScore = null)
    {
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
