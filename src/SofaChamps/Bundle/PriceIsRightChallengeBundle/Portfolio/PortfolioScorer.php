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
        $game = $portfolio->getGame();
        $config = $game->getConfig();

        $portfolioTeamIds = $portfolio->getTeams()->map(function($portfolioTeam) {
            return $portfolioTeam->getTeam()->getId();
        })->toArray();

        $score = 0;
        foreach ($game->getBracket()->getGames() as $game) {
            $winnerId = $game->getWinner()->getTeam()->getId();
            if (in_array($winnerId, $portfolioTeamIds)) {
                $points = $config->getWinForRound($game->getRound());
                $score += $points;
            }
        }

        $portfolio->setScore($score);
    }
}
