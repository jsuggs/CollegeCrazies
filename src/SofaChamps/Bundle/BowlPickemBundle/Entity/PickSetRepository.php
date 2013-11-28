<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PickSetRepository extends EntityRepository
{
    const DISTRIBUTION_SQL =<<<EOF
SELECT finish, COUNT(finish) AS distribution, (CAST(COUNT(finish) AS numeric) / (SELECT COUNT(*) FROM prediction_sets)) * 100 as percentage
FROM user_prediction_set_score
WHERE league_id = :leagueId
AND user_id = :userId
AND season = :season
GROUP BY finish
EOF;

    const BEST_PROJECTED_FINISH_SQL = <<<EOF
SELECT finish, predictionset_id
FROM user_prediction_set_score
WHERE league_id = ?
AND user_id = ?
AND finish = (
  SELECT MIN(finish)
  FROM user_prediction_set_score
  WHERE league_id = ?
  AND user_id = ?
)
ORDER BY predictionset_id
LIMIT 1
EOF;

    const PROJECTED_FINISH_STATS_SQL = <<<EOF
SELECT
    MIN(finish) AS bestfinish
  , MAX(finish) AS worstfinish
  , AVG(finish) AS avgfinish
  , MAX(score) AS bestscore
  , MIN(score) AS worstscore
  , AVG(score) AS avgscore
FROM user_prediction_set_score
WHERE league_id = ?
AND user_id = ?
EOF;

    const PICKSET_DQL =<<<EOF
SELECT ps, u, p, g, ht, at
FROM SofaChampsBowlPickemBundle:Pickset ps
JOIN ps.user u
JOIN ps.picks p
JOIN p.game g
JOIN g.homeTeam ht
JOIN g.awayTeam at
WHERE ps.id = :id
ORDER BY %s
EOF;

    public function getPickDistribution(PickSet $pickSet, League $league, $season)
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::DISTRIBUTION_SQL, array(
            'season' => $season,
            'leagueId' => $league->getId(),
            'userId' => $pickSet->getUser()->getId(),
        ));
    }

    public function getProjectedBestFinish(PickSet $pickSet, League $league)
    {
        $result = $this->getEntityManager()->getConnection()->fetchAll(self::BEST_PROJECTED_FINISH_SQL, array(
            $league->getId(),
            $pickSet->getUser()->getId(),
            $league->getId(),
            $pickSet->getUser()->getId(),
        ));

        return count($result) ? $result[0] : null;
    }

    public function getProjectedFinishStats(PickSet $pickSet, League $league)
    {
        return $this->getEntityManager()->getConnection()->fetchAssoc(self::PROJECTED_FINISH_STATS_SQL, array(
            $league->getId(),
            $pickSet->getUser()->getId(),
        ));
    }

    public function findAllOrderedByPoints($limit = 10)
    {
        return $this->getEntityManager()->createQuery('SELECT p, u, pk, pg from SofaChampsBowlPickemBundle:PickSet p
            JOIN p.user u
            JOIN p.picks pk
            JOIN pk.game pg')
            ->getResult();
    }

    public function findPickSet($id, $sort)
    {
        // Hack the order via sprintf
        return $this->getEntityManager()->createQuery(sprintf(self::PICKSET_DQL, $sort))
            ->setParameters(array(
                'id' => $id,
            ))
            ->getSingleResult();
    }
}
