<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use SofaChamps\Bundle\SquaresBundle\Form\GameResultsFormType;
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
            $this->addMessage('success', 'Squares game created');

            return $this->redirect($this->generateUrl('squares_game_view', array(
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
            $this->addMessage('success', 'Squares game updated');

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
     * @Route("/claim/{gameId}/{row}/{col}", name="squares_square_claim")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"POST"})
     */
    public function claimSquareAction(Game $game, $row, $col)
    {
        $player = $this->findPlayer($this->getRequest()->request->get('playerId'));
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
     * @Route("/results/{gameId}", name="squares_results")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Template
     */
    public function resultsAction(Game $game)
    {
        $form = $this->createForm(new GameResultsFormType(), $game);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                $this->addMessage('success', 'Results updated');
            }
        }

        return array(
            'game' => $game,
            'form' => $form->createView(),
        );
    }
}
