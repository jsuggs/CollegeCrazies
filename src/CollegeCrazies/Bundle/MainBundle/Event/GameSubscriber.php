<?php

namespace CollegeCrazies\Bundle\MainBundle\Event;

use CollegeCrazies\Bundle\MainBundle\Service\PicksetAnalyzer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GameSubscriber implements EventSubscriberInterface
{
    protected $analyzer;
    protected $om;

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
        );
    }

    public function updatePredictions(GameEvent $event)
    {
        $game = $event->getGame();
        $this->om
            ->createQuery('UPDATE CollegeCraziesMainBundle:Prediction p SET p.winner = :winner WHERE p.game = :game')
            ->setParameters(array(
                'game' => $game,
                'winner' => $game->getWinner(),
            ))
            ->execute();
    }

    public function analyizePickSets(GameEvent $event)
    {
        $this->analyzer->deleteAnalysis();
        $leagues = $this->om->getRepository('CollegeCraziesMainBundle:League')->findAll();
        foreach ($leagues as $league) {
            $this->analyzer->analyizeLeaguePickSets($league);
        }
    }
}
