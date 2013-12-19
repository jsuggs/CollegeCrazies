<?php

namespace SofaChamps\Bundle\BowlPickemBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;
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
    private $seasonManager;
    private $picksLockedManager;

    /**
     * @DI\InjectParams({
     *      "router" = @DI\Inject("router"),
     *      "seasonManager" = @DI\Inject("sofachamps.bp.season_manager"),
     *      "picksLockedManager" = @DI\Inject("sofachamps.bp.picks_locked_manager"),
     * })
     */
    public function __construct(UrlGeneratorInterface $router, SeasonManager $seasonManager, PicksLockedManager $picksLockedManager)
    {
        $this->router = $router;
        $this->seasonManager = $seasonManager;
        $this->picksLockedManager = $picksLockedManager;
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
        // TODO - This is better, but as we add more overlapping games, this may not be sufficient
        if (!$this->picksLockedManager->arePickLocked()) {
            $url = $this->router->generate('pickset_new', array(
                'season' => $this->seasonManager->getCurrentSeason(),
            ));

            $event->setResponse(new RedirectResponse($url));
        }
    }
}
