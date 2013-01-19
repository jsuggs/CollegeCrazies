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
    }
}
