<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\User;

class UserSorter
{
    public function sortUsersByPoints($users, User $userToRank, League $league)
    {
        // I am sure there is a MUCH better way to do this...
        // Just for reference, having to get the values from the obj
        // since not stored in the db to let it do the sorting
        $rankedUsers = array();
        foreach ($users as $user) {
            $pickSet = $league->getPicksetForUser($user);
            if ($pickSet) {
                $rankedUsers[$pickSet->getPoints()][] = $user;
            }
        }
        krsort($rankedUsers);
        $sortedUsers = array();
        $rank = 1;
        $userRank = 0;
        foreach ($rankedUsers as $points => $users) {
            foreach ($users as $user) {
                if ($user == $userToRank) {
                    $userRank = $rank;
                }
                $sortedUsers[] = $user;
            }
            $rank++;
        }

        return array($userRank, $sortedUsers);
    }
}
