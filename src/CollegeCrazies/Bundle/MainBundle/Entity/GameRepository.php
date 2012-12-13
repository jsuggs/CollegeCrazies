<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    const IMPORTANCE_SQL=<<<EOF
SELECT
    g.id
  , g.name
  , g.hometeam_id
  , g.awayteam_id
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

    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.gameDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function gamesByImportance()
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::IMPORTANCE_SQL);
    }
}
