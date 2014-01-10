<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Form\GameFormType;
use SofaChamps\Bundle\SquaresBundle\Form\GameMapFormType;
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
        return array(
            'game' => $game,
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
     * @Template
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
        $form = $this->getGameForm($game);

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
     * @Route("/claim/{gameId}/{row}/{col}", name="squares_square_claim")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsSquaresBundle:Game", options={"id" = "gameId"})
     * @Method({"GET"})
     */
    public function claimSquareAction(Game $game, $row, $col)
    {
        $success = $this->getGameManager()->claimSquare($this->getUser(), $game, $row, $col);
        $this->getEntityManager()->flush();

        return new JsonResponse(array(
            'success' => $success,
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

    protected function getGameForm(Game $game = null)
    {
        return $this->createForm(new GameFormType(), $game);
    }

    protected function getGameMapForm(Game $game = null)
    {
        return $this->createForm(new GameMapFormType(), $game);
    }
}
