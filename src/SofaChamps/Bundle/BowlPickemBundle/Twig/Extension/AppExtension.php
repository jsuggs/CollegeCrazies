<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Twig\Extension;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AppExtension
 *
 * @DI\Service("sofachamps.bp.twig.app")
 * @DI\Tag("twig.extension")
 */
class AppExtension extends \Twig_Extension
{
    private $container;

    /**
     * @DI\InjectParams({
     *      "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array(
            'current_season' => $this->getCurrentSeason(),
        );
    }

    public function getFunctions()
    {
        return array(
            'picks_lock_time' => new \Twig_Function_Method($this, 'getLockTime'),
            'picks_locked' => new \Twig_Function_Method($this, 'picksLocked'),
        );
    }

    public function picksLocked(Season $season)
    {
        return $this->container->get('sofachamps.bp.picks_locked_manager')->arePickLocked($season);
    }

    public function getCurrentSeason()
    {
        return $this->container->get('sofachamps.bp.season_manager')->getCurrentSeason();;
    }

    public function getLockTime(Season $season)
    {
        return $this->container->get('sofachamps.bp.picks_locked_manager')->getLockTime($season);
    }

    public function getName()
    {
        return 'sofachamps.bp.app';
    }
}
