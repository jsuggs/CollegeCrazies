<?php

namespace SofaChamps\Bundle\BowlPickemBundle\RequestProcessor;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SecurityBundle\RequestProcessor\RequestProcessor;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;

/**
 * BowlPickemProcessor
 *
 * @DI\Service
 * @DI\Tag("sofachamps.request_processor")
 */
class BowlPickemProcessor implements RequestProcessor
{
    /**
     * @DI\InjectParams({
     *      "router" = @DI\Inject("router"),
     *      "session" = @DI\Inject("session"),
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "seasonManager" = @DI\Inject("sofachamps.bp.season_manager"),
     * })
     */
    public function __construct(Router $router, Session $session, ObjectManager $om, SeasonManager $seasonManager)
    {
        $this->router = $router;
        $this->session = $session;
        $this->om = $om;
        $this->seasonManager = $seasonManager;
    }

    public function processRequest(Request $request, User $user)
    {
        if ($this->session->has('auto_league_assoc')) {
            $league = $this->om->getRepository('SofaChampsBowlPickemBundle:League')->find($this->session->get('auto_league_assoc'));

            if ($league) {
                if ($league->isUserInLeague($user)) {
                    $this->session->setFlash('error', 'You are already in the league');
                } else {
                    $user = $token->getUser();
                    $user->addLeague($league);
                    $season = $this->seasonManager->getCurrentSeason();

                    $pickSets = $user->getPickSetsForSeason($season);

                    switch (count($pickSets)) {
                        case 0:
                            $response = new RedirectResponse($this->router->generate('pickset_new', array(
                                'season' => $season,
                            )));
                            break;
                        case 1:
                            $pickSet = $pickSets[0];
                            $league->addPickSet($pickSet);
                            $this->session->setFlash('success', sprintf('Pickset assigned to league "%s"', $league->getName()));

                            $response = new RedirectResponse($this->router->generate('league_home', array(
                                'season' => $season,
                                'leagueId' => $league->getId(),
                            )));
                            break;
                        default:
                            $response = new RedirectResponse($this->router->generate('league_assoc', array(
                                'season' => $season,
                                'leagueId' => $league->getId(),
                            )));
                            break;
                    }

                    $this->session->remove('auto_league_assoc');
                    $this->om->flush();
                }
            }

            if ($response) {
                return $response;
            }
        }
    }
}

