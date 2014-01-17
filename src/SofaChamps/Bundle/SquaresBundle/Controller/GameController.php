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
 * @Route("/game")
 */
class GameController extends BaseController
{
    /**
     * @Route("/", name="squares_game_list")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function listAction()
    {
        return array(
            'games' => $this->getUser()->getSquaresGames(),
        );
    }

    /**
     * @Route("/view/{gameId}", name="squares_game_view")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"GET"})
     * @Template
     */
    public function viewAction(Game $game)
    {
        $user = $this->getUser();
        $player = $game->getPlayerForUser($user);

        return array(
            'game' => $game,
            'player' => $player,
            'player_forms' => $this->getPlayerForms($game),
        );
    }

    /**
     * @Route("/new", name="squares_game_new")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function newAction()
    {
        $form = $this->getGameForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="squares_game_create")
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @Template("SofaChampsSquaresBundle:Game:new.html.twig")
     */
    public function createAction()
    {
        $user = $this->getUser();
        $game = $this->getGameManager()->createGame($user);

        $form = $this->getGameForm($game);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('squares_game_edit', array(
                'gameId' => $game->getId(),
            )));
        }

        $this->addMessage('warning', 'There was an error creating your game');

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/edit/{gameId}", name="squares_game_edit")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Game $game)
    {
        $form = $this->getGameEditForm($game);

        return array(
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    /**
     * @Route("/update/{gameId}", name="squares_game_update")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"POST"})
     * @Template
     */
    public function updateAction(Game $game)
    {
        $form = $this->getGameForm($game);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('squares_game_edit', array(
                'gameId' => $game->getId(),
            )));
        }

        $this->addMessage('warning', 'There was an error updating your game');

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/claim/{gameId}/{playerId}/{row}/{col}", name="squares_square_claim")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @ParamConverter("player", class="SofaChampsSquaresBundle:Player", options={"id" = "playerId"})
     * @Method({"POST"})
     */
    public function claimSquareAction(Game $game, Player $player, $row, $col)
    {
        $square = $game->getSquare($row, $col);
        $success = $this->getGameManager()->claimSquare($player, $square);
        $this->getEntityManager()->flush();

        return new JsonResponse(array(
            'success' => $success,
            'html' => $this->renderView('SofaChampsSquaresBundle:Game:_td.html.twig', array(
                'player' => $player,
                'square' => $square,
                'game' => $game,
            )),
        ));
    }

    /**
     * @Route("/map/{gameId}", name="squares_map")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Template
     */
    public function mapAction(Game $game)
    {
        $form = $this->getGameMapForm($game);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();

                $this->addMessage('success', 'Map Updated');

                return $this->redirect($this->generateUrl('squares_map', array(
                    'gameId' => $game->getId(),
                )));
            }

            $this->addMessage('warning', 'There was an error updating the map');
        }

        return array(
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    /**
     * @Route("/payouts/{gameId}", name="squares_payouts")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Template
     */
    public function payoutsAction(Game $game)
    {
        $form = $this->getGamePayoutsForm($game);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();

                $this->addMessage('success', 'Payouts Updated');

                return $this->redirect($this->generateUrl('squares_payouts', array(
                    'gameId' => $game->getId(),
                )));
            }

            $this->addMessage('warning', 'There was an error updating the payouts');
        }

        return array(
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    /**
     * @Route("/players/{gameId}", name="squares_players")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Template
     */
    public function playersAction(Game $game)
    {
        $form = $this->getGamePayoutsForm($game);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();

                $this->addMessage('success', 'Payouts Updated');

                return $this->redirect($this->generateUrl('squares_payouts', array(
                    'gameId' => $game->getId(),
                )));
            }

            $this->addMessage('warning', 'There was an error updating the payouts');
        }

        return array(
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    protected function getPlayerForms(Game $game)
    {
        $forms = array();
        foreach ($game->getPlayers() as $player) {
            $forms[$player->getId()] =  $this->getPlayerForm($player)->createView();
        }

        return $forms;
    }
}
