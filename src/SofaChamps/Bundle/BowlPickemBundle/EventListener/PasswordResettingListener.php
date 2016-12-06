<?php

namespace SofaChamps\Bundle\BowlPickemBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 *
 * @DI\Service
 * @DI\Tag("kernel.event_subscriber")
 */
class PasswordResettingListener implements EventSubscriberInterface
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
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onPasswordResettingSuccess',
        );
    }

    public function onPasswordResettingSuccess(FormEvent $event)
    {
        if (strpos($event->getRequest()->headers->get('referer'), 'bowl-pickem') === false) {
            return null;
        }

        // TODO - get right season
        $url = $this->router->generate('pickset_manage', array(
            'season' => 2016,
        ));
        $event->setResponse(new RedirectResponse($url));
    }
}
