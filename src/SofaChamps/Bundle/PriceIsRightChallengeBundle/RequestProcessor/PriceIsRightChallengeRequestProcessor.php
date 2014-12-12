<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\RequestProcessor;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\RequestProcessor\LoginRequestProcessor;
use SofaChamps\Bundle\CoreBundle\RequestProcessor\RegistrationRequestProcessor;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Invite\InviteManager;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Portfolio\PortfolioManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * @DI\Service
 * @DI\Tag("sofachamps.request_processor.login")
 * @DI\Tag("sofachamps.request_processor.registration")
 */
class PriceIsRightChallengeRequestProcessor implements LoginRequestProcessor, RegistrationRequestProcessor
{
    protected $router;
    protected $om;
    protected $portfolioManager;

    /**
     * @DI\InjectParams({
     *      "router" = @DI\Inject("router"),
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "portfolioManager" = @DI\Inject("sofachamps.pirc.portfolio_manager"),
     * })
     */
    public function __construct(Router $router, ObjectManager $om, PortfolioManager $portfolioManager)
    {
        $this->router = $router;
        $this->om = $om;
        $this->portfolioManager = $portfolioManager;
    }

    public function processLoginRequest(Request $request, User $user)
    {
        if ($request->cookies->has(InviteManager::COOKIE_NAME)) {
            $game = $this->om->getRepository('SofaChampsPriceIsRightChallengeBundle:Game')->find($request->cookies->get(InviteManager::COOKIE_NAME));

            if (!$game) {
                return;
            }

            $portfolio = $game->getUserPortfolio($user);

            if (!$portfolio) {
                $portolfio = $this->portfolioManager->createPortfolio($game, $user);
                $this->om->flush();

                return new RedirectResponse($this->router->generate('pirc_portfolio_edit', array(
                    'season' => $game->getSeason(),
                    'id' => $portolfio->getId(),
                )));
            }
        }
    }

    public function processRegisrationRequest(Request $request)
    {
        if ($request->cookies->has(InviteManager::COOKIE_NAME)) {
            $game = $this->om->getRepository('SofaChampsPriceIsRightChallengeBundle:Game')->find($request->cookies->get(InviteManager::COOKIE_NAME));

            if ($game) {
                return new RedirectResponse($this->router->generate('pirc_join', array(
                    'gameId' => $game->getId(),
                    'season' => $game->getSeason(),
                )));
            }
        }
    }

    public function handleRegistrationCompleted(FilterUserResponseEvent $event)
    {
    }
}
