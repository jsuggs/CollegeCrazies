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
 * @DI\Service("sofachamps.bp.game_worker")
 */
class GameWorker
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

    public function updatePredictions(\GearmanJob $job)
    {
        $workload = json_decode($job->workload(), true);
        $game = $this->om->getRepository('SofaChampsBowlPickemBundle:Game')->find($workload['game']);

        $this->om
            ->createQuery('UPDATE SofaChampsBowlPickemBundle:Prediction p SET p.winner = :winner WHERE p.game = :game')
            ->setParameters(array(
                'game' => $game,
                'winner' => $game->getWinner(),
            ))
            ->execute();

        $season = $game->getSeason();
        $this->analyzer->deleteAnalysis($season);
        $leagues = $this->om->getRepository('SofaChampsBowlPickemBundle:League')->findBySeason($season);
        foreach ($leagues as $league) {
            $this->analyzer->analyizeLeaguePickSets($league, $season);
        }
    }
}
