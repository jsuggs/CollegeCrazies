<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueCommissionersFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueNoteFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueLockFormType;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Invite;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/league")
 */
class LeagueController extends BaseController
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
            $this->get('session')->getFlashBag()->set('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        if (!$pickSet) {
            $this->get('session')->getFlashBag()->set('warning', 'You do not have a pick set for this league');
            return $this->redirect($this->router->generateUrl('league_assoc', array(
                'leagueId' => $leagueId,
            )));
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('SofaChampsBowlPickemBundle:League')->getUsersAndPoints($league);
        $projectedBestFinish = $em->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedBestFinish($pickSet, $league);
        $projectedFinishStats = $em->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedFinishStats($pickSet, $league);
        list($rank, $sortedUsers) = $this->get('user.sorter')->sortUsersByPoints($users, $user, $league);
        $importantGames = $em->getRepository('SofaChampsBowlPickemBundle:Game')->gamesByImportanceForLeague($league, 5);

        // Only show the top 10 users
        $sortedUsers = array_slice($sortedUsers, 0, 10);

        return array(
            'league' => $league,
            'users' => $sortedUsers,
            'pickSet' => $pickSet,
            'rank' => $rank,
            'projectedBestFinish' => $projectedBestFinish,
            'projectedFinishStats' => $projectedFinishStats,
            'importantGames' => $importantGames,
        );
    }

    /**
     * @Route("/public", name="league_public")
     * @Template
     */
    public function publicAction()
    {
        $leagues = $this->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:League')
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
            ->add('id', 'integer')
            ->add('password', 'password', array(
                'required' => false,
            ))
            ->getForm();

        $leagues = $this->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:League')
            ->findAllPublic();

        return array(
            'leagues' => $leagues,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{leagueId}/group-picks", name="league_group_picks")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:League:picks.html.twig")
     */
    public function groupPicksAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        if (!$this->picksLocked()) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot view the group picks until the league locks');

            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $leagueId,
            )));
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->createQuery('SELECT g FROM SofaChampsBowlPickemBundle:Game g ORDER BY g.gameDate')->getResult();

        $query = $em->createQuery('SELECT u, p from SofaChampsBowlPickemBundle:User u
            JOIN u.pickSets p
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = :leagueId
            ORDER BY pg.id');
        $users = $query->setParameter('leagueId', $leagueId)->getResult();

        list($rank, $sortedUsers) = $this->get('user.sorter')->sortUsersByPoints($users, $user, $league);

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
     * @Method({"POST"})
     */
    public function joinAction()
    {
        if ($this->picksLocked()) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot join a league after picks lock');
            return $this->redirect('/');
        }

        $request = $this->getRequest()->request->get('form');
        $leagueId = $request['id'];

        if (!is_numeric($leagueId)) {
            $this->get('session')->getFlashBag()->set('warning', 'When searching for a league, use the League Id (ex 12) not the name');
            return $this->redirect($this->generateUrl('league_find'));
        }

        $league = $this->findLeague($leagueId);
        $user = $this->getUser();
        if (!$user) {
            // Store the league requested into the session
            if (!$league->isPublic() && $league->getPassword() !== $request['password']) {
                $this->get('session')->getFlashBag()->set('error', 'The password was not correct');

                return $this->redirect($this->generateUrl('league_prejoin', array(
                    'leagueId' => $league->getId(),
                )));
            }
            $this->getRequest()->getSession()->set('auto_league_assoc', $league->getId());
            throw new AccessDeniedException('Must be logged in to join league');
        }
        $pickSets = $user->getPickSets();

        if (count($pickSets) === 0) {
            $this->get('session')->getFlashBag()->set('info', 'You cannot join a league without first creating a pickset.');
            return $this->redirect($this->generateUrl('pickset_new'));
        }

        if (!$league->isPublic()) {
            if ($request['password'] !== $league->getPassword()) {
                $this->get('session')->getFlashBag()->set('error', 'The password was not correct');
                return $this->redirect($this->generateUrl('league_prejoin', array(
                    'leagueId' => $league->getId(),
                )));
            }
        }

        if ($league->isUserInLeague($user)) {
            $this->get('session')->getFlashBag()->set('info', 'You are already in this league');
            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $league->getId(),
            )));
        } elseif ($league->isLocked()) {
            $this->get('session')->getFlashBag()->set('error', 'This league has been locked by the commissioner');
            return $this->redirect($this->generateUrl('league_find'));
        } else {
            $this->get('session')->getFlashBag()->set('success', sprintf('Welcome to %s', $league->getName()));

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

            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $league->getId(),
            )));
        }
    }

    /**
     * @Route("/{leagueId}/join", name="league_prejoin")
     * @Method({"GET"})
     * @Template
     */
    public function prejoinAction($leagueId)
    {
        if ($this->picksLocked()) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot join a league after picks lock');
        }

        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if ($user && $league->isUserInLeague($user)) {
            $this->get('session')->getFlashBag()->set('info', 'You are already in this league');
            return $this->redirect($this->generateUrl('league_home', array(
                'leagueId' => $leagueId,
            )));
        }

        $form = $this->createFormBuilder()
            ->add('id', 'hidden')
            ->add('password', 'text', array(
                'required' => false,
            ))
            ->setData(array(
                'id' => $leagueId,
            ));

        return array(
            'league' => $league,
            'form' => $form->getForm()->createView(),
        );
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
            if ($this->picksLocked()) {
                $this->get('session')->getFlashBag()->set('warning', 'You cannot change league assignments after picks lock');
            } else {
                $pickSet = $this->findPickSet($request->request->get('pickset'));
                $pickSet->addLeague($league);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->getFlashBag()->set('success', sprintf('Welcome to %s', $league->getName()));
                return $this->redirect($this->generateUrl('league_home', array(
                    'leagueId' => $leagueId,
                )));
            }
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
            $this->get('session')->getFlashBag()->set('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        $members = $this->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:User')
            ->findUsersInLeague($league);

        return array(
            'league' => $league,
            'members' => $members,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/{leagueId}/stats", name="league_stats")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function statsAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$league->userCanView($user)) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $pickSet = $league->getPicksetForUser($user);
        $games = $em->getRepository('SofaChampsBowlPickemBundle:Game')->userGamesByImportance($league, $pickSet);
        $projectedFinishStats = $em->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedFinishStats($pickSet, $league);

        return array(
            'league' => $league,
            'pickSet' => $pickSet,
            'games' => $games,
            'projectedFinishStats' => $projectedFinishStats,
        );
    }

    /**
     * @Route("/{leagueId}/members-remove", name="league_member_remove")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:League:remove.html.twig")
     */
    public function memberRemoveAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot edit this league');
            return $this->redirect('/');
        }

        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');
        if ($request->getMethod() === 'POST') {
            $userId = $request->request->get('userId');
            $em->getConnection()->executeUpdate('DELETE FROM pickset_leagues WHERE league_id = ? AND pickset_id IN (SELECT id FROM picksets WHERE user_id = ?)', array($leagueId, $userId));
            $em->getConnection()->executeUpdate('DELETE FROM user_league WHERE league_id = ? AND user_id = ?', array($leagueId, $userId));
            $em->flush();
            $this->get('session')->getFlashBag()->set('info', 'User Removed');
        }

        $members = $em->getRepository('SofaChampsBowlPickemBundle:User')->findUsersInLeague($league);

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
            $this->get('session')->getFlashBag()->set('warning', 'You must be in the league to view the leaderboard');
            return $this->redirect($this->generateUrl('league_prejoin', array(
                'leagueId' => $leagueId,
            )));
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('SofaChampsBowlPickemBundle:League')->getUsersAndPoints($league);
        list($rank, $sortedUsers) = $this->get('user.sorter')->sortUsersByPoints($users, $user, $league);

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
        if ($this->picksLocked()) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot create a league after picks lock');
            return $this->redirect('/');
        }

        $league = new League();
        $league->addUser($this->getUser());
        $league->addCommissioner($this->getUser());
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
        if ($this->picksLocked()) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot create a league after picks lock');
            return $this->redirect('/');
        }

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
            $this->get('session')->getFlashBag()->set('warning', 'You cannot view this league');
            return $this->redirect('/');
        } elseif (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You do not have permissions to edit this league');

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
     * @Template("SofaChampsBowlPickemBundle:League:edit.html.twig")
     */
    public function updateAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$league->userCanView($user)) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot view this league');
            return $this->redirect('/');
        } elseif (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You do not have permissions to edit this league');

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

        $this->get('session')->getFlashBag()->set('error', 'Error editing the league');

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
            $this->get('session')->getFlashBag()->set('warning', 'You do not have permissions to send invitations for this league');

            return $this->redirect('/');
        }

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $emails = array_filter(array_map('trim', explode(',', $request->get('emails'))), function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            $subjectLine = sprintf('Invitation to join SofaChamps Bowl Pickem Challenge - League: %s', $league->getName());

            $fromName = trim(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));

            $this->get('email.sender')->sendToEmails($emails, 'League:invite', $subjectLine, array(
                'user' => $user,
                'league' => $league,
                'from' => array($user->getEmail() => $fromName ?: $user->getUsername()),
            ));

            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($emails as $email) {
                $invite = new Invite($user, $email);
                $em->persist($invite);
            }
            $em->flush();

            $this->get('session')->getFlashBag()->set('note', sprintf('Your invitation(s) were sent to %d people', count($emails)));
        }

        return array(
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/lock", name="league_lock")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:League:lock.html.twig")
     */
    public function lockAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You do not have permissions to lock this league');

            return $this->redirect('/');
        }

        $form = $this->createForm(new LeagueLockFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->flush();
            } else {
                $this->get('session')->getFlashBag()->set('error', 'Error locking the league');
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
     * @Template("SofaChampsBowlPickemBundle:League:commissioners.html.twig")
     */
    public function commissionersAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You do not have permissions to edit this league');

            return $this->redirect('/');
        }

        $form = $this->createForm(new LeagueCommissionersFormType(), $league, array(
            'league' => $league,
        ));

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if (count($league->getCommissioners()) === 0) {
                $this->get('session')->getFlashBag()->set('warning', 'What cha smokin?  Every league needs a commish...');
            } else {
                if ($form->isValid()) {
                    $this->get('doctrine.orm.entity_manager')->flush();
                } else {
                    $this->get('session')->getFlashBag()->set('error', 'Error updating the commissioners for the league');
                }
            }
        }

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/potential-winners", name="league_potential_winners")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:League:potential-winners.html.twig")
     */
    public function potentailWinnersAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'Only commissioners can view this feature');

            return $this->redirect('/');
        }

        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:User')
            ->findPotentialWinersInLeague($league);

        $emailList = array_map(function($user) { return $user['email']; }, $users);

        return array(
            'league' => $league,
            'users' => $users,
            'emailList' => $emailList,
        );
    }

    /**
     * @Route("/{leagueId}/note", name="league_note")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:League:note.html.twig")
     */
    public function noteAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You do not have permissions to edit the note for this league');

            return $this->redirect('/');
        }

        $form = $this->createForm(new LeagueNoteFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->flush();
            } else {
                $this->get('session')->getFlashBag()->set('error', 'Error updating the note for the league');
            }
        }

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/blast", name="league_blast")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:League:blast.html.twig")
     */
    public function blastAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You cannot send an email for this league');

            return $this->redirect('/');
        }

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $emails = array_map(function (User $user) {
                return $user->getEmail();
            }, iterator_to_array($league->getUsers()));

            $emails = array_filter($emails, function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            $subjectLine = sprintf('League: "%s" - Commissioner Note - SofaChamps', $league->getName());

            $fromName = trim(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));

            $this->get('email.sender')->sendToEmails($emails, 'League:league-blast', $subjectLine, array(
                'user' => $user,
                'league' => $league,
                'message' => $request->get('message'),
                'from' => array($user->getEmail() => $fromName ?: $user->getUsername()),
            ));

            $this->get('session')->getFlashBag()->set('note', 'League message sent');
        }

        return array(
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/no-picks", name="league_nopicks")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:League:nopicks.html.twig")
     */
    public function nopicksAction($leagueId)
    {
        $league = $this->findLeague($leagueId);
        $user = $this->getUser();

        if (!$this->canUserEditLeague($user, $league)) {
            $this->get('session')->getFlashBag()->set('warning', 'You do not have permissions to edit the note for this league');

            return $this->redirect('/');
        }

        $users = $this->get('doctrine.orm.entity_manager')->getRepository('SofaChampsBowlPickemBundle:User')->findUsersInLeagueWithIncompletePicksets($league);
        $emailList = array_map(function($user) { return $user->getEmail(); }, $users);

        return array(
            'users' => $users,
            'league' => $league,
            'emailList' => $emailList,
        );
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
            $this->get('session')->getFlashBag()->set('warning', 'You cannot view this league');
            return $this->redirect('/');
        }

        return array(
            'league' => $league,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/leave", name="league_leave")
     * @Template
     */
    public function leaveAction()
    {
        $user = $this->getUser();

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $em = $this->get('doctrine.orm.entity_manager');

            $league = $this->findLeague($request->request->get('leagueId'));
            $pickSet = $league->getPicksetForUser($user);
            $league->removePickSet($pickSet);
            $user->removeLeague($league);
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->set('warning', sprintf('You are no longer in league "%s"', $league->getName()));

            // Check to see if any leagues do not have a pickset
            foreach ($user->getLeagues() as $league) {
                if (!$league->getPicksetForUser($user)) {
                    $this->get('session')->getFlashBag()->set('warning', 'One or more leagues do not have a pickSet');
                    return $this->redirect($this->generateUrl('league_assoc', array(
                        'leagueId' => $league->getId(),
                    )));
                }
            }
        }

        return array(
            'leagues' => $user->getLeagues(),
        );
    }

    private function getLeagueForm(League $league)
    {
        return $this->createForm(new LeagueFormType(), $league);
    }
}
