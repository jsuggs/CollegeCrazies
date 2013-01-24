<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SuperBowlChallengeExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array(
            'sbc_picks_open' => $this->picksOpen(),
            'sbc_game_available' => $this->gameAvailable(),
        );
    }

    public function picksOpen()
    {
        $config = $this->getConfig();

        if (!$config) {
            return false;
        }

        $now = new \DateTime();

        return $now > $config->getStartTime() && $now < $config->getCloseTime();
    }

    public function gameAvailable()
    {
        $config = $this->getConfig();

        if (!$config) {
            return false;
        }

        $now = new \DateTime();

        return $now < $config->getCloseTime()->modify('+30 days');
    }

    public function getName()
    {
        return 'sofachamps.superbowlchallenge';
    }

    protected function getConfig()
    {
        return $this->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Config')
            ->find(date('Y'));
    }
}
