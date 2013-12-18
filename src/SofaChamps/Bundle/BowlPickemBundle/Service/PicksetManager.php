<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Pick;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Event\PickSetEvent;
use SofaChamps\Bundle\BowlPickemBundle\Event\PickSetEvents;
use SofaChamps\Bundle\BowlPickemBundle\League\LeagueManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * PicksetManager
 *
 * @DI\Service("sofachamps.bp.pickset_manager")
 */
class PicksetManager
{
    private $om;
    private $dispatcher;
    private $leagueManager;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "dispatcher" = @DI\Inject("event_dispatcher"),
     *      "leagueManager" = @DI\Inject("sofachamps.bp.league_manager"),
     * })
     */
    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher, LeagueManager $leagueManager)
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
        $this->leagueManager = $leagueManager;
    }

    public function createUserPickset(User $user, $season, League $league = null)
    {
        $pickSet = new PickSet();
        $pickSet->setUser($user);
        $pickSetName = $this->createPicksetName($user);
        $pickSet->setName($pickSetName);
        $pickSet->setSeason($season);

        if ($league) {
            $pickSet->addLeague($league);
            $this->leagueManager->addUserToLeague($league, $user);
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

        $this->dispatchPickSetCreated($pickSet);

        $this->om->persist($pickSet);

        return $pickSet;
    }

    public function addPickSetToLeague(League $league, PickSet $pickSet)
    {
        $pickSet->addLeague($league);

        $user = $pickSet->getUser();
        $this->leagueManager->addUserToLeague($league, $user);
    }

    protected function dispatchPickSetCreated(PickSet $pickSet)
    {
        $this->dispatcher->dispatch(PickSetEvents::PICKSET_CREATED, new PickSetEvent($pickSet));
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
