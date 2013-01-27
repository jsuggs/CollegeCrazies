<?php

namespace SofaChamps\Bundle\SecurityBundle\Component\Authentication\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $router;
    protected $security;
    protected $session;

    public function __construct(Router $router, SecurityContext $security, Session $session, ObjectManager $om)
    {
        $this->router = $router;
        $this->security = $security;
        $this->session = $session;
        $this->om = $om;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->session->has('auto_league_assoc')) {
            $league = $this->om->getRepository('SofaChampsBowlPickemBundle:League')->find($this->session->get('auto_league_assoc'));

            if ($league) {
                $user = $token->getUser();

                if ($league->isUserInLeague($user)) {
                    $this->session->setFlash('error', 'You are already in the league');
                } else {
                    $user->addLeague($league);

                    $pickSets = $user->getPicksets();

                    switch (count($pickSets)) {
                        case 0:
                            $response = new RedirectResponse($this->router->generate('pickset_new'));
                            break;
                        case 1:
                            $pickSet = $pickSets[0];
                            $league->addPickSet($pickSet);
                            $this->session->setFlash('success', sprintf('Pickset assigned to league "%s"', $league->getName()));

                            $response = new RedirectResponse($this->router->generate('league_home', array(
                                'leagueId' => $league->getId(),
                            )));
                            break;
                        default:
                            $response = new RedirectResponse($this->router->generate('league_assoc', array(
                                'leagueId' => $league->getId(),
                            )));
                            break;
                    }

                    $this->session->remove('auto_league_assoc');
                    $this->om->flush();
                }
            }
        }

        // If no response, take them back to where they were coming from
        if (!isset($response)) {
            $referer_url = $request->headers->get('referer');
            // Don't go back to the login
            if (0 === substr_compare($referer_url, 'login', -5, 5)) {
                $referer_url = '/';
            }
            $response = new RedirectResponse($referer_url);
        }

        return $response;
    }
}
