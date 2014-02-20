<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use Doctrine\ORM\NoResultException;
use SofaChamps\Bundle\CoreBundle\Controller\CoreController;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Config;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\PortfolioTeam;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Form\ConfigFormType;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Form\GameFormType;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Form\GameInviteFormType;

class BaseController extends CoreController
{
    protected function getGameManager()
    {
        return $this->container->get('sofachamps.pirc.game_manager');
    }

    protected function getPortfolioManager()
    {
        return $this->container->get('sofachamps.pirc.portfolio_manager');
    }

    protected function getInviteManager()
    {
        return $this->get('sofachamps.pirc.invite_manager');
    }

    protected function getUserPortfolio(User $user, $season)
    {
        try {
            return $this->getRepository('SofaChampsPriceIsRightChallengeBundle:Portfolio')->getUserPortfolio($user, $season);
        } catch (NoResultException $e) {
            return $this->getPortfolioManager()->createPortfolio($user, $season);
        }
    }

    protected function getInviteForm(Game $game)
    {
        return $this->createForm(new GameInviteFormType());
    }

    protected function getPortfolioForm(Portfolio $portfolio)
    {
        $bracket = $portfolio->getGame()->getBracket();

        // Filter out the existing bracket teams (we need the existing/persisted PortfolioTeam)
        $portfolioTeams = $bracket->getTeams()
            ->filter(function($bracketTeam) use ($portfolio) {
                return !$portfolio->hasTeam($bracketTeam->getTeam());
            })
            ->map(function($bracketTeam) use ($portfolio) {
                return new PortfolioTeam($portfolio, $bracketTeam->getTeam());
            });

        foreach ($portfolio->getTeams() as $team) {
            $portfolioTeams->add($team);
        }

        $portfolioTeams = $portfolioTeams->toArray();

        // Sort by seed, then overall seed so the order will be static
        usort($portfolioTeams, function($a, $b) use ($bracket) {
            $aBracketTeam = $bracket->getBracketTeamForTeam($a->getTeam());
            $bBracketTeam = $bracket->getBracketTeamForTeam($b->getTeam());

            if ($aBracketTeam->getRegionSeed() == $bBracketTeam->getRegionSeed()) {
                return $aBracketTeam->getOverallSeed() > $bBracketTeam->getOverallSeed()
                    ? 1
                    : -1;
            }

            return $aBracketTeam->getRegionSeed() > $bBracketTeam->getRegionSeed()
                ? 1
                : -1;
        });

        return $this->createFormBuilder($portfolio)
            ->add('name', 'text', array(
                'label' => 'Portfolio Name',
            ))
            ->add('teams', 'entity', array(
                'class' => 'SofaChampsPriceIsRightChallengeBundle:PortfolioTeam',
                'choices' => $portfolioTeams,
                'multiple' => true,
                'expanded' => true,
                'property' => 'teamName'
            ))
            ->getForm();
    }

    protected function getConfigForm(Config $config)
    {
        return $this->createForm(new ConfigFormType(), $config);
    }

    protected function getGameForm(Game $game)
    {
        return $this->createForm(new GameFormType(), $game);
    }
}
