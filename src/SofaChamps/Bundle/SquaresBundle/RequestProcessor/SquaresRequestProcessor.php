<?php

namespace SofaChamps\Bundle\SquaresBundle\RequestProcessor;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\RequestProcessor\LoginRequestProcessor;
use SofaChamps\Bundle\SquaresBundle\Game\InviteManager;
use SofaChamps\Bundle\SquaresBundle\Game\PlayerManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * SquaresProcessor
 *
 * @DI\Service
 * @DI\Tag("sofachamps.request_processor.login")
 */
class SquaresRequestProcessor implements LoginRequestProcessor
{
    /**
     * @DI\InjectParams({
     *      "router" = @DI\Inject("router"),
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "playerManager" = @DI\Inject("sofachamps.squares.player_manager"),
     * })
     */
    public function __construct(Router $router, ObjectManager $om, PlayerManager $playerManager)
    {
        $this->router = $router;
        $this->om = $om;
        $this->playerManager = $playerManager;
    }

    public function processLoginRequest(Request $request, User $user)
    {
        if ($request->cookies->has(InviteManager::COOKIE_NAME)) {
            $game = $this->om->getRepository('SofaChampsSquaresBundle:Game')->find($request->cookies->get(InviteManager::COOKIE_NAME));

            if (!$game) {
                return;
            }

            $player = $game->getPlayerForUser($user);

            if (!$player) {
                $this->playerManager->createPlayer($user, $game);
                $this->om->flush();
                return new RedirectResponse($this->router->generateUrl('squares_game_view', array(
                    'gameId' => $game->getId(),
                )));
            }
        }
    }
}


