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

        return array(
            'season' => $season,
            'user' => $user,
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
        $form = $this->getConfigForm(new Config());

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
     * @Template
     */
    public function createAction(Bracket $bracket, $season)
    {
        $form = $this->getConfigForm(new Config());

        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $config = $form->getData();
            $user = $this->getUser();
            $game = $this->getGameManager()->createGame($bracket, $config, $user);

            // Create a portfolio for this game
            $this->getPortfolioManager()->createPortfolio($game, $user);

            $this->getEntityManager()->persist($config);
            $this->getEntityManager()->flush();

            $this->addMessage('success', 'Game created');

            return $this->redirect($this->generateUrl('pirc_game_edit', array(
                'id' => $game->getId(),
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
     * @Route("/edit/{id}", name="pirc_game_edit")
     * @ParamConverter("bracket", class="SofaChampsPriceIsRightChallengeBundle:Game", options={"id" = "id"})
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Game $game, $season)
    {
        $form = $this->getConfigForm($game->getConfig());

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
        $form = $this->getConfigForm($game->getConfig());

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
