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

    /**
     * Sort the users by their points according to the pickSetSorter algorithm for the specififed league
     *
     * @param array $users
     * @param User $userToRank
     * @param League $league
     * @return void
     */
    public function sortUsersByPoints(array $users, League $league)
    {
        $validPickSets = $this->getValidPickSetsForLeague($league, $users);
        $sortedPickSets = $this->pickSetSorter->sortPickSets($validPickSets, $league->getSeason());

        return array_map(function ($pickSet) {
            return $pickSet->getUser();
        }, $sortedPickSets);

        return array($userRank, $sortedUsers);
    }

    public function getUserRank(User $user, array $sortedUsers)
    {
        // array_search returns a 0 based index
        return array_search($user, $sortedUsers) + 1;
    }

    protected function getValidPickSetsForLeague(League $league, $users)
    {
        return array_map(function ($user) use ($league) {
            return $league->getPicksetForUser($user);
        }, $users);
    }
}
