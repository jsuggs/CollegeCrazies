<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Portfolio;

use Doctrine\ORM\Mapping as ORM;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;

/**
 * PortfolioScorer
 *
 * @DI\Service("sofachamps.pirc.portfolio_scorer")
 */
class PortfolioScorer
{
    public function scorePortfolio(Portfolio $portfolio)
    {
        $config = $portfolio->getConfig();

        $score = 0;
        foreach ($portfolio->getBracket()->getGames() as $game) {
            if (in_array($game->getWinner(), $portfolio->getTeams())) {
                $points = $config->getPointsForRound($game->getRound());
                $score += $points;
            }
        }

        $portfolio->setScore($score);
    }
}
