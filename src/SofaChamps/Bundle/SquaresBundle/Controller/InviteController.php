<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Game\InviteManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/invite")
 */
class InviteController extends BaseController
{
    /**
     * @Route("/{gameId}", name="squares_invite")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"GET"})
     * @Template
     */
    public function inviteAction(Game $game)
    {
        $form = $this->getInviteForm($game);

        return array(
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    /**
     * @Route("/send/{gameId}", name="squares_invite_send")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"POST"})
     */
    public function sendInvitesAction(Game $game)
    {
        $form = $this->getInviteForm($game);
        $form->bind($this->getRequest());

        $data = $form->getData();
        $emails = $this->getEmailInputParser()->parseEmails($data['invites']);

        $this->addMessage('info', sprintf('Sending %d invites', count($emails)));

        $user = $this->getUser();
        $this->getInviteManager()->sendInvitesToEmails($game, $user, $emails);

        $this->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('squares_game_view', array(
            'gameId' => $game->getId(),
        )));
    }

    /**
     * @Route("/join/{gameId}", name="squares_join")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"GET"})
     * @Template
     */
    public function joinAction(Game $game)
    {
        $user = $this->getUser();

        if ($user) {
            $player = $game->getPlayerForUser($user);

            if (!$player) {
                $player = $this->getPlayerManager()->createPlayer($user, $game);
                $this->addMessage('success', 'Added to game');
            } else {
                $this->addMessage('info', 'You were already in the game');
            }
            $this->getGameManager()->addPlayerToGame($game, $player);
            $this->getEntityManager()->flush();
        } else {
            $response = $this->getResponse();
            $this->setCookie($response, InviteManager::COOKIE_NAME, $game->getId());
            $response->sendHeaders();

            $this->addMessage('info', 'You can join the squares game after you login or create an account');
            throw new AccessDeniedException('Must be logged in to join squares');
        }

        return array(
            'game' => $game,
        );
    }
}
