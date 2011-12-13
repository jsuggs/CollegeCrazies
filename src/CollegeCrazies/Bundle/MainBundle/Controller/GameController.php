<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
use CollegeCrazies\Bundle\MainBundle\Form\GameEditFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * @Route("/game/list", name="game_list")
     * @Template("CollegeCraziesMainBundle:Game:list.html.twig")
     */
    public function listAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->findAll();
        return array('games' => $games);
    }

    /**
     * @Route("/game/edit/{id}", name="game_edit")
     * @Template("CollegeCraziesMainBundle:Game:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $game = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->find($id);
        $form = $this->getGameForm($game);

        return array(
            'game' => $game,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/game/update/{id}", name="game_update")
     */
    public function updateAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $game = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->find($id);
        $form = $this->getGameForm($game);

        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $game = $form->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($game);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('game_edit', array(
            'id' => $game->getId()
        )));
    }

    private function findGame($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $game = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->find($id);
        if (!$game) {
            throw new \NotFoundHttpException(sprintf('There was no game with id = %s', $id));
        }

        return $game;
    }

    private function getGameForm(Game $game)
    {
        return $this->createForm(new GameEditFormType(), $game);
    }
}
