<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Config;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game;

/**
 * @Route("/{season}/game")
 */
class GameController extends BaseController
{
    /**
     * @Route("/list", name="pirc_game_list")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function listAction(Bracket $bracket, $season)
    {
        $user = $this->getUser();
        $games = $this->getRepository('SofaChampsPriceIsRightChallengeBundle:Game')->findUsersGamesForBracket($user, $bracket);

        return array(
            'games' => $games,
            'season' => $season,
        );
    }

    /**
     * @Route("/new", name="pirc_game_new")
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function newAction(Bracket $bracket, $season)
    {
        $form = $this->getGameForm(new Game($bracket, new Config()));

        return array(
            'season' => $season,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="pirc_game_create")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @Template("SofaChampsPriceIsRightChallengeBundle:Game:new.html.twig")
     */
    public function createAction(Bracket $bracket, $season)
    {
        $user = $this->getUser();
        $game = $this->getGameManager()->createGame($bracket, $user);
        $form = $this->getGameForm($game);

        $form->bind($this->getRequest());

        if ($form->isValid()) {
            // Create a portfolio for this game
            $portfolio = $this->getPortfolioManager()->createPortfolio($game, $user);

            $this->getEntityManager()->flush();

            $this->addMessage('success', 'Congratulations! Your game was created.  Now you can edit your portfolio.');

            return $this->redirect($this->generateUrl('pirc_portfolio_edit', array(
                'id' => $portfolio->getId(),
                'season' => $season,
            )));
        } else {
            $this->addMessage('error', 'There was an error creating the game');
        }

        return array(
            'season' => $season,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/view/{id}", name="pirc_game_view")
     * @ParamConverter("bracket", class="SofaChampsPriceIsRightChallengeBundle:Game", options={"id" = "id"})
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function viewAction(Game $game, $season)
    {
        $form = $this->getGameForm($game);

        return array(
            'season' => $season,
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    /**
     * @Route("/edit/{id}", name="pirc_game_edit")
     * @ParamConverter("bracket", class="SofaChampsPriceIsRightChallengeBundle:Game", options={"id" = "id"})
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Game $game, $season)
    {
        $form = $this->getGameForm($game);

        return array(
            'season' => $season,
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    /**
     * @Route("/edit/{id}", name="pirc_game_update")
     * @ParamConverter("bracket", class="SofaChampsPriceIsRightChallengeBundle:Game", options={"id" = "id"})
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @Template("SofaChampsPriceIsRightChallengeBundle:Game:edit.html.twig")
     */
    public function updateAction(Game $game, $season)
    {
        $form = $this->getGameForm($game);

        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $this->getEntityManager()->flush();

            $this->addMessage('success', 'Game updated');

            return $this->redirect($this->generateUrl('pirc_game_edit', array(
                'id' => $game->getId(),
                'season' => $season,
            )));
        } else {
            $this->addMessage('warning', 'There was an error updating the game');
        }

        return array(
            'season' => $season,
            'form' => $form->createView(),
            'game' => $game,
        );
    }
}
