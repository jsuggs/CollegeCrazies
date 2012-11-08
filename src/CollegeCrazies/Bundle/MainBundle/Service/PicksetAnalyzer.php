<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;

class PicksetAnalyzer
{
    const DELETE_USER_SCORE_SQL =<<<EOF
TRUNCATE user_prediction_set_score
EOF;

    const INSERT_USER_SCORE_SQL =<<<EOF
INSERT INTO user_prediction_set_score (league_id, pickSet_id, user_id, predictionSet_id, score, finish)
SELECT
    ps.league_id
  , ps.id
  , ps.user_id
  , pd.predictionset_id
  , sum(p.confidence)
  , rank() OVER (PARTITION BY ps.league_id, pd.predictionset_id ORDER BY SUM(p.confidence) DESC)
FROM picksets ps
JOIN picks p ON ps.id = p.pickset_id
JOIN predictions pd ON p.game_id = pd.game_id
AND p.team_id = pd.winner_id
GROUP BY ps.league_id, pd.predictionset_id, ps.user_id, ps.id
ORDER BY predictionset_id, rank
EOF;

    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function analyizePickSets()
    {
        $this->conn->executeUpdate(self::DELETE_USER_SCORE_SQL);
        $this->conn->executeUpdate(self::INSERT_USER_SCORE_SQL);
    }
}
