<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

class UserSorter
{
    public function sortUsersByPoints($users)
    {
        // I am sure there is a MUCH better way to do this...
        // Just for reference, having to get the values from the obj
        // since not stored in the db to let it do the sorting
        $rankedUsers = array();
        foreach ($users as $user) {
            $rankedUsers[$user->getPickSet()->getPoints()][] = $user;
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
