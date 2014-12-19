<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\DBAL\Connection;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;

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
INSERT INTO user_prediction_set_score (league_id, season, pickSet_id, user_id, predictionSet_id, score, finish)
SELECT
    pl.league_id
  , l.season
  , ps.id
  , ps.user_id
  , pds.id
  , sum(p.confidence) + CASE WHEN pds.championshipwinner_id = ps.champ_team_id THEN s.championshippoints ELSE 0 END
  , rank() OVER (PARTITION BY pl.league_id, pds.id ORDER BY sum(p.confidence) + CASE WHEN pds.championshipwinner_id = ps.champ_team_id THEN s.championshippoints ELSE 0 END DESC)
FROM picksets ps
JOIN pickset_leagues pl on pl.pickset_id = ps.id
JOIN leagues l on l.id = pl.league_id
JOIN picks p ON ps.id = p.pickset_id
JOIN predictions pd ON p.game_id = pd.game_id
JOIN prediction_sets pds ON pd.predictionset_id = pds.id
JOIN bp_seasons s on l.season = s.season
AND p.team_id = pd.winner_id
WHERE pl.league_id = :leagueId
AND l.season = :season
GROUP BY pl.league_id, l.season, pds.id, ps.user_id, ps.id, s.championshippoints
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

    public function deleteAnalysis(Season $season)
    {
        $this->conn->executeUpdate(self::DELETE_USER_SCORE_SQL, array(
            'season' => $season->getSeason(),
        ));
    }

    public function analyizeLeaguePickSets(League $league, Season $season)
    {
        $this->conn->executeUpdate(self::INSERT_USER_SCORE_SQL, array(
            'leagueId' => $league->getId(),
            'season' => $season->getSeason(),
        ));
    }
}
