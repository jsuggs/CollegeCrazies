<?php

namespace SofaChamps\Bundle\BowlPickemBundle\RequestProcessor;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\RequestProcessor\LoginRequestProcessor;
use SofaChamps\Bundle\CoreBundle\RequestProcessor\RegistrationRequestProcessor;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * BowlPickemProcessor
 *
 * @DI\Service
 * @DI\Tag("sofachamps.request_processor.login")
 * @DI\Tag("sofachamps.request_processor.registration")
 */
class BowlPickemRequestProcessor implements LoginRequestProcessor, RegistrationRequestProcessor
{
    /**
     * @DI\InjectParams({
     *      "router" = @DI\Inject("router"),
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "seasonManager" = @DI\Inject("sofachamps.bp.season_manager"),
     *      "picksLockedManager" = @DI\Inject("sofachamps.bp.picks_locked_manager"),
     * })
     */
    public function __construct(UrlGeneratorInterface $router, ObjectManager $om, SeasonManager $seasonManager, PicksLockedManager $picksLockedManager)
    {
        $this->router = $router;
        $this->om = $om;
        $this->seasonManager = $seasonManager;
        $this->picksLockedManager = $picksLockedManager;
    }

    public function processLoginRequest(Request $request, User $user)
    {
        if ($request->cookies->has('bp_league_join')) {
            $league = $this->om->getRepository('SofaChampsBowlPickemBundle:League')->find($request->cookies->get('bp_league_join'));

            if ($league) {
                if ($league->isUserInLeague($user)) {
                    $request->getSession()->setFlash('error', 'You are already in the league');
                } else {
                    $user->addLeague($league);
                    $season = $this->seasonManager->getCurrentSeason();

                    $pickSets = $user->getPickSetsForSeason($season);

                    switch (count($pickSets)) {
                        case 0:
                            $response = new RedirectResponse($this->router->generate('pickset_new', array(
                                'season' => $season->getSeason(),
                            )));
                            break;
                        case 1:
                            $pickSet = $pickSets->first();
                            $league->addPickSet($pickSet);
                            $request->getSession()->setFlash('success', sprintf('Pickset assigned to league "%s"', $league->getName()));

                            $response = new RedirectResponse($this->router->generate('league_home', array(
                                'season' => $season->getSeason(),
                                'leagueId' => $league->getId(),
                            )));
                            break;
                        default:
                            $response = new RedirectResponse($this->router->generate('league_assoc', array(
                                'season' => $season->getSeason(),
                                'leagueId' => $league->getId(),
                            )));
                            break;
                    }

                    //$response->headers->clearCookie('bp_league_join');
                    $response->sendHeaders();
                    $this->om->flush();

                    return $response;
                }
            }
        }
    }

    public function processRegisrationRequest(Request $request)
    {
    }

    public function handleRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->cookies->has('bp_league_join')) {
            $league = $this->om->getRepository('SofaChampsBowlPickemBundle:League')->find($request->cookies->get('bp_league_join'));

            if ($league) {
                $event->getUser()->addLeague($league);
                $this->om->flush();
            }
        }
    }
}
