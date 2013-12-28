<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksetAnalyzer;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * GameSubscriber
 *
 * @DI\Service
 * @DI\Tag("kernel.event_subscriber")
 */
class GameSubscriber implements EventSubscriberInterface
{
    protected $analyzer;
    protected $om;

    /**
     * @DI\InjectParams({
     *      "analyzer" = @DI\Inject("sofachamps.bp.pickset_analyzer"),
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    public function __construct(PicksetAnalyzer $analyzer, ObjectManager $om)
    {
        $this->analyzer = $analyzer;
        $this->om = $om;
    }

    static public function getSubscribedEvents()
    {
        return array(
            GameEvents::GAME_COMPLETE => array(
                array('analyizePickSets', 0),
                array('updatePredictions', 10),
            ),
            GameEvents::GAME_UPDATED => array(
                array('clearQueryCache', 0),
            ),
        );
    }

    public function updatePredictions(GameEvent $event)
    {
        $game = $event->getGame();
        $this->om
            ->createQuery('UPDATE SofaChampsBowlPickemBundle:Prediction p SET p.winner = :winner WHERE p.game = :game')
            ->setParameters(array(
                'game' => $game,
                'winner' => $game->getWinner(),
            ))
            ->execute();
    }

    public function analyizePickSets(GameEvent $event)
    {
        $game = $event->getGame();
        $season = $game->getSeason();
        $this->analyzer->deleteAnalysis($season);
        $leagues = $this->om->getRepository('SofaChampsBowlPickemBundle:League')->findBySeason($season);
        foreach ($leagues as $league) {
            $this->analyzer->analyizeLeaguePickSets($league, $season);
        }
    }

    public function clearQueryCache(GameEvent $event)
    {
        $resultCache = $this->om->getConfiguration()->getResultCacheImpl();
        $resultCache->deleteAll();
    }
}
