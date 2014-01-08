<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Form\GameFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

    protected function getGameForm(Game $game = null)
    {
        return $this->createForm(new GameFormType(), $game);
    }
}
