<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\User;

class UserSorter
{
    protected $pickSetSorter;

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
