<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
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
    public function playersAction(Game $game)
    {
        $players = $game->getPlayers()->toArray();
        $forms = $this->getPlayerForms($players);

        return array(
            'game' => $game,
            'forms' => $forms,
            'players' => $players,
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
     * @Method({"POST"})
     */
    public function updateAction(Player $player)
    {
        $form = $this->getPlayerForm($player);
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $success = true;
            $this->getEntityManager()->flush();
        } else {
            $success = false;
        }

        return new JsonResponse(array(
            'success' => $success,
        ));
    }

    protected function getPlayerForms(array $players)
    {
        $forms = array();
        foreach ($players as $player) {
            $forms[$player->getId()] =  $this->getPlayerForm($player)->createView();
        }

        return $forms;
    }
}
