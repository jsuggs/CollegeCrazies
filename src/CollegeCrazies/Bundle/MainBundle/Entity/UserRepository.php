<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    const USERS_INCOMPLETE_PICKSETS_SQL =<<<EOF
SELECT distinct u.username, u.email
FROM picks p
INNER JOIN picksets ps ON p.pickset_id = ps.id
INNER JOIN users u on ps.user_id = u.id
WHERE team_id IS NULL
EOF;

    const USERS_NOLEAGUE_SQL = <<<EOF
SELECT DISTINCT u.username, u.email
FROM users u
WHERE u.id NOT IN (
  SELECT user_id
  FROM user_league
)
EOF;

    const LEAGUE_POTENTIAL_WINNERS_SQL = <<<EOF
SELECT DISTINCT u.id, u.username, u.email
FROM user_prediction_set_score s
INNER JOIN users u on s.user_id = u.id
WHERE league_id = ?
AND finish = 1
EOF;

    public function findUsersInLeague(League $league)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.leagues', 'l')
            ->where('l.id = :leagueId')
            ->setParameter('leagueId', $league->getId())
            ->getQuery()
            ->getResult();
    }

    public function findUsersInLeagueWithIncompletePicksets(League $league)
    {
        $numGames = $this
            ->getEntityManager()
            ->createQuery('SELECT COUNT(g.id) FROM CollegeCraziesMainBundle:Game g')
            ->getSingleScalarResult();

        $users = $this->createQueryBuilder('u')
            ->innerJoin('u.leagues', 'l')
            ->innerJoin('l.pickSets', 'lps')
            ->innerJoin('lps.picks', 'lp')
            ->where('l.id = :leagueId')
            ->setParameter('leagueId', $league->getId())
            ->getQuery()
            ->getResult();

        return array_filter($users, function($user) use ($numGames, $league) {
            // Make sure the user has a pickset for the league
            if (is_null($pickSet = $league->getPickSetForUser($user))) {
                return true;
            }

            // Make sure that user has a pick for all of the games
            $picks = array_filter(iterator_to_array($pickSet->getPicks()), function ($pick) {
                $team = $pick->getTeam();
                return isset($team);
            });
            return count($picks) < $numGames;
        });
    }

    public function getUsersWithIncompletePicksets()
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::USERS_INCOMPLETE_PICKSETS_SQL);
    }

    public function getUsersWithNoLeague()
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::USERS_INCOMPLETE_PICKSETS_SQL);
    }

    public function findPotentialWinersInLeague(League $league)
    {
        return $this->getEntityManager()
            ->getConnection()
            ->fetchAll(self::LEAGUE_POTENTIAL_WINNERS_SQL, array(
                $league->getId(),
            ));
    }
}
