<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use CollegeCrazies\Bundle\MainBundle\Entity\Prediction;
use CollegeCrazies\Bundle\MainBundle\Entity\PredictionSet;
use Doctrine\Common\Persistence\ObjectManager;

class PredictionGenerator
{
    const BATCH_SIZE = 5;
    const CLEAR_SIZE = 10;

    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function createPredictions($numPredictions, $truncate = true)
    {
        // Clear out the old predictions
        if ($truncate) {
            $this->truncatePredictionTables();
        }

        for ($x = 0; $x < $numPredictions; $x++) {
            if ($x % self::CLEAR_SIZE === 0) {
                $this->om->clear();

                $games = $this->om->getRepository('CollegeCraziesMainBundle:Game')->findAll();
                $completedGames = array_filter($games, function ($game) {
                    return $game->isComplete();
                });

                $incompleteGames = array_filter($games, function ($game) {
                    return !$game->isComplete();
                });
            }

            $set = new PredictionSet();
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
                $favoriteBase = floor(($overunder / 2) + $spread + 1);
                $underdogBase = floor($overunder / 2);

                $homeTeamWinPercentage = $this->getHomeTeamWinPercentage($spread);

                // We'll be lame for now with coming up with cool scores
                if (rand(0, 1) < $homeTeamWinPercentage) {
                    $homeTeamScore = $favoriteBase;
                    $awayTeamScore = $underdogBase;
                } else {
                    $homeTeamScore = $underdogBase;
                    $awayTeamScore = $favoriteBase;
                }

                $predictions[] = $this->savePrediction($set, $game, $homeTeamScore, $awayTeamScore);
            }

            $set->setPredictions($predictions);

            if ($x % self::BATCH_SIZE === 0) {
                $this->om->flush();
            }

            echo sprintf("%d\t%s %s\n", $x, memory_get_usage(), memory_get_peak_usage());
        }
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

    protected function truncatePredictionTables()
    {
        $conn = $this->om->getConnection();
        $conn->beginTransaction();
        $conn->executeUpdate('TRUNCATE prediction_sets CASCADE');
        $conn->commit();
    }
}
