<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\LeagueFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/league")
 */
class LeagueController extends Controller
{
    /**
     * @Route("/{leagueId}/home", name="league_home")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function homeAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:User')
            ->findUsersInLeague($league);

        return array(
            'league' => $league,
            'users' => $users,
        );
    }

    /**
     * @Route("/find", name="league_find")
     * @Template
     */
    public function findAction()
    {
        $leagues = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:League')
            ->findAllPublic();

        return array(
            'leagues' => $leagues,
        );
    }

    /**
     * @Route("/{leagueId}/group-picks", name="league_group_picks")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:League:picks.html.twig")
     */
    public function groupPicksAction($leagueId)
    {
        $league = $this->findLeague($leagueId);

        if (!$league->isLocked()) {
            $this->get('session')->setFlash('warning', 'You cannot view the group picks until the league locks');
            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $leagueId,
            )));
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->createQuery('SELECT g FROM CollegeCraziesMainBundle:Game g ORDER BY g.gameDate')->getResult();

        $query = $em->createQuery('SELECT u, p from CollegeCraziesMainBundle:User u
            JOIN u.pickSets p
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = :leagueId
            ORDER BY pg.id');
        $users = $query->setParameter('leagueId', $leagueId)->getResult();

        $userSorter = $this->get('user.sorter');
        $sortedUsers = $userSorter->sortUsersByPoints($users);

        $curUser = $this->getUser();

        return array(
            'games' => $games,
            'league' => $league,
            'users' => $sortedUsers,
            'curUser' => $curUser,
        );
    }

    /**
     * @Route("/{leagueId}/join", name="league_join")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function joinAction($leagueId)
    {
        $league = $this->findLeague($leagueId);

        $form = $this->createFormBuilder($league)
            ->add('id', 'hidden')
            ->add('password', 'password')
            ->getForm();

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $user = $this->getUser();
            $data = $request->request->get('form');
            $password = $data['password'];

            if ($password == $league->getPassword()) {
                $this->addUserToLeague($league, $user);
                return $this->redirect($this->generateUrl('league_home', array(
                    'leagueId' => $leagueId,
                )));
            } else {
                $this->get('session')->setFlash('error', 'The password was not correct');
            }
        }

        return array(
            'league' => $league,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{leagueId}/leaderboard", name="league_leaderboard")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function leaderboardAction($leagueId)
    {
        $league = $this->findLeague($leagueId);

        $user = $this->getUser();
        if (!$user->isInTheLeague($league)) {
            $this->get('session')->setFlash('warning', 'You must be in the league to view the leaderboard');
            return $this->redirect($this->generateUrl('league_join', array(
                'leagueId' => $leagueId,
            )));
        }

        $em = $this->get('doctrine.orm.entity_manager');

        $users = $em->createQuery('SELECT u, p, l, pk, pg from CollegeCraziesMainBundle:User u
            JOIN u.pickSets p
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = :leagueId
            ORDER BY pg.id')
            ->setParameter('leagueId', $leagueId)
            ->getResult();

        $sortedUsers = $this->get('user.sorter')->sortUsersByPoints($users);

        return array(
            'users' => $sortedUsers,
            'league' => $league,
        );
    }

    /**
     * @Route("/new", name="league_new")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function newAction()
    {
        $league = new League();
        $form = $this->getLeagueForm($league);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="league_create")
     * @Method({"POST"})
     * @Secure(roles="ROLE_USER")
     * @Template
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
            return array(
                'form' => $form->createView(),
            );
        }

        return $this->redirect($this->generateUrl('league_home', array(
            'leagueId' => $league->getId(),
        )));
    }

    /**
     * @Route("/{leagueId}/edit", name="league_edit")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function editAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You cannot view this league');
            return $this->redirect('/');
        } elseif (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->setFlash('warning', 'You do not have permissions to edit this league');

            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $league->getId(),
            )));
        }

        $form = $this->getLeagueForm($league);

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/update", name="league_update")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:League:edit.html.twig")
     */
    public function updateAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You cannot view this league');
            return $this->redirect('/');
        } elseif (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->setFlash('warning', 'You do not have permissions to edit this league');

            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $league->getId(),
            )));
        }

        $form = $this->getLeagueForm($league);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager')->flush();

            return $this->redirect($this->generateUrl('league_edit', array(
                'leagueId' => $leagueId,
            )));
        }

        $this->get('session')->setFlash('error', 'Error editing the league');

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/picks", name="league_picks")
     * @Secure(roles="ROLE_USER")
     */
    public function picksAction($leagueId)
    {
        $user = $this->getUser();
        $league = $this->findLeague($leagueId);

        $pickSet = $user->getPicksetForLeague($league);
        if ($pickSet) {
            return $this->redirect($this->generateUrl('pickset_edit', array(
                'id' => $pickSet->getId(),
                'leagueId' => $leagueId,
            )));
        }

        return $this->redirect($this->generateUrl('pickset_new', array(
            'leagueId' => $leagueId,
        )));
    }

    private function getLeagueForm(League $league)
    {
        return $this->createForm(new LeagueFormType(), $league);
    }

    private function addUserToLeague(League $league, User $user)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user->addLeague($league);

        $em->persist($league);
        $em->persist($user);
        $em->flush();
    }

    private function canUserEditLeague(User $user, League $league)
    {
        return $this->get('security.context')->isGranted('ROLE_ADMIN') || $league->userIsCommissioner($user);
    }

    private function findLeague($id)
    {
        $league = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:League')
            ->find($id);

        if (!$league) {
            throw new NotFoundHttpException(sprintf('There was no league with id = %s', $id));
        }

        return $league;
    }
}
