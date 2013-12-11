<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Pick;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * PicksetManager
 *
 * @DI\Service("sofachamps.bp.pickset_manager")
 */
class PicksetManager
{
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function createUserPickset(User $user, $season, League $league = null)
    {
        $pickSet = new PickSet();
        $pickSet->setUser($user);
        $pickSetName = $this->createPicksetName($user);
        $pickSet->setName($pickSetName);
        $pickSet->setSeason($season);

        if ($league) {
            $pickSet->setLeague($league);
        }

        $games = $this->om->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate($season);
        $idx = count($games);
        foreach ($games as $game) {
            $pick = new Pick();
            $pick->setGame($game);
            $pick->setConfidence($idx);

            $pickSet->addPick($pick);
            $idx--;
        }

        $user->addPickSet($pickSet);

        return $pickSet;
    }

    public function addPickSetToLeague(League $league, PickSet $pickSet)
    {
        $pickSet->addLeague($league);
    }

    protected function createPicksetName(User $user)
    {
        $pickSetName = sprintf(
            '%s - %s',
            $user->getUsername(),
            count($user->getPicksets()) == 0
                ? 'Default Pickset'
                : sprintf('Pickset #%d', count($user->getPicksets()) + 1)
        );

        return substr($pickSetName, 0, 40);
    }
}
