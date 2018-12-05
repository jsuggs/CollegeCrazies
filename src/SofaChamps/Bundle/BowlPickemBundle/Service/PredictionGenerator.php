<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Prediction;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PredictionSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\NCAABundle\Entity\Team;

/**
 * PredictionGenerator
 *
 * @DI\Service("sofachamps.bp.prediction_generator")
 */
class PredictionGenerator
{
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function createPredictions($numPredictions, Season $season)
    {
        $games = $this->om->getRepository('SofaChampsBowlPickemBundle:Game')->findAll();
        $completedGames = array_filter($games, function ($game) {
            return $game->isComplete();
        });

        $incompleteGames = array_filter($games, function ($game) {
            return !$game->isComplete();
        });

        for ($x = 0; $x < $numPredictions; $x++) {
            $set = new PredictionSet();
            $set->setSeason($season);
            $this->om->persist($set);

            $predictions = array();

            // Insert the predictions for the completed games
            foreach ($completedGames as $game) {
                $predictions[] = $this->savePrediction($set, $game, $game->getHomeTeamScore(), $game->getAwayTeamScore());
            }

            foreach ($incompleteGames as $game) {
                $overunder = $game->getOverunder();
                $spread = $game->getSpread();

                // Get the base score
                $favoriteBase = floor(($overunder / 2) + $spread + 4 + rand(2, 12));
                $underdogBase = floor($overunder / 2 + rand(3, 11));

                if ($favoriteBase === $underdogBase) {
                    $favoriteBase += 1;
                }

                if ($favoriteBase > $underdogBase) {
                    $winnerScore = $favoriteBase;
                    $loserScore = $underdogBase;
                } else {
                    $winnerScore = $underdogBase;
                    $loserScore = $favoriteBase;
                }

                $homeTeamWinPercentage = $this->getHomeTeamWinPercentage($spread) * 100;

                // We'll be lame for now with coming up with cool scores
                if (rand(0, 100) < $homeTeamWinPercentage) {
                    $homeTeamScore = $winnerScore;
                    $awayTeamScore = $loserScore;
                } else {
                    $homeTeamScore = $loserScore;
                    $awayTeamScore = $winnerScore;
                }

                $predictions[] = $this->savePrediction($set, $game, $homeTeamScore, $awayTeamScore);
            }

            // Store a championship winner
            $set->setChampionshipWinner($this->getSeasonChampionshipWinner($season));

            $set->setPredictions($predictions);
            $this->om->flush();

            echo sprintf("%d\t%s %s\n", $x, memory_get_usage(), memory_get_peak_usage());
        }
    }

    public function truncatePredictionTables(Season $season)
    {
        $conn = $this->om->getConnection();
        $conn->beginTransaction();
        $conn->executeUpdate('DELETE FROM user_prediction_set_score WHERE season = :season', array(
            'season' => $season->getSeason(),
        ));
        $conn->executeUpdate('DELETE FROM predictions p WHERE p.predictionset_id IN (SELECT ps.id FROM prediction_sets ps WHERE ps.season = :season)', array(
            'season' => $season->getSeason(),
        ));
        $conn->executeUpdate('DELETE FROM prediction_sets WHERE season = :season', array(
            'season' => $season->getSeason(),
        ));
        $conn->commit();
    }

    public function getHomeTeamWinPercentage($spread) {
        // If no spread, its 50/50
        if ($spread === 0) {
            return .5;
        }

        // If spread is greater than 25, its 5/95
        if (abs($spread) >= 25) {
            $percent = .95;
        } else {
            $percent = (-0.0006 * pow(abs($spread), 2)) + (0.0336 * abs($spread)) + 0.4766;
        }

        return $spread > 0
            ? 1 - $percent
            : $percent;
    }

    protected function savePrediction(PredictionSet $set, Game $game, $homeTeamScore, $awayTeamScore)
    {
        $winner = ($homeTeamScore > $awayTeamScore) ? $game->getHomeTeam() : $game->getAwayTeam();

        $prediction = new Prediction($set, $game, $winner);
        $prediction->setHomeTeamScore($homeTeamScore);
        $prediction->setAwayTeamScore($awayTeamScore);
        $this->om->persist($prediction);

        return $prediction;
    }

    protected function getSeasonChampionshipWinner(Season $season)
    {
        $games = $this->getGameRepository()->getPlayoffGames($season);
        $teams = array();
        foreach ($games as $game) {
            $teams[$game->getHomeTeam()->getId()] = $game->getHomeTeam();
            $teams[$game->getAwayTeam()->getId()] = $game->getAwayTeam();
        }
        // TODO - Build this out into season config rather than hard coding
        $weightedValues = [
            'ALA' => 52,
            'CLEM' => 26,
            'ND' => 8,
            'OKLA' => 14,
        ];
        $rand = $this->getRandomWeightedElement($weightedValues);

        return $teams[$rand];
    }

    protected function getGameRepository()
    {
        return $this->om->getRepository('SofaChampsBowlPickemBundle:Game');
    }

    private function getRandomWeightedElement(array $weightedValues) {
        $rand = mt_rand(1, (int) array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }
}
