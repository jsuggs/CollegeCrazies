<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Twig\Extension;

use SofaChamps\Bundle\SuperBowlChallengeBundle\Pick\PickManager;

class SuperBowlChallengeExtension extends \Twig_Extension
{
    private $manager;
    private $year;

    public function __construct(PickManager $manager, $year)
    {
        $this->manager = $manager;
        $this->year = $year;
    }

    public function getGlobals()
    {
        return array(
            'sbc_picks_open' => $this->manager->picksOpen($this->year),
            'sbc_game_available' => $this->manager->gameAvailable($this->year),
        );
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
