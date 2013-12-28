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
     * })
     */
    public function __construct(PickManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        $manager = $this->manager;

        return array(
            'sbc_picks_open' => new \Twig_Function_Method($this, 'arePicksOpen'),
            'sbc_game_available' => new \Twig_Function_Method($this, 'isGameAvailable'),
        );
    }

    public function arePicksOpen($year)
    {
        return $this->manager->picksOpen($year);
    }

    public function isGameAvailable($year)
    {
        return $this->manager->gameAvailable($year);
    }

    public function getName()
    {
        return 'sofachamps.superbowlchallenge';
    }
}
