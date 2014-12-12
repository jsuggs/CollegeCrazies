<?php

namespace SofaChamps\Bundle\CoreBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use SofaChamps\Bundle\CoreBundle\RequestProcessor\RegistrationRequestProcessor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @DI\Service("sofachamps.listener.registration_listener")
 * @DI\Tag("kernel.event_subscriber")
 */
class RegistrationListener implements EventSubscriberInterface
{
    protected $processors;

    public function addRequestProcessor(RegistrationRequestProcessor $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        foreach ($this->processors as $processor) {
            if ($response = $processor->processRegisrationRequest($event->getRequest())) {
                $event->setResponse($response);
                break;
            }
        }
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        foreach ($this->processors as $processor) {
            $processor->handleRegistrationCompleted($event);
        }
    }
}
