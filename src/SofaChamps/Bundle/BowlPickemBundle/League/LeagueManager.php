<?php

namespace SofaChamps\Bundle\BowlPickemBundle\League;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Event\LeagueEvent;
use SofaChamps\Bundle\BowlPickemBundle\Event\LeagueEvents;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * LeagueManager
 *
 * @DI\Service("sofachamps.bp.league_manager")
 */
class LeagueManager
{
    private $om;
    private $dispatcher;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "dispatcher" = @DI\Inject("event_dispatcher"),
     *      "session" = @DI\Inject("session"),
     * })
     */
    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher)
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
    }

    public function createLeague(Season $season)
    {
        $league = new League();
        $league->setSeason($season);

        $this->om->persist($league);

        $this->dispatcher->dispatch(LeagueEvents::LEAGUE_CREATED, new LeagueEvent($league));

        return $league;
    }

    public function addUserToLeague(League $league, User $user)
    {
        $league->addUser($user);
    }

    public function addCommissionerToLeague(League $league, User $user)
    {
        $this->addUserToLeague($league, $user);
        $league->addCommissioner($user);
    }

    public function removeUserFromLeague(League $league, User $user)
    {
        $this->getLeagueRepository()->removeUser($league, $user);
    }

    private function getLeagueRepository()
    {
        return $this->om->getRepository('SofaChampsBowlPickemBundle:League');
    }
}
