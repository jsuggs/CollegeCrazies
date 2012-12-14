<?php

namespace CollegeCrazies\Bundle\MainBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends \Twig_Extension
{
    private $container;

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
        return $this->container->get('picks_locked_manager')->arePickLocked();
    }

    public function getName()
    {
        return 'sofachamps.app';
    }
}
