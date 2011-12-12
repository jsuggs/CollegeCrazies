<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\TeamFormType;
use CollegeCrazies\Bundle\MainBundle\Form\LeagueJoinFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LeagueController extends Controller
{
    /**
     * @Route("/league/picks", name="league_picks")
     * @Template("CollegeCraziesMainBundle:League:picks.html.twig")
     */
    public function listAction()
    {
        return array();
        $em = $this->get('doctrine.orm.entity_manager');
        $teams = $em->getRepository('Team')->findAll();
        return array('teams' => $teams);
    }

    /**
     * @Route("/league/join/{id}", name="league_join")
     * @Template("CollegeCraziesMainBundle:League:join.html.twig")
     */
    public function joinAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if ($user == 'anon.') {
            throw new AccessDeniedException();
        }

        $league = $this->findLeague($id);
        $form = $this->createFormBuilder($league)
            ->add('id', 'hidden')
            ->add('password', 'password')
            ->getForm();

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $data = $request->request->get('form');
            $password = $data['password'];

            if ($password == $league->getPassword()) {
                $em = $this->get('doctrine.orm.entity_manager');
                $user->addLeague($league);

                $em->persist($league);
                $em->persist($user);
                $em->flush();
                return $this->redirect('/');
            } else {
                $this->get('session')->setFlash('warning','You password was not correct');
            }
        }

        return array(
            'league' => $league,
            'form' => $form->createView()
        );
        $teams = $em->getRepository('Team')->findAll();
        return array('teams' => $teams);
    }

    /**
     * @Route("/league/leaderboard", name="leaderboard")
     * @Template("CollegeCraziesMainBundle:League:leaderboard.html.twig")
     */
    public function leaderboardAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            $this->get('session')->setFlash('warning','You must be logged in to view the leaderboard');
            throw new AccessDeniedException();
        }

        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->isInTheLeague()) {
            $this->get('session')->setFlash('warning','You must be in the leage to view the leaderboard');
            return $this->redirect($this->generateUrl('league_join', array('id' => 1)));
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT u from CollegeCrazies\Bundle\MainBundle\Entity\User u JOIN u.leagues l WHERE l.id = 1');
        $users = $query->getResult();

        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/team/create", name="team_create")
     * @Template("CollegeCraziesMainBundle:Team:new.html.twig")
     */
    public function createAction()
    {
        $team = new Team();
        $form = $this->getTeamForm($team);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $team = $form->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($team);
            $em->flush();
        } else {
            return array('form' => $form->createView());
        }

        return $this->redirect($this->generateUrl('team_list'));
    }

    private function getTeamForm(Team $team)
    {
        return $this->createForm(new TeamFormType(), $team);
    }

    private function findLeague($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $league = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\League')->find($id);
        if (!$league) {
            throw new \NotFoundHttpException(sprintf('There was no league with id = %s', $id));
        }
        return $league;
    }
}
