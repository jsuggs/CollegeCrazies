<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Twig\Extension;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Pick\PickManager;

/**
 * SuperBowlChallengeExtension
 *
 * @DI\Service("sofachamps.sbc.twig.app")
 * @DI\Tag("twig.extension")
 */
class SuperBowlChallengeExtension extends \Twig_Extension
{
    private $manager;
    private $year;

    /**
     * @DI\InjectParams({
     *      "manager" = @DI\Inject("sofachamps.superbowlchallenge.pickmanager"),
     *      "year" = @DI\Inject("config.curyear")
     * })
     */
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
}
