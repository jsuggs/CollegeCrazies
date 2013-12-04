<?php

namespace SofaChamps\Bundle\BowlPickemBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager;
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
     *      "router" = @DI\Inject("router"),
     *      "seasonManager" = @DI\Inject("sofachamps.bp.season_manager"),
     * })
     */
    public function __construct(UrlGeneratorInterface $router, SeasonManager $seasonManager)
    {
        $this->router = $router;
        $this->seasonManager = $seasonManager;
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
        // TODO - Make this conditional
        $url = $this->router->generate('pickset_new', array(
            'season' => $this->seasonManager->getCurrentSeason(),
        ));

        $event->setResponse(new RedirectResponse($url));
    }
}
