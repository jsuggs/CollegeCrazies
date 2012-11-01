<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\LeagueFormType;
use CollegeCrazies\Bundle\MainBundle\Form\LeagueJoinFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/league")
 */
class LeagueController extends Controller
{
    /**
     * @Route("/{leagueId}/home", name="league_home")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function homeAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->createQuery('SELECT g from CollegeCraziesMainBundle:Game g ORDER BY g.gameDate')->getResult();
        $users = $em->createQuery('SELECT u, p from CollegeCraziesMainBundle:User u
            JOIN u.pickSet p
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = :leagueId
            ORDER BY pg.id')
            ->setParameter('leagueId', $leagueId)
            ->getResult();

        $sortedUsers = $this->get('user.sorter')->sortUsersByPoints($users);

        return array(
            'games' => $games,
            'league' => $league,
            'users' => $sortedUsers,
        );
    }

    /**
     * @Route("/{leagueId}/picks", name="league_group_picks")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:League:picks.html.twig")
     */
    public function groupPicksAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();
        if (!$league->isLocked()) {
            $this->get('session')->setFlash('warning','You cannot view the group picks until the league locks');
            return $this->redirect('/');
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
            'league' => $league,
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
     * @Route("/join/{id}", name="league_join")
     * @Template("CollegeCraziesMainBundle:League:join.html.twig")
     * @Secure(roles="ROLE_USER")
     */
    public function joinAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();

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
     * @Route("/leaderboard", name="leaderboard")
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
     * @Route("/new", name="league_new")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $league = new League();
        $form = $this->getLeagueForm($league);

        return array('form' => $form->createView());
    }

    /**
     * @Route("/create", name="league_create")
     * @Template()
     * @Method({"POST"})
     * @Secure(roles="ROLE_USER")
     */
    public function createAction()
    {
        $league = new League();
        $form = $this->getLeagueForm($league);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $league = $form->getData();
            $league->addUser($this->getUser());
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($league);
            $em->flush();
        } else {
            return array('form' => $form->createView());
        }

        return $this->redirect($this->generateUrl('team_list'));
    }

    /**
     * @Route("/{leagueId}/picks", name="league_picks")
     * @Secure(roles="ROLE_USER")
     */
    public function picksAction($leagueId)
    {
        $user = $this->getUser();
        $league = $this->findLeague($leagueId);

        $pickSet = $user->getPickSet();
        if ($pickSet) {
            return $this->redirect($this->generateUrl('pickset_edit', array(
                'id' => $pickSet->getId()
            )));
        }

        return $this->redirect($this->generateUrl('picklist_new'));
    }

    private function getLeagueForm(League $league)
    {
        return $this->createForm(new LeagueFormType(), $league);
    }

    private function findLeague($id)
    {
        $league = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\League')
            ->find($id);

        if (!$league) {
            throw new NotFoundHttpException(sprintf('There was no league with id = %s', $id));
        }
        return $league;
    }
}
