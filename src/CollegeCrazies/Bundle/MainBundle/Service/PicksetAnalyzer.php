<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;

class PicksetAnalyzer
{
    const DELETE_USER_SCORE_SQL =<<<EOF
DELETE FROM user_prediction_set_score
WHERE pickSet_id = ?
EOF;

    const INSERT_USER_SCORE_SQL =<<<EOF
INSERT INTO user_prediction_set_score (pickSet_id, user_id, predictionSet_id, score)
SELECT ps.id, ps.user_id, pd.predictionset_id, sum(p.confidence)
FROM picksets ps
JOIN picks p ON ps.id = p.pickset_id
JOIN predictions pd ON p.game_id = pd.game_id
WHERE ps.id = ?
AND p.team_id = pd.winner_id
GROUP BY ps.id, ps.user_id, pd.predictionset_id
EOF;

    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function analyizePickSet(PickSet $pickSet)
    {
        $this->conn->executeUpdate(self::DELETE_USER_SCORE_SQL, array(
            $pickSet->getId(),
        ));

        $this->conn->executeUpdate(self::INSERT_USER_SCORE_SQL, array(
            $pickSet->getId(),
        ));
    }

    public function analyizeLeaguePickSets(League $league)
    {
        foreach ($league->getPickSets() as $pickSet) {
            $this->analyizePickSet($pickSet);
        }
    }
}
