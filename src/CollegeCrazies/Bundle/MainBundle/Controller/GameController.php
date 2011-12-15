<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
use CollegeCrazies\Bundle\MainBundle\Form\GameEditFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GameController extends Controller
{
    /**
     * @Route("/game/list", name="game_list")
     * @Template("CollegeCraziesMainBundle:Game:list.html.twig")
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
     * @Route("/admin/game/list", name="game_admin")
     * @Template("CollegeCraziesMainBundle:Game:admin.html.twig")
     */
    public function adminAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user == 'anon.') {
            throw new AccessDeniedException();
        }
        $em = $this->get('doctrine.orm.entity_manager');
        
        $query = $em->createQuery('SELECT g FROM CollegeCrazies\Bundle\MainBundle\Entity\Game g ORDER BY g.gameDate');
        $games = $query->getResult();

        return array(
            'games' => $games,
        );
    }

    /**
     * @Route("/admin/game/edit/{id}", name="game_edit")
     * @Template("CollegeCraziesMainBundle:Game:edit.html.twig")
     */
    public function editAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user == 'anon.') {
            throw new AccessDeniedException();
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $game = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->find($id);
        $form = $this->getGameForm($game);

        return array(
            'game' => $game,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/admin/game/update/{id}", name="game_update")
     */
    public function updateAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user == 'anon.') {
            throw new AccessDeniedException();
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $game = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->find($id);
        $form = $this->getGameForm($game);

        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $game = $form->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($game);
            $em->flush();
            $this->get('session')->setFlash('success','Game updated successfully');
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
