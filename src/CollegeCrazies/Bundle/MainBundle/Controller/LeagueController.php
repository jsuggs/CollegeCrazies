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
    public function groupPicksAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $league = $this->findLeague(1);
            if (!$league->isLocked()) {
                $this->get('session')->setFlash('warning','You cannot view the group picks until the league locks');
                return $this->redirect('/');
            }
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT g from CollegeCrazies\Bundle\MainBundle\Entity\Game g ORDER BY g.gameDate');
        $games = $query->getResult();

        //TODO only in out league
        $users = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\User')->findAll();
        $query = $em->createQuery('SELECT u, p from CollegeCrazies\Bundle\MainBundle\Entity\User u 
            JOIN u.pickSet p 
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = 1 
            ORDER BY pg.id');
        $users = $query->getResult();

        $userSorter = $this->get('user.sorter');
        $sortedUsers = $userSorter->sortUsersByPoints($users);

        $curUser = $this->get('security.context')->getToken()->getUser();

        return array(
            'games' => $games,
            'users' => $sortedUsers,
            'curUser' => $curUser,
        );
    }

    /**
     * @Route("/admin/users/nopicks", name="users_nopicks")
     * @Template("CollegeCraziesMainBundle:League:nopicks.html.twig")
     */
    public function userNopicksAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT u, p, pk from CollegeCrazies\Bundle\MainBundle\Entity\User u 
            JOIN u.pickSet p 
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = 1
            AND pk.team IS NULL 
            ORDER BY pg.id');
        $users = $query->getResult();

        return array(
            'users' => $users,
        );
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

        $query = $em->createQuery('SELECT u, p, l, pk, pg from CollegeCrazies\Bundle\MainBundle\Entity\User u 
            JOIN u.pickSet p 
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = 1 
            ORDER BY pg.id');
        $users = $query->getResult();

        $userSorter = $this->get('user.sorter');
        $sortedUsers = $userSorter->sortUsersByPoints($users);

        return array(
            'users' => $sortedUsers
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
