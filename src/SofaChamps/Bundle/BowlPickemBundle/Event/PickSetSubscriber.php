<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * PickSetSubscriber
 *
 * @DI\Service
 * @DI\Tag("kernel.event_subscriber")
 */
class PickSetSubscriber implements EventSubscriberInterface
{
    protected $om;
    protected $session;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "session" = @DI\Inject("session"),
     * })
     */
    public function __construct(ObjectManager $om, Session $session)
    {
        $this->om = $om;
        $this->session = $session;
    }

    static public function getSubscribedEvents()
    {
        return array(
            PickSetEvents::PICKSET_CREATED => array(
                array('assignPickSets', 0),
            ),
        );
    }

    public function assignPickSets(PickSetEvent $event)
    {
        //TODO
    }
}
