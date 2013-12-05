<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Twig\Extension;

use JMS\DiExtraBundle\Annotation as DI;
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
            'picks_locked' => $this->picksLocked(),
            'current_season' => $this->getCurrentSeason(),
        );
    }

    public function getFunctions()
    {
        return array(
            'picks_lock_time' => new \Twig_Function_Method($this, 'getLockTime'),
        );
    }

    public function picksLocked()
    {
        return $this->container->get('sofachamps.bp.picks_locked_manager')->arePickLocked();
    }

    public function getCurrentSeason()
    {
        return $this->container->get('sofachamps.bp.season_manager')->getCurrentSeason();;
    }

    public function getLockTime($season)
    {
        return $this->container->get('sofachamps.bp.picks_locked_manager')->getLockTime($season);
    }

    public function getName()
    {
        return 'sofachamps.bp.app';
    }
}
