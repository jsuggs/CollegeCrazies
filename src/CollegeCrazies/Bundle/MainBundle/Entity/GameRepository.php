<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    const SITE_IMPORTANCE_SQL=<<<EOF
SELECT
    g.id
  , g.name
  , g.hometeam_id
  , g.awayteam_id
  , g.gamedate
  , stddev_pop(CASE WHEN p.team_id = g.hometeam_id THEN p.confidence WHEN p.team_id = g.awayteam_id THEN p.confidence * -1 END) as weightedstddev
  , sum(CASE WHEN p.team_id = g.hometeam_id THEN 1 ELSE 0 END) as homeTeamVotes
  , sum(CASE WHEN p.team_id = g.awayteam_id THEN 1 ELSE 0 END) as awayTeamVotes
  , count(p.team_id) as totalVotes
  , sum(CASE WHEN p.team_id IS NOT NULL THEN p.confidence ELSE 0 END) / sum(CASE WHEN p.team_id IS NOT NULL THEN 1 ELSE 0 END)::float as avgConfidence
  , avg(CASE WHEN p.team_id = g.hometeam_id THEN p.confidence ELSE null END) as avgHomeConfidence
  , avg(CASE WHEN p.team_id = g.awayteam_id THEN p.confidence ELSE null END) as avgAwayConfidence
FROM games g
INNER JOIN picks p on g.id = p.game_id
WHERE p.team_id IS NOT NULL
GROUP BY g.id
ORDER BY weightedstddev DESC
EOF;

    const LEAGUE_IMPORTANCE_SQL=<<<EOF
SELECT
    g.id
  , g.name
  , g.hometeam_id
  , g.awayteam_id
  , g.gamedate
  , stddev_pop(CASE WHEN p.team_id = g.hometeam_id THEN p.confidence WHEN p.team_id = g.awayteam_id THEN p.confidence * -1 END) as weightedstddev
  , sum(CASE WHEN p.team_id = g.hometeam_id THEN 1 ELSE 0 END) as homeTeamVotes
  , sum(CASE WHEN p.team_id = g.awayteam_id THEN 1 ELSE 0 END) as awayTeamVotes
  , count(p.team_id) as totalVotes
  , sum(CASE WHEN p.team_id IS NOT NULL THEN p.confidence ELSE 0 END) / sum(CASE WHEN p.team_id IS NOT NULL THEN 1 ELSE 0 END)::float as avgConfidence
  , avg(CASE WHEN p.team_id = g.hometeam_id THEN p.confidence ELSE null END) as avgHomeConfidence
  , avg(CASE WHEN p.team_id = g.awayteam_id THEN p.confidence ELSE null END) as avgAwayConfidence
FROM games g
INNER JOIN picks p on g.id = p.game_id
INNER JOIN picksets ps on p.pickset_id = p.pickset_id
INNER JOIN pickset_leagues pl on ps.id = pl.pickset_id
WHERE p.team_id IS NOT NULL
AND pl.league_id = ?
GROUP BY g.id
ORDER BY weightedstddev DESC
LIMIT ?
EOF;

    const USER_IMPORTANCE_SQL = <<<EOF
SELECT
    g.id
  , g.name
  , g.gamedate
  , p.team_id
  , p.confidence
  , CASE WHEN g.hometeamscore IS NOT NULL AND g.awayteamscore IS NOT NULL
      THEN CASE WHEN g.hometeamscore > g.awayteamscore THEN g.hometeam_id ELSE g.awayteam_id END
      ELSE null
    END as winner
FROM picks p
INNER JOIN games g ON p.game_id = g.id
INNER JOIN
(
	SELECT
	    g.id as game_id
	  , avg(CASE WHEN p.team_id = g.hometeam_id THEN p.confidence WHEN p.team_id = g.awayteam_id THEN p.confidence * -1 END) as weightedmean
	  , stddev_pop(CASE WHEN p.team_id = g.hometeam_id THEN p.confidence WHEN p.team_id = g.awayteam_id THEN p.confidence * -1 END) as weightedstddev
	FROM games g
	INNER JOIN picks p ON g.id = p.game_id
	INNER JOIN pickset_leagues pl ON p.pickset_id = pl.pickset_id
	WHERE p.team_id IS NOT NULL
	AND pl.league_id = ?
	GROUP BY g.id
) AS lp ON p.game_id = lp.game_id
WHERE p.pickset_id = ?
ORDER BY CASE WHEN lp.weightedstddev = 0 THEN 0 ELSE (CASE WHEN p.team_id = g.hometeam_id THEN p.confidence WHEN p.team_id = g.awayteam_id THEN p.confidence * -1 END - lp.weightedmean)/lp.weightedstddev END DESC
EOF;

    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.gameDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function gamesByImportance()
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::SITE_IMPORTANCE_SQL);
    }

    public function gamesByImportanceForLeague(League $league, $limit = 10)
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::LEAGUE_IMPORTANCE_SQL, array(
            $league->getId(),
            $limit,
        ));
    }

    public function userGamesByImportance(League $league, PickSet $pickSet)
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::USER_IMPORTANCE_SQL, array(
            $league->getId(),
            $pickSet->getId(),
        ));
    }
}
