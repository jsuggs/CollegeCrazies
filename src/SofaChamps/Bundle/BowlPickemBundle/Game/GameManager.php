<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Game;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Event\GameEvent;
use SofaChamps\Bundle\BowlPickemBundle\Event\GameEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * GameManager
 *
 * @DI\Service("sofachamps.bp.game_manager")
 */
class GameManager
{
    private $om;
    private $dispatcher;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "dispatcher" = @DI\Inject("event_dispatcher"),
     * })
     */
    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher)
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
    }

    public function createGame(Season $season)
    {
        $game = new League();
        $game->setSeason($season);

        $this->om->persist($game);

        $this->dispatcher->dispatch(LeagueEvents::LEAGUE_CREATED, new LeagueEvent($game));

        return $game;
    }

    public function updateGame(Game $game)
    {
        $this->dispatcher->dispatch(GameEvents::GAME_UPDATED, new GameEvent($game));
        if ($game->isComplete()) {
            $this->dispatcher->dispatch(GameEvents::GAME_COMPLETE, new GameEvent($game));
        }
    }
}
