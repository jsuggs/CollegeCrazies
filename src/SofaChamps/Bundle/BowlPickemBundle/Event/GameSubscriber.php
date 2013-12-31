<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Mmoreram\GearmanBundle\Driver\Gearman;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * GameSubscriber
 *
 * @Gearman\Work(
 *      name = "GameSubscriber",
 *      description = "Handles progressing game events",
 *      defaultMethod = "doBackground",
 *      service = "sofachamps.bp.game_worker",
 * )
 * @DI\Service
 * @DI\Tag("kernel.event_subscriber")
 */
class GameSubscriber implements EventSubscriberInterface
{
    protected $gearman;
    protected $om;

    /**
     * @DI\InjectParams({
     *      "gearman" = @DI\Inject("gearman"),
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     * })
     */
    public function __construct($gearman, ObjectManager $om)
    {
        $this->gearman = $gearman;
        $this->om = $om;
    }

    static public function getSubscribedEvents()
    {
        return array(
            GameEvents::GAME_COMPLETE => array(
                array('updatePredictions', 0),
            ),
            GameEvents::GAME_UPDATED => array(
                array('clearQueryCache', 0),
            ),
        );
    }

    /**
     * @Gearman\Job(
     *      name = "updatePredictions",
     *      description = "Update the predictions and analyize the leagues",
     *      iterations = 5,
     * )
     */
    public function updatePredictions(GameEvent $event)
    {
        $game = $event->getGame();

        $payload = array(
            'game' => $game->getId(),
        );

        $this->gearman->doNormalJob('SofaChampsBundleBowlPickemBundleEventGameSubscriber~updatePredictions', json_encode($payload));
    }

    public function clearQueryCache(GameEvent $event)
    {
        $resultCache = $this->om->getConfiguration()->getResultCacheImpl();
        $resultCache->deleteAll();
    }
}
