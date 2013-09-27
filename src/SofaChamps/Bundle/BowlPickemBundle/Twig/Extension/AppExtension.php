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
        );
    }

    public function picksLocked()
    {
        return $this->container->get('sofachamps.bp.picks_locked_manager')->arePickLocked();
    }

    public function getName()
    {
        return 'sofachamps.bp.app';
    }
}
