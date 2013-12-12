<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * LeagueSubscriber
 *
 * @DI\Service
 * @DI\Tag("kernel.event_subscriber")
 */
class LeagueSubscriber implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(
            LeagueEvents::LEAGUE_CREATED => array(
                array('ensureCommissioner', 0),
            ),
        );
    }

    public function ensureCommissioner(LeagueEvent $event)
    {
    }
}
