<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * UserSorter
 *
 * @DI\Service("sofachamps.bp.user_sorter")
 */
class UserSorter
{
    protected $pickSetSorter;

    /**
     * @DI\InjectParams({
     *      "pickSetSorter" = @DI\Inject("sofachamps.bp.pickset_sorter")
     * })
     */
    public function __construct(PickSetSorter $pickSetSorter)
    {
        $this->pickSetSorter = $pickSetSorter;
    }

    public function sortUsersByPoints($users, User $userToRank, League $league)
    {
        $pickSets = array_map(function ($user) use ($league) {
            return $league->getPicksetForUser($user);
        }, $users);

        $pickSets = $this->pickSetSorter->sortPickSets($pickSets);

        $sortedUsers = array();
        $rank = 1;
        $userRank = 0;
        foreach ($pickSets as $pickSet) {
            $pickSetUser = $pickSet->getUser();
            if ($userToRank == $pickSetUser) {
                $userRank = $rank;
            }
            $sortedUsers[] = $pickSetUser;
            $rank++;
        }

        return array($userRank, $sortedUsers);
    }
}
