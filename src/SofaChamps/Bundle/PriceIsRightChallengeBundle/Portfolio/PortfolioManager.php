<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Portfolio;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\NCAABundle\Entity\Team;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\PortfolioTeam;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * PortfolioManager
 *
 * @DI\Service("sofachamps.pirc.portfolio_manager")
 */
class PortfolioManager
{
    private $om;
    private $dispatcher;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "dispatcher" = @DI\Inject("event_dispatcher"),
     * })
     */
    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher)
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
    }

    public function createPortfolio(Game $game, User $user)
    {
        $portfolio = new Portfolio($game, $user);

        $this->om->persist($portfolio);

        return $portfolio;
    }

    public function setPortfolioTeams(Portfolio $portfolio, $teams)
    {
        $portfolioTeams = new ArrayCollection();
        foreach ($teams as $team) {
            $portfolioTeams->add($this->createPortfolioTeam($portfolio, $team));
        }

        $portfolio->setTeams($portfolioTeams);
    }

    public function createPortfolioTeam(Portfolio $portfolio, Team $team)
    {
        $portfolioTeam = new PortfolioTeam($portfolio, $team);

        $bracket = $portfolio->getBracket();
        $bracketTeam = $bracket->getBracketTeamForTeam($team);
        $config = $portfolio->getGame()->getConfig();
        $cost = $config->getCostForSeed($bracketTeam->getRegionSeed());

        $portfolioTeam->setCost($cost);

        $this->om->persist($portfolioTeam);

        return $portfolioTeam;
    }
}
