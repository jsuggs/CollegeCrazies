<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\LeagueFormType;
use CollegeCrazies\Bundle\MainBundle\Form\LeagueCommissionersFormType;
use CollegeCrazies\Bundle\MainBundle\Form\LeagueNoteFormType;
use CollegeCrazies\Bundle\MainBundle\Form\LeagueLockFormType;
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
        $pickSet = $league->getPicksetForUser($user);

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        if (!$pickSet) {
            $this->get('session')->setFlash('warning', 'You do not have a pick set for this league');
        }

        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:User')
            ->findUsersInLeague($league);

        return array(
            'league' => $league,
            'users' => $users,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/public", name="league_public")
     * @Template
     */
    public function publicAction()
    {
        $leagues = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:League')
            ->findAllPublic();

        return array(
            'leagues' => $leagues,
        );
    }

    /**
     * @Route("/find", name="league_find")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function findAction()
    {
        $form = $this->createFormBuilder()
            ->add('id', 'text')
            ->add('password', 'password', array(
                'required' => false,
            ))
            ->getForm();

        $leagues = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:League')
            ->findAllPublic();

        return array(
            'leagues' => $leagues,
            'form' => $form->createView(),
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
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        if (!$league->picksLocked()) {
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
        $sortedUsers = $userSorter->sortUsersByPoints($users, $league);

        $curUser = $this->getUser();

        return array(
            'games' => $games,
            'league' => $league,
            'users' => $sortedUsers,
            'pickSet' => $pickSet,
            'curUser' => $curUser,
        );
    }

    /**
     * @Route("/join", name="league_join")
     * @Secure(roles="ROLE_USER")
     */
    public function joinAction()
    {
        $request = $this->getRequest()->request->get('form');
        $league = $this->findLeague($request['id']);
        $user = $this->getUser();
        $pickSets = $user->getPickSets();

        if (count($pickSets) === 0) {
            $this->get('session')->setFlash('info', 'You cannot join a league without first creating a pickset.');
            return $this->redirect($this->generateUrl('pickset_new'));
        }

        $allowInLeague = true;
        if (!$league->isPublic()) {
            if ($request['password'] !== $league->getPassword()) {
                $this->get('session')->setFlash('error', 'The password was not correct');
                $allowInLeague = false;
            }
        }

        if ($league->isUserInLeague($user)) {
            $this->get('session')->setFlash('info', 'You are already in this league');
            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $league->getId(),
            )));
        } elseif ($league->isLocked()) {
            $this->get('session')->setFlash('error', 'This league has been locked by the commissioner');
        } elseif ($allowInLeague) {
            $this->addUserToLeague($league, $user);

            // If the user has one pickset, then auto-assign the pickset to that league
            if (count($pickSets) === 1) {
                $pickSets[0]->addLeague($league);
            } else {
                $this->get('doctrine.orm.entity_manager')->flush();

                // Go to an intermediate page for assiging pickset to this league
                return $this->redirect($this->generateUrl('league_assoc', array(
                    'leagueId' => $league->getId(),
                )));
            }
            $this->get('doctrine.orm.entity_manager')->flush();
            $this->get('session')->setFlash('success', sprintf('Welcome to %s', $league->getName()));

            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $league->getId(),
            )));
        }

        return $this->redirect($this->generateUrl('league_find'));
    }

    /**
     * @Route("/{leagueId}/assoc", name="league_assoc")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function assocAction($leagueId)
    {
        $league = $this->findLeague($leagueId);

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $pickSet = $this->findPickSet($request->request->get('pickset'));
            $pickSet->addLeague($league);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->get('session')->setFlash('success', sprintf('Welcome to %s', $league->getName()));
            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $leagueId,
            )));
        }

        $user = $this->getUser();
        $pickSets = $user->getPickSets();

        return array(
            'league' => $league,
            'pickSets' => $pickSets,
        );
    }

    /**
     * @Route("/{leagueId}/members", name="league_members")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function membersAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        $members = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:User')
            ->findUsersInLeague($league);

        return array(
            'league' => $league,
            'members' => $members,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/{leagueId}/members-remove", name="league_member_remove")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:League:remove.html.twig")
     */
    public function memberRemoveAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->setFlash('warning', 'You cannot edit this league');
            return $this->redirect('/');
        }

        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');
        if ($request->getMethod() === 'POST') {
            $userId = $request->request->get('userId');
            $em->getConnection()->executeUpdate('DELETE FROM pickset_leagues WHERE league_id = ? AND pickset_id IN (SELECT id FROM picksets WHERE user_id = ?)', array($leagueId, $userId));
            $em->getConnection()->executeUpdate('DELETE FROM user_league WHERE league_id = ? AND user_id = ?', array($leagueId, $userId));
            $em->flush();
            $this->get('session')->setFlash('info', 'User Removed');
        }

        $members = $em->getRepository('CollegeCraziesMainBundle:User')->findUsersInLeague($league);

        return array(
            'league' => $league,
            'members' => $members,
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
        $pickSet = $league->getPicksetForUser($user);

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

        $sortedUsers = $this->get('user.sorter')->sortUsersByPoints($users, $league);

        return array(
            'users' => $sortedUsers,
            'league' => $league,
            'pickSet' => $pickSet,
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
        $league->addUser($this->getUser());
        $league->addCommissioner($this->getUser());
        $league->setPublic(true);
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
            $user = $this->getUser();
            $pickSets = $user->getPickSets();

            $league = $form->getData();
            $league->addUser($user);

            $league->addCommissioner($this->getUser());
            if (count($pickSets) === 1) {
                $pickSets[0]->addLeague($league);
            }
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
     * @Route("/{leagueId}/invite", name="league_invite")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function inviteAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You do not have permissions to view this league');

            return $this->redirect('/');
        }

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $emails = array_filter(array_map('trim', explode(',', $request->get('emails'))), function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            $subjectLine = sprintf('Invitation to join CollegeCrazies - League: %s', $league->getName());

            $this->get('email.sender')->sendToEmails($emails, 'League:invite', $subjectLine, array(
                'league' => $league,
            ));

            $this->get('session')->setFlash('note', sprintf('Your invitation(s) were sent to %d people', count($emails)));
        }

        return array(
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/lock", name="league_lock")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:League:lock.html.twig")
     */
    public function lockAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->setFlash('warning', 'You do not have permissions to lock this league');

            return $this->redirect('/');
        }

        $form = $this->createForm(new LeagueLockFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->flush();
            } else {
                $this->get('session')->setFlash('error', 'Error locking the league');
            }
        }

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/commissioners", name="league_commissioners")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:League:commissioners.html.twig")
     */
    public function commissionersAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->setFlash('warning', 'You do not have permissions to edit this league');

            return $this->redirect('/');
        }

        $form = $this->createForm(new LeagueCommissionersFormType(), $league, array(
            'league' => $league,
        ));

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->flush();
            } else {
                $this->get('session')->setFlash('error', 'Error updating the commissioners for the league');
            }
        }

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/note", name="league_note")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:League:note.html.twig")
     */
    public function noteAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->setFlash('warning', 'You do not have permissions to edit the note for this league');

            return $this->redirect('/');
        }

        $form = $this->createForm(new LeagueNoteFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->flush();
            } else {
                $this->get('session')->setFlash('error', 'Error updating the note for the league');
            }
        }

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

        $pickSet = $user->getPicksetForUser($user);
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

    /**
     * @Route("/{leagueId}/settings", name="league_settings")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function settingsAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        if (!$league->userCanView($user)) {
            $this->get('session')->setFlash('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        return array(
            'league' => $league,
            'pickSet' => $pickSet,
        );
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

    private function findPickSet($id)
    {
        $pickSet = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:PickSet')
            ->find($id);

        if (!$pickSet) {
            throw new NotFoundHttpException(sprintf('There was no pickSet with id = %s', $id));
        }

        return $pickSet;
    }
}
