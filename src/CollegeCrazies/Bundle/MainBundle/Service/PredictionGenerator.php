<?php

namespace CollegeCrazies\Bundle\MainBundle\Service;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use CollegeCrazies\Bundle\MainBundle\Entity\Prediction;
use CollegeCrazies\Bundle\MainBundle\Entity\PredictionSet;
use Doctrine\Common\Persistence\ObjectManager;

class PredictionGenerator
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function createPredictions($numPredictions)
    {
        // Clear out the old predictions
        $this->truncatePredictionTables();

        $games = $this->om->getRepository('CollegeCraziesMainBundle:Game')->findAll();
        $completedGames = array_filter($games, function ($game) {
            return $game->isComplete();
        });

        $incompleteGames = array_filter($games, function ($game) {
            return !$game->isComplete();
        });

        $gameSpreads = array_map(function ($game) { return abs($game->getSpread()); }, $games);
        $spreadAvg = array_sum($gameSpreads) / count($gameSpreads);
        $maxSpread = max($gameSpreads);

        for ($x = 0; $x < $numPredictions; $x++) {
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
                $spreadDiff = $maxSpread - $spread + 2;

                // Get the base score
                $homeTeamBase = ($overunder / 2) + $spread;
                $awayTeamBase = $overunder / 2;

                $variance = log($spreadDiff, $overunder) * $spreadDiff;

                // This means that they are a huge underdog
                if (($variance *2) < $spreadDiff) {
                    // 30% of the time, bump up the variance, which still doesn't ensure an upset just makes it a possibility
                    if (rand(0, 1) < .3) {
                        $variance = $spread;
                    }
                }
                $homeTeamScore = floor($homeTeamBase + rand(0, $variance));
                $awayTeamScore = floor($awayTeamBase + rand(0, $variance));

                //echo sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\n", $game->getId(), $spreadDiff, $variance, $homeTeamBase, $awayTeamBase, $homeTeamScore, $awayTeamScore);

                $predictions[] = $this->savePrediction($set, $game, $homeTeamScore, $awayTeamScore);
            }

            $set->setPredictions($predictions);

            $this->om->flush();
        }
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
