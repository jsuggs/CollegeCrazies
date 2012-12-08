<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PickSetRepository extends EntityRepository
{
    const DISTRIBUTION_SQL =<<<EOF
SELECT finish, COUNT(finish) AS distribution
FROM user_prediction_set_score
WHERE league_id = ?
AND user_id = ?
GROUP BY finish
EOF;

    public function getPickDistribution(PickSet $pickSet, League $league)
    {
        return $this->getEntityManager()->getConnection()->fetchAll(self::DISTRIBUTION_SQL, array(
            $league->getId(),
            $pickSet->getUser()->getId(),
        ));
    }
}
