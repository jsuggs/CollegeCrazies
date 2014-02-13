<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Twig\Extension;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\NCAABundle\Entity\Team;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TeamExtension
 *
 * @DI\Service("sofachamps.pirc.twig.team")
 * @DI\Tag("twig.extension")
 */
class TeamExtension extends \Twig_Extension
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

    public function getFunctions()
    {
        return array(
            'bracket_team' => new \Twig_Function_Method($this, 'getBracketTeam'),
        );
    }

    public function getBracketTeam(Bracket $bracket, Team $team)
    {
        return $bracket->getTeams()->filter(function($bracketTeam) use ($team) {
            return $bracketTeam->getTeam() == $team;
        })->first();
    }

    public function getName()
    {
        return 'sofachamps.pirc.team';
    }
}
