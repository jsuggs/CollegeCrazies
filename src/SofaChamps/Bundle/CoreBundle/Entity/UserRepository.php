<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;

class UserRepository extends EntityRepository
{
    const USERS_INCOMPLETE_PICKSETS_SQL =<<<EOF
SELECT distinct u.username, u.email
FROM picks p
INNER JOIN picksets ps ON p.pickset_id = ps.id
INNER JOIN users u on ps.user_id = u.id
WHERE team_id IS NULL
AND ps.season = :season
EOF;

    const USERS_NOLEAGUE_SQL = <<<EOF
SELECT DISTINCT u.username, u.email
FROM users u
WHERE u.id NOT IN (
  SELECT ul.user_id
  FROM user_league ul
  JOIN leagues l ON ul.league_id = l.id
  WHERE l.season = :season
)
AND u.id IN (
  SELECT p.user_id
  FROM picksets p
  WHERE p.season = :season
)
EOF;

    const USERS_LEAGUE_PICKSET_NOT_ASSOC_SQL = <<<EOF
SELECT DISTINCT u.username, u.email
FROM users u
WHERE u.id IN (
  SELECT ul.user_id
  FROM user_league ul
  JOIN leagues l ON ul.league_id = l.id
  WHERE l.season = :season
)
AND u.id IN (
  SELECT p.user_id
  FROM picksets p
  WHERE p.season = :season
)
AND u.id NOT IN (
  SELECT p.user_id
  FROM pickset_leagues pl
  INNER JOIN picksets p on pl.pickset_id = p.id
  WHERE p.season = :season
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
            ->createQuery('SELECT COUNT(g.id) FROM SofaChampsBowlPickemBundle:Game g')
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

    public function getUsersAndPicksetsForLeague(League $league, $season)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u, p from SofaChampsCoreBundle:User u
                JOIN u.pickSets p
                JOIN u.leagues l
                JOIN p.picks pk
                JOIN pk.game pg
                WHERE l.id = :leagueId
                ORDER BY pg.id'
            )
            ->setParameter('leagueId', $league->getId())
            ->getResult();
    }

    public function getUsersWithIncompletePicksets($season)
    {
        return $this->getEntityManager()
            ->getConnection()
            ->fetchAll(self::USERS_INCOMPLETE_PICKSETS_SQL, array('season' => $season));
    }

    public function getUsersWithNoLeague($season)
    {
        return $this->getEntityManager()
            ->getConnection()
            ->fetchAll(self::USERS_NOLEAGUE_SQL, array('season' => $season));
    }

    public function getUsersWithAPickSetAndALeagueButNoPickSetAssociated($season)
    {
        return $this->getEntityManager()
            ->getConnection()
            ->fetchAll(self::USERS_LEAGUE_PICKSET_NOT_ASSOC_SQL, array('season' => $season));
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
