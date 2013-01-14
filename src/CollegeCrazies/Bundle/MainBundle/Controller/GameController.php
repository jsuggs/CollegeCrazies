<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
use CollegeCrazies\Bundle\MainBundle\Event\GameEvent;
use CollegeCrazies\Bundle\MainBundle\Event\GameEvents;
use CollegeCrazies\Bundle\MainBundle\Form\GameEditFormType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/list", name="_bp_game_sidebar")
     * @Template("CollegeCraziesMainBundle:Game:list.html.twig")
     * @Cache(expires="+5 minutes")
     */
    public function listAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $upcomingQuery = $em->createQuery('SELECT g FROM CollegeCrazies\Bundle\MainBundle\Entity\Game g
            WHERE g.homeTeamScore is null
            ORDER BY g.gameDate')->setMaxResults(5);
        $upcoming = $upcomingQuery->getResult();

        $completedQuery = $em->createQuery('SELECT g FROM CollegeCrazies\Bundle\MainBundle\Entity\Game g
            WHERE g.homeTeamScore is not null
            ORDER BY g.gameDate desc');
        $completed = $completedQuery->getResult();

        return array(
            'upcoming' => $upcoming,
            'completed' => $completed,
        );
    }

    /**
     * @Route("/admin/list", name="game_admin")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("CollegeCraziesMainBundle:Admin:games.html.twig")
     */
    public function adminAction()
    {
        $user = $this->getUser();

        $em = $this->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT g FROM CollegeCrazies\Bundle\MainBundle\Entity\Game g ORDER BY g.gameDate');
        $games = $query->getResult();

        return array(
            'games' => $games,
        );
    }

    /**
     * @Route("/admin/new", name="game_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function newAction()
    {
        $game = new Game();
        $form = $this->getGameForm($game);

        return array(
            'form' => $form->createView(),
            'game' => $game,
        );
    }

    /**
     * @Route("/admin/create", name="game_create")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("CollegeCraziesMainBundle:Game:new.html.twig")
     */
    public function createAction()
    {
        $game = new Game();
        $form = $this->getGameForm($game);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $game = $form->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($game);
            $em->flush();
            $this->get('session')->getFlashBag()->set('success', 'Game Created');

            return $this->redirect($this->generateUrl('game_edit', array(
                'gameId' => $game->getId()
            )));
        }

        return array(
            'game' => $game,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/admin/game/edit/{gameId}", name="game_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function editAction($gameId)
    {
        $game = $this->findGame($gameId);
        $form = $this->getGameForm($game);

        return array(
            'game' => $game,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/admin/game/update/{gameId}", name="game_update")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("CollegeCraziesMainBundle:Game:edit.html.twig")
     */
    public function updateAction($gameId)
    {
        $game = $this->findGame($gameId);
        $form = $this->getGameForm($game);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($game);

            // If the game is complete, then dispatch an event
            if ($game->isComplete()) {
                $this->get('event_dispatcher')->dispatch(GameEvents::GAME_COMPLETE, new GameEvent($game));
            }

            $em->flush();
            $this->get('session')->getFlashBag()->set('success', 'Game updated successfully');
        }

        return $this->redirect($this->generateUrl('game_edit', array(
            'gameId' => $game->getId()
        )));
    }

    private function findGame($gameId)
    {
        $game = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')
            ->find($gameId);

        if (!$game) {
            throw new \NotFoundHttpException(sprintf('There was no game with id = %s', $gameId));
        }

        return $game;
    }

    private function getGameForm(Game $game = null)
    {
        return $this->createForm(new GameEditFormType(), $game);
    }
}
