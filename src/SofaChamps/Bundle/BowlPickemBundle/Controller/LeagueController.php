<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Invite;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueCommissionersFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueNoteFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueLockFormType;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("{season}/league")
 */
class LeagueController extends BaseController
{
    /**
     * @Route("/{leagueId}/home", name="league_home")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function homeAction(League $league, $season)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        if (!$pickSet) {
            $this->addMessage('warning', 'You do not have a pick set for this league');
            return $this->redirect($this->router->generateUrl('league_assoc', array(
                'leagueId' => $league->getId(),
            )));
        }

        $users = $this->getRepository('SofaChampsBowlPickemBundle:League')->getUsersAndPoints($league);
        $projectedBestFinish = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedBestFinish($pickSet, $league);
        $projectedFinishStats = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedFinishStats($pickSet, $league);
        list($rank, $sortedUsers) = $this->get('sofachamps.bp.user_sorter')->sortUsersByPoints($users, $user, $league);
        $importantGames = $this->getRepository('SofaChampsBowlPickemBundle:Game')->gamesByImportanceForLeague($league, 5);

        // Only show the top 10 users
        $sortedUsers = array_slice($sortedUsers, 0, 10);

        return array(
            'league' => $league,
            'season' => $season,
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
    public function publicAction($season)
    {
        $leagues = $this->getRepository('SofaChampsBowlPickemBundle:League')
            ->findAllPublic($season);

        return array(
            'leagues' => $leagues,
            'season' => $season,
        );
    }

    /**
     * @Route("/find", name="league_find")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function findAction($season)
    {
        $form = $this->createFormBuilder()
            ->add('id', 'integer')
            ->add('password', 'password', array(
                'required' => false,
            ))
            ->getForm();

        $leagues = $this->getRepository('SofaChampsBowlPickemBundle:League')
            ->findAllPublic($season);

        return array(
            'leagues' => $leagues,
            'form' => $form->createView(),
            'season' => $season,
        );
    }

    /**
     * @Route("/{leagueId}/group-picks", name="league_group_picks")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW_PICKS")
     * @Template("SofaChampsBowlPickemBundle:League:picks.html.twig")
     */
    public function groupPicksAction(League $league, $season)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        $em = $this->getEntityManager();
        $games = $em->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate('ASC');
        $users = $em->getRepository('SofaChampsCoreBundle:User')->getUsersAndPicksetsForLeague($league, $season);

        list($rank, $sortedUsers) = $this->getUserSorter()->sortUsersByPoints($users, $user, $league);

        $curUser = $this->getUser();

        return array(
            'games' => $games,
            'league' => $league,
            'season' => $season,
            'users' => $sortedUsers,
            'pickSet' => $pickSet,
            'curUser' => $curUser,
        );
    }

    /**
     * @Route("/join", name="league_join")
     * @Method({"POST"})
     */
    public function joinAction($season)
    {
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'You cannot join a league after picks lock');
            return $this->redirect('/');
        }

        $request = $this->getRequest()->request->get('form');
        $leagueId = $request['id'];

        if (!is_numeric($leagueId)) {
            $this->addMessage('warning', 'When searching for a league, use the League Id (ex 12) not the name');
            return $this->redirect($this->generateUrl('league_find', array(
                'season' => $season,
            )));
        }

        $league = $this->findLeague($leagueId);
        $user = $this->getUser();
        if (!$user) {
            // Store the league requested into the session
            if (!$league->isPublic() && $league->getPassword() !== $request['password']) {
                $this->addMessage('error', 'The password was not correct');

                return $this->redirect($this->generateUrl('league_prejoin', array(
                    'leagueId' => $league->getId(),
                )));
            }
            $this->getRequest()->getSession()->set('auto_league_assoc', $league->getId());
            throw new AccessDeniedException('Must be logged in to join league');
        }
        $pickSets = $user->getPickSets();

        if (count($pickSets) === 0) {
            $this->addMessage('info', 'You cannot join a league without first creating a pickset.');
            return $this->redirect($this->generateUrl('pickset_new', array(
                'season' => $season,
            )));
        }

        if (!$league->isPublic()) {
            if ($request['password'] !== $league->getPassword()) {
                $this->addMessage('error', 'The password was not correct');
                return $this->redirect($this->generateUrl('league_prejoin', array(
                    'season' => $season,
                    'leagueId' => $league->getId(),
                )));
            }
        }

        if ($league->isUserInLeague($user)) {
            $this->addMessage('info', 'You are already in this league');
            return $this->redirect($this->generateUrl('league_home', array(
                'season' => $season,
                'leagueId' => $league->getId(),
            )));
        } elseif ($league->isLocked()) {
            $this->addMessage('error', 'This league has been locked by the commissioner');
            return $this->redirect($this->generateUrl('league_find', array(
                'season' => $season,
            )));
        } else {
            $this->addMessage('success', sprintf('Welcome to %s', $league->getName()));

            $this->addUserToLeague($league, $user);

            // If the user has one pickset, then auto-assign the pickset to that league
            if (count($pickSets) === 1) {
                $pickSets[0]->addLeague($league);
            } else {
                $this->getEntityManager()->flush();

                // Go to an intermediate page for assiging pickset to this league
                return $this->redirect($this->generateUrl('league_assoc', array(
                    'season' => $season,
                    'leagueId' => $league->getId(),
                )));
            }
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('league_home', array(
                'season' => $season,
                'leagueId' => $league->getId(),
            )));
        }
    }

    /**
     * @Route("/{leagueId}/join", name="league_prejoin")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @Method({"GET"})
     * @Template
     */
    public function prejoinAction(League $league, $season)
    {
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'You cannot join a league after picks lock');
        }

        $user = $this->getUser();

        if ($user && $league->isUserInLeague($user)) {
            $this->addMessage('info', 'You are already in this league');
            return $this->redirect($this->generateUrl('league_home', array(
                'season' => $season,
                'leagueId' => $league->getId(),
            )));
        }

        $form = $this->createFormBuilder()
            ->add('id', 'hidden')
            ->add('password', 'text', array(
                'required' => false,
            ))
            ->setData(array(
                'id' => $league->getId(),
            ));

        return array(
            'league' => $league,
            'form' => $form->getForm()->createView(),
        );
    }

    /**
     * @Route("/{leagueId}/assoc", name="league_assoc")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @Template
     */
    public function assocAction(League $league, $season)
    {
        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            if ($this->picksLocked()) {
                $this->addMessage('warning', 'You cannot change league assignments after picks lock');
            } else {
                $pickSet = $this->findPickSet($request->request->get('pickset'));
                $pickSet->addLeague($league);
                $this->getEntityManager()->flush();

                $this->addMessage('success', sprintf('Welcome to %s', $league->getName()));
                return $this->redirect($this->generateUrl('league_home', array(
                    'season' => $season,
                    'leagueId' => $league->getId(),
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
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function membersAction(League $league, $season)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        $members = $this->getRepository('SofaChampsCoreBundle:User')
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
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function statsAction(League $league, $season)
    {
        $user = $this->getUser();

        $pickSet = $league->getPicksetForUser($user);
        $games = $this->getRepository('SofaChampsBowlPickemBundle:Game')->userGamesByImportance($league, $pickSet);
        $projectedFinishStats = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedFinishStats($pickSet, $league);

        return array(
            'league' => $league,
            'pickSet' => $pickSet,
            'games' => $games,
            'projectedFinishStats' => $projectedFinishStats,
        );
    }

    /**
     * @Route("/{leagueId}/member-remove", name="league_member_remove_list")
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:remove.html.twig")
     */
    public function memberRemoveListAction(League $league, $season)
    {
        $members = $this->getEntityManager()->getRepository('SofaChampsCoreBundle:User')->findUsersInLeague($league);

        return array(
            'league' => $league,
            'season' => $season,
            'members' => $members,
        );
    }

    /**
     * @Route("/{leagueId}/{userId}/member-remove", name="league_member_remove")
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @ParamConverter("user", class="SofaChampsCoreBundle:User", options={"id" = "userId"})
     * @SecureParam(name="league", permissions="MANAGE")
     */
    public function memberRemoveAction(League $league, User $user, $season)
    {
        $this->getLeagueManager()->removeUserFromLeague($league, $user);
        $this->getEntityManager()->flush();
        $this->addMessage('info', 'User Removed');

        return $this->redirect($this->generateUrl('league_member_remove_list', array(
            'season' => $season,
            'leagueId' => $league->getId(),
        )));
    }

    /**
     * @Route("/{leagueId}/leaderboard", name="league_leaderboard")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function leaderboardAction(League $league, $season)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        $users = $this->getRepository('SofaChampsBowlPickemBundle:League')->getUsersAndPoints($league);
        list($rank, $sortedUsers) = $this->getUserSorter()->sortUsersByPoints($users, $user, $league);

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
    public function newAction($season)
    {
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'You cannot create a league after picks lock');
            return $this->redirect('/');
        }

        $league = new League();
        $league->addUser($this->getUser());
        $league->addCommissioner($this->getUser());
        $form = $this->getLeagueForm($league);

        return array(
            'form' => $form->createView(),
            'season' => $season,
        );
    }

    /**
     * @Route("/create", name="league_create")
     * @Method({"POST"})
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function createAction($season)
    {
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'You cannot create a league after picks lock');
            return $this->redirect('/');
        }

        $league = new League();
        $league->setSeason($season);
        $form = $this->getLeagueForm($league);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $user = $this->getUser();
            $pickSets = $user->getPickSets();

            $league = $form->getData();
            $league->addUser($user);

            $league->addCommissioner($this->getUser());
            if (count($pickSets) === 1) {
                $pickSets[0]->addLeague($league);
            }
            $em = $this->getEntityManager();
            $em->persist($league);
            $em->flush();
        } else {
            return array(
                'form' => $form->createView(),
                'season' => $season,
            );
        }

        return $this->redirect($this->generateUrl('league_home', array(
            'season' => $season,
            'leagueId' => $league->getId(),
        )));
    }

    /**
     * @Route("/{leagueId}/edit", name="league_edit")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template
     */
    public function editAction(League $league)
    {
        $form = $this->getLeagueForm($league);

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/update", name="league_update")
     * @Method({"POST"})
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:edit.html.twig")
     */
    public function updateAction(League $league)
    {
        $form = $this->getLeagueForm($league);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('league_edit', array(
                'leagueId' => $league->getId(),
            )));
        }

        $this->addMessage('error', 'Error editing the league');

        return array(
            'form' => $form->createView(),
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/invite", name="league_invite")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function inviteAction(League $league)
    {
        $user = $this->getUser();

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $emails = array_filter(array_map('trim', explode(',', $request->get('emails'))), function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            $subjectLine = sprintf('Invitation to join SofaChamps Bowl Pickem Challenge - League: %s', $league->getName());

            $fromName = trim(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));

            $this->get('sofachamps.email.sender')->sendToEmails($emails, 'League:invite', $subjectLine, array(
                'user' => $user,
                'league' => $league,
                'from' => array($user->getEmail() => $fromName ?: $user->getUsername()),
            ));

            $em = $this->getEntityManager();
            foreach ($emails as $email) {
                $invite = new Invite($user, $email);
                $em->persist($invite);
            }
            $em->flush();

            $this->addMessage('note', sprintf('Your invitation(s) were sent to %d people', count($emails)));
        }

        return array(
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/lock", name="league_lock")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:lock.html.twig")
     */
    public function lockAction(League $league)
    {
        $form = $this->createForm(new LeagueLockFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();
            } else {
                $this->addMessage('error', 'Error locking the league');
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
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:commissioners.html.twig")
     */
    public function commissionersAction(League $league, $season)
    {
        $form = $this->createForm(new LeagueCommissionersFormType(), $league, array(
            'league' => $league,
        ));

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if (count($league->getCommissioners()) === 0) {
                $this->addMessage('warning', 'What cha smokin?  Every league needs a commish...');
            } else {
                if ($form->isValid()) {
                    $this->getEntityManager()->flush();
                } else {
                    $this->addMessage('error', 'Error updating the commissioners for the league');
                }
            }
        }

        return array(
            'form' => $form->createView(),
            'season' => $season,
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/potential-winners", name="league_potential_winners")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:potential-winners.html.twig")
     */
    public function potentialWinnersAction(League $league)
    {
        $users = $this->getRepository('SofaChampsCoreBundle:User')
            ->findPotentialWinersInLeague($league);

        $emailList = array_map(function($user) {
            return $user['email'];
        }, $users);

        return array(
            'league' => $league,
            'users' => $users,
            'emailList' => $emailList,
        );
    }

    /**
     * @Route("/{leagueId}/note", name="league_note")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:note.html.twig")
     */
    public function noteAction(League $league)
    {
        $form = $this->createForm(new LeagueNoteFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();
            } else {
                $this->addMessage('error', 'Error updating the note for the league');
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
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:blast.html.twig")
     */
    public function blastAction(League $league)
    {
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

            $this->get('sofachamps.email.sender')->sendToEmails($emails, 'League:league-blast', $subjectLine, array(
                'user' => $user,
                'league' => $league,
                'message' => $request->get('message'),
                'from' => array($user->getEmail() => $fromName ?: $user->getUsername()),
            ));

            $this->addMessage('note', 'League message sent');
        }

        return array(
            'league' => $league,
        );
    }

    /**
     * @Route("/{leagueId}/no-picks", name="league_nopicks")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:nopicks.html.twig")
     */
    public function nopicksAction(League $league, $season)
    {
        $users = $this->getRepository('SofaChampsCoreBundle:User')->findUsersInLeagueWithIncompletePicksets($league);
        $emailList = array_map(function($user) {
            return $user->getEmail();
        }, $users);

        return array(
            'users' => $users,
            'season' => $season,
            'league' => $league,
            'emailList' => $emailList,
        );
    }

    /**
     * @Route("/{leagueId}/settings", name="league_settings")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template
     */
    public function settingsAction(League $league)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

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
            $em = $this->getEntityManager();

            $league = $this->findLeague($request->request->get('leagueId'));
            $pickSet = $league->getPicksetForUser($user);
            $league->removePickSet($pickSet);
            $user->removeLeague($league);
            $em->persist($user);
            $em->flush();

            $this->addMessage('warning', sprintf('You are no longer in league "%s"', $league->getName()));

            // Check to see if any leagues do not have a pickset
            foreach ($user->getLeagues() as $league) {
                if (!$league->getPicksetForUser($user)) {
                    $this->addMessage('warning', 'One or more leagues do not have a pickSet');
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
