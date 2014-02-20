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
        $bracketTeams = $bracket->getTeams();

        $teamChoices = array();
        foreach ($bracketTeams as $bracketTeam) {
            $teamChoices[$bracketTeam->getTeam()->getId()] = $bracketTeam->getTeamName();
        }

        return $this->createFormBuilder()
            ->add('name', 'text', array(
                'label' => 'Portfolio Name',
            ))
            ->add('teams', 'choice', array(
                'choices' => $teamChoices,
                'multiple' => true,
                'expanded' => true,
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
