<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PickSetRepository extends EntityRepository
{
    const DISTRIBUTION_SQL =<<<EOF
SELECT finish, COUNT(finish) AS distribution, (CAST(COUNT(finish) AS numeric) / (SELECT COUNT(*) FROM prediction_sets)) * 100 as percentage
FROM user_prediction_set_score
WHERE league_id = ?
AND user_id = ?
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

    public function getPickDistribution(PickSet $pickSet, League $league)
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::DISTRIBUTION_SQL, array(
            $league->getId(),
            $pickSet->getUser()->getId(),
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
}
