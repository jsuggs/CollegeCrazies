<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\DBAL\Connection;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;

/**
 * PicksetAnalyzer
 *
 * @DI\Service("sofachamps.bp.pickset_analyzer")
 */
class PicksetAnalyzer
{
    const DELETE_USER_SCORE_SQL =<<<EOF
DELETE FROM user_prediction_set_score
WHERE season = :season
EOF;

    const INSERT_USER_SCORE_SQL =<<<EOF
INSERT INTO user_prediction_set_score (league_id, pickSet_id, user_id, predictionSet_id, score, finish)
SELECT
    pl.league_id
  , ps.id
  , ps.user_id
  , pd.predictionset_id
  , sum(p.confidence)
  , rank() OVER (PARTITION BY pl.league_id, pd.predictionset_id ORDER BY SUM(p.confidence) DESC)
FROM picksets ps
JOIN pickset_leagues pl on pl.pickset_id = ps.id
JOIN picks p ON ps.id = p.pickset_id
JOIN predictions pd ON p.game_id = pd.game_id
AND p.team_id = pd.winner_id
WHERE pl.league_id = :leagueId
GROUP BY pl.league_id, pd.predictionset_id, ps.user_id, ps.id
ORDER BY predictionset_id, rank
EOF;

    protected $conn;

    /**
     * @DI\InjectParams({
     *      "conn" = @DI\Inject("doctrine.dbal.default_connection"),
     * })
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function deleteAnalysis($season)
    {
        $this->conn->executeUpdate(self::DELETE_USER_SCORE_SQL, array(
            'season' => $season,
        ));
    }

    public function analyizeLeaguePickSets(League $league, $season)
    {
        $this->conn->executeUpdate(self::INSERT_USER_SCORE_SQL, array(
            'leagueId' => $league->getId(),
            //'season' => $season,
        ));
    }
}
