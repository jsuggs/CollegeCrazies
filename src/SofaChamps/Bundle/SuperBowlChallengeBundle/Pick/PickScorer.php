<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Pick;

use SofaChamps\Bundle\CoreBundle\Util\Math\SigmaUtils;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Result;

class PickScorer
{
    public static function scorePick(Pick $pick, Result $result, Config $config)
    {
        // Get the sigma summation of the difference of the scores
        $nfcFinalScoreSigma = SigmaUtils::summation($pick->getNfcFinalScore() - $result->getNfcFinalScore());
        $afcFinalScoreSigma = SigmaUtils::summation($pick->getAfcFinalScore() - $result->getAfcFinalScore());
        $finalScorePoints = max(0, $config->getFinalScorePoints() - $nfcFinalScoreSigma - $afcFinalScoreSigma);

        $pick->setFinalScorePoints($finalScorePoints);

        $nfcHalftimeScoreSigma = SigmaUtils::summation($pick->getNfcHalftimeScore() - $result->getNfcHalftimeScore());
        $afcHalftimeScoreSigma = SigmaUtils::summation($pick->getAfcHalftimeScore() - $result->getAfcHalftimeScore());
        $halftimeScorePoints = max(0, $config->getHalftimeScorePoints() - $nfcHalftimeScoreSigma - $afcHalftimeScoreSigma);

        $pick->setHalftimeScorePoints($halftimeScorePoints);

        // Get the points for correctly picking the team to score first in each of the quarters
        $firstTeamToScorePoints = 0;
        if ($pick->getFirstTeamToScoreFirstQuarter() == $result->getFirstTeamToScoreFirstQuarter()) {
            $firstTeamToScorePoints += $result->getFirstTeamToScoreFirstQuarter() === 'none'
                ? $config->getNeitherTeamToScoreInAQuarterPoints()
                : $config->getFirstTeamToScoreInAQuarterPoints();
        }

        if ($pick->getFirstTeamToScoreSecondQuarter() == $result->getFirstTeamToScoreSecondQuarter()) {
            $firstTeamToScorePoints += $result->getFirstTeamToScoreSecondQuarter() === 'none'
                ? $config->getNeitherTeamToScoreInAQuarterPoints()
                : $config->getFirstTeamToScoreInAQuarterPoints();
        }

        if ($pick->getFirstTeamToScoreThirdQuarter() == $result->getFirstTeamToScoreThirdQuarter()) {
            $firstTeamToScorePoints += $result->getFirstTeamToScoreThirdQuarter() === 'none'
                ? $config->getNeitherTeamToScoreInAQuarterPoints()
                : $config->getFirstTeamToScoreInAQuarterPoints();
        }

        if ($pick->getFirstTeamToScoreFourthQuarter() == $result->getFirstTeamToScoreFourthQuarter()) {
            $firstTeamToScorePoints += $result->getFirstTeamToScoreFourthQuarter() === 'none'
                ? $config->getNeitherTeamToScoreInAQuarterPoints()
                : $config->getFirstTeamToScoreInAQuarterPoints();
        }

        $pick->setFirstTeamToScorePoints($firstTeamToScorePoints);

        // Get the points for the bonus questions
        $bonusQuestionPoints = 0;

        if ($pick->getBonusQuestion1() == $result->getBonusQuestion1()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        if ($pick->getBonusQuestion2() == $result->getBonusQuestion2()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        if ($pick->getBonusQuestion3() == $result->getBonusQuestion3()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        if ($pick->getBonusQuestion4() == $result->getBonusQuestion4()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        if ($pick->getBonusQuestion5() == $result->getBonusQuestion5()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        if ($pick->getBonusQuestion6() == $result->getBonusQuestion6()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        if ($pick->getBonusQuestion7() == $result->getBonusQuestion7()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        if ($pick->getBonusQuestion8() == $result->getBonusQuestion8()) {
            $bonusQuestionPoints += $config->getBonusQuestionPoints();
        }

        $pick->setBonusQuestionPoints($bonusQuestionPoints);

        // Set the total points
        $pick->setTotalPoints($finalScorePoints + $halftimeScorePoints + $firstTeamToScorePoints + $bonusQuestionPoints);
    }
}
