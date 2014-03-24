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
        $games = $game->getBracket()->getGamesPessimistic();
        foreach ($game->getBracket()->getGamesPessimistic() as $game) {
            $winner = $game->getWinner();
            if (!$winner) {
                continue;
            }
            $winningTeam = $winner->getTeam();
            $winnerId = $winningTeam->getId();
            $round = $game->getRound();
            if (in_array($winnerId, $portfolioTeamIds)) {
                $points = $config->getWinForRound($round);
                $bracketTeam = $portfolio->getTeam($winningTeam);
                $bracketTeam->setRoundScore($round, $points);
                $score += $points;
            }
        }

        $portfolio->setScore($score);
    }
}
