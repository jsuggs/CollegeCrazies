<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use SofaChamps\Bundle\SquaresBundle\Form\GamePlayersFormType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/player")
 */
class PlayerController extends BaseController
{
    /**
     * @Route("/list/{gameId}", name="squares_players")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Template
     */
    public function listAction(Game $game)
    {
        $form = $this->createForm(new GamePlayersFormType(), $game);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                $this->addMessage('success', 'Players updated');
            }
        }

        return array(
            'game' => $game,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/edit/{playerId}", name="squares_player_edit")
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Player $player)
    {
        $form = $this->getPlayerForm($player);

        return array(
            'player' => $player,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{playerId}", name="squares_player_update")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("player", class="SofaChampsSquaresBundle:Player", options={"id" = "playerId"})
     * @SecureParam(name="player", permissions="EDIT")
     * @Method({"POST"})
     */
    public function updateAction(Player $player)
    {
        $form = $this->getPlayerForm($player);
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $this->getEntityManager()->flush();
            $this->addMessage('success', 'Player Updated');
        } else {
            $this->addMessage('danger', 'Error updating player');
        }

        return $this->redirect($this->generateUrl('squares_game_view', array(
            'gameId' => $player->getGame()->getId(),
        )));
    }
}
