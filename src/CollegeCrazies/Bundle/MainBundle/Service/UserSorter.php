<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\League;

class UserSorter
{
    public function sortUsersByPoints($users, League $league)
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
        foreach ($rankedUsers as $points) {
            foreach ($points as $point) {
                $sortedUsers[] = $point;
            }
        }

        return $sortedUsers;
    }
}
