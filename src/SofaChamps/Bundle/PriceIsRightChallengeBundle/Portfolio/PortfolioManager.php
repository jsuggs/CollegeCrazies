<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Portfolio;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
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

    public function createPortfolio(User $user)
    {
        $portfolio = new Portfolio($user);

        $this->om->persist($portfolio);

        return $portfolio;
    }
}
