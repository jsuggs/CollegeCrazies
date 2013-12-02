<?php

namespace SofaChamps\Bundle\BowlPickemBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the registration
 *
 * @DI\Service
 * @DI\Tag("kernel.event_subscriber")
 */
class RegistrationListener implements EventSubscriberInterface
{
    private $router;

    /**
     * @DI\InjectParams({
     *      "router" = @DI\Inject("router")
     * })
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        if (strpos($event->getRequest()->headers->get('referer'), 'bowl-pickem') === false) {
            return null;
        }

        // TODO - Get the right season
        $url = $this->router->generate('pickset_new', array(
            'season' => '2013',
        ));
        $event->setResponse(new RedirectResponse($url));
    }
}
