<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;

/**
 * PickSetSorter
 *
 * @DI\Service("sofachamps.bp.pickset_sorter")
 */
class PickSetSorter
{
    private $pickSetComparer;

    /**
     * @DI\InjectParams({
     *      "pickSetComparer" = @DI\Inject("sofachamps.bp.pickset_comparer"),
     * })
     */
    public function __construct(PicksetComparer $pickSetComparer)
    {
        $this->pickSetComparer = $pickSetComparer;
    }

    public function sortPickSets($pickSets, $season, $reverseSort = true)
    {
        $pickSets = array_filter($pickSets, function($pickSet) {
            return isset($pickSet);
        });

        $comparer = $this->pickSetComparer;
        usort($pickSets, function(PickSet $a, PickSet $b) use ($comparer, $season) {
            return $comparer->comparePicksets($a, $b, $season);
        });

        if ($reverseSort) {
            array_reverse($pickSets);
        }

        return $pickSets;
    }
}
