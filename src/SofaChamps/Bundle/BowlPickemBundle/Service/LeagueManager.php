<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;

/**
 * LeagueManager
 *
 * @DI\Service("sofachamps.bp.league_manager")
 */
class LeagueManager
{
    public function __construct()
    {
    }

    public function addUserToLeague(League $league, User $user)
    {
    }

    public function removeUserFromLeague(League $league, User $user)
    {
        // Move to repo methods
        $em->getConnection()->executeUpdate('DELETE FROM pickset_leagues WHERE league_id = ? AND pickset_id IN (SELECT id FROM picksets WHERE user_id = ?)', array($league->getId(), $user->getId()));
        $em->getConnection()->executeUpdate('DELETE FROM user_league WHERE league_id = ? AND user_id = ?', array($league->getId(), $user->getId()));
    }
}
