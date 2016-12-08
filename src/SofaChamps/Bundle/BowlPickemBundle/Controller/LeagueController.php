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
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueCommissionersFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueNoteFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\LeagueLogoFormType;
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
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @Template
     */
    public function homeAction(League $league, Season $season)
    {
        $user = $this->getUser();
        if (!$user || !($pickSet = $league->getPicksetForUser($user))) {
            // If the user is in the league, but doesn't have a pickset make them assoc
            if ($user) {
                if ($league->isUserInLeague($user)) {
                    $this->addMessage('warning', 'You do not have a pick set for this league');
                    return $this->redirect($this->generateUrl('league_assoc', array(
                        'season' => $season->getSeason(),
                        'leagueId' => $league->getId(),
                    )));
                }
            }

            // If the league is private redirect to the join page, but with the league info defaulted
            if (!$league->isPublic()) {
                if ($this->picksLocked($season)) {
                    $this->addMessage('danger', 'This is a private league and closed after picks lock');

                    return $this->redirect($this->generateUrl('bp_home', array(
                        'season' => $season->getSeason(),
                    )));
                } else {
                    $this->addMessage('danger', 'This is a private league.  Enter password below to join');

                    return $this->redirect($this->generateUrl('league_find', array(
                        'season' => $season->getSeason(),
                        'leagueId' => $league->getId(),
                    )));
                }
            }

            // Send to the guest version of the league
            return $this->redirect($this->generateUrl('league_guest', array(
                'season' => $season->getSeason(),
                'leagueId' => $league->getId(),
            )));
        }

        $users = $this->getRepository('SofaChampsBowlPickemBundle:League')->getUsersAndPoints($league);
        $projectedBestFinish = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedBestFinish($pickSet, $league);
        $projectedFinishStats = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedFinishStats($pickSet, $league);
        $sortedUsers = $this->getUserSorter()->sortUsersByPoints($users, $league);
        $rank = $this->getUserSorter()->getUserRank($user, $sortedUsers);
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
     * @Route("/{leagueId}/guest", name="league_guest")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function guestAction(League $league, Season $season)
    {
        $users = $this->getRepository('SofaChampsBowlPickemBundle:League')->getUsersAndPoints($league);
        $sortedUsers = $this->getUserSorter()->sortUsersByPoints($users, $league);
        $importantGames = $this->getRepository('SofaChampsBowlPickemBundle:Game')->gamesByImportanceForLeague($league, 5);

        // Only show the top 10 users
        $sortedUsers = array_slice($sortedUsers, 0, 10);

        return array(
            'league' => $league,
            'season' => $season,
            'users' => $sortedUsers,
            'importantGames' => $importantGames,
        );
    }

    /**
     * @Route("/public", name="league_public")
     * @Template
     */
    public function publicAction(Season $season)
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
     * Secure(roles="ROLE_USER")
     * @Template
     */
    public function findAction(Season $season)
    {
        $form = $this->createFormBuilder()
            ->add('id', 'integer', array(
                'data' => $this->getRequest()->query->get('leagueId'),
            ))
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
    public function groupPicksAction(League $league, Season $season)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        $em = $this->getEntityManager();
        $games = $em->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate($season, 'ASC');
        $users = $em->getRepository('SofaChampsCoreBundle:User')->getUsersAndPicksetsForLeague($league, $season);

        $sortedUsers = $this->getUserSorter()->sortUsersByPoints($users, $league);

        return array(
            'games' => $games,
            'league' => $league,
            'season' => $season,
            'users' => $sortedUsers,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/join", name="league_join")
     * @Method({"POST"})
     */
    public function joinAction(Season $season)
    {
        if ($this->picksLocked($season)) {
            $this->addMessage('warning', 'You cannot join a league after picks lock');
            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season->getSeason(),
            )));
        }

        $request = $this->getRequest()->request->get('form');
        $leagueId = $request['id'];

        if (!is_numeric($leagueId)) {
            $this->addMessage('warning', 'When searching for a league, use the League Id (ex 12) not the name');
            return $this->redirect($this->generateUrl('league_find', array(
                'season' => $season->getSeason(),
            )));
        }

        $league = $this->findLeague($leagueId);
        $user = $this->getUser();
        if (!$user) {
            // Store the league requested into the session
            if (!$league->isPublic() && $league->getPassword() !== $request['password']) {
                $this->addMessage('error', 'The password was not correct');

                return $this->redirect($this->generateUrl('league_prejoin', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            }
            $this->writeLeagueJoinCookie($league);

            throw new AccessDeniedException('Must be logged in to join league');
        }
        $pickSets = $user->getPickSetsForSeason($season);

        if (count($pickSets) === 0) {
            $this->addMessage('info', 'You cannot join a league without first creating a pickset.');
            $this->writeLeagueJoinCookie($league);

            return $this->redirect($this->generateUrl('pickset_new', array(
                'season' => $season->getSeason(),
                'leagueId' => $league->getId(),
            )));
        }

        if (!$league->isPublic()) {
            if ($request['password'] !== $league->getPassword()) {
                $this->addMessage('error', 'The password was not correct');
                return $this->redirect($this->generateUrl('league_prejoin', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            }
        }

        if ($league->isUserInLeague($user)) {
            $this->addMessage('info', 'You are already in this league');
            return $this->redirect($this->generateUrl('league_home', array(
                'season' => $season->getSeason(),
                'leagueId' => $league->getId(),
            )));
        } elseif ($league->isLocked()) {
            $this->addMessage('error', 'This league has been locked by the commissioner');
            return $this->redirect($this->generateUrl('league_find', array(
                'season' => $season->getSeason(),
            )));
        } else {
            $this->addMessage('success', sprintf('Welcome to %s', $league->getName()));

            $this->addUserToLeague($league, $user);

            // If the user has one pickset, then auto-assign the pickset to that league
            if (count($pickSets) === 1) {
                $pickSet = $pickSets->first();
                $this->getPicksetManager()->addPickSetToLeague($league, $pickSet);
            } else {
                $this->getEntityManager()->flush();

                // Go to an intermediate page for assiging pickset to this league
                $this->writeLeagueJoinCookie($league);

                return $this->redirect($this->generateUrl('league_assoc', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            }
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('league_home', array(
                'season' => $season->getSeason(),
                'leagueId' => $league->getId(),
            )));
        }
    }

    /**
     * @Route("/{leagueId}/join", name="league_prejoin")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @Method({"GET"})
     * @Template
     */
    public function prejoinAction(League $league, Season $season)
    {
        $returnToLeagueHome = false;
        $this->writeLeagueJoinCookie($league);

        if ($this->picksLocked($season)) {
            $this->addMessage('warning', 'You cannot join a league after picks lock');
            $returnToLeagueHome = true;
        }

        $user = $this->getUser();

        if ($user && $league->isUserInLeague($user)) {
            $this->addMessage('info', 'You are already in this league');
            $returnToLeagueHome = true;
        }

        if ($returnToLeagueHome) {
            return $this->redirect($this->generateUrl('league_home', array(
                'season' => $season->getSeason(),
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
            'season' => $season,
            'form' => $form->getForm()->createView(),
        );
    }

    /**
     * @Route("/{leagueId}/assoc", name="league_assoc")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @Template
     */
    public function assocAction(League $league, Season $season)
    {
        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            if ($this->picksLocked($season)) {
                $this->addMessage('warning', 'You cannot change league assignments after picks lock');
            } else {
                $pickSet = $this->findPickSet($request->request->get('pickset'));
                $pickSet->addLeague($league);
                $this->getEntityManager()->flush();

                $this->addMessage('success', sprintf('Welcome to %s', $league->getName()));
                return $this->redirect($this->generateUrl('league_home', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            }
        }

        $user = $this->getUser();
        $pickSets = $user->getPickSetsForSeason($season);

        return array(
            'league' => $league,
            'season' => $season,
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
    public function membersAction(League $league, Season $season)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        $members = $this->getRepository('SofaChampsCoreBundle:User')
            ->findUsersInLeague($league);

        return array(
            'league' => $league,
            'season' => $season,
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
    public function statsAction(League $league, Season $season)
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addMessage('danger', 'You must login to view these stats.');

            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season->getSeason(),
            )));
        }

        $pickSet = $league->getPicksetForUser($user);
        $games = $this->getRepository('SofaChampsBowlPickemBundle:Game')->userGamesByImportance($league, $pickSet);
        $projectedFinishStats = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedFinishStats($pickSet, $league);

        return array(
            'league' => $league,
            'season' => $season,
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
    public function memberRemoveListAction(League $league, Season $season)
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
    public function memberRemoveAction(League $league, User $user, Season $season)
    {
        $this->getLeagueManager()->removeUserFromLeague($league, $user);
        $this->getEntityManager()->flush();
        $this->addMessage('info', 'User Removed');

        return $this->redirect($this->generateUrl('league_member_remove_list', array(
            'season' => $season->getSeason(),
            'leagueId' => $league->getId(),
        )));
    }

    /**
     * @Route("/{leagueId}/logo", name="league_logo")
     * @Secure(roles="ROLE_USER")
     * @Method({"POST", "GET"})
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template
     */
    public function logoAction(League $league, Season $season)
    {
        $form = $this->getLogoUploadForm($league);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                $this->addMessage('success', 'Logo Updated');

                return $this->redirect($this->generateUrl('league_home', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            }
        }

        return array(
            'season' => $season,
            'league' => $league,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{leagueId}/leaderboard", name="league_leaderboard")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function leaderboardAction(League $league, Season $season)
    {
        $user = $this->getUser();
        $pickSet = $user
            ? $league->getPicksetForUser($user)
            : null;

        if (!$pickSet) {
            // If the league is private redirect to the join page, but with the league info defaulted
            if (!$league->isPublic()) {
                $this->addMessage('danger', 'This is a private league.  Enter password below to join');

                return $this->redirect($this->generateUrl('league_find', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            }
        }

        $users = $this->getRepository('SofaChampsBowlPickemBundle:League')->getUsersAndPoints($league);
        $sortedUsers = $this->getUserSorter()->sortUsersByPoints($users, $league);

        return array(
            'users' => $sortedUsers,
            'season' => $season,
            'league' => $league,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/new", name="league_new")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function newAction(Season $season)
    {
        if ($this->picksLocked($season)) {
            $this->addMessage('warning', 'You cannot create a league after picks lock');
            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season->getSeason(),
            )));
        }

        $user = $this->getUser();
        $pickSets = $user->getPickSetsForSeason($season);
        if (count($pickSets) === 0) {
            $this->addMessage('warning', 'You cannot create a league until you create a pickset');
            return $this->redirect($this->generateUrl('pickset_new', array(
                'season' => $season->getSeason(),
            )));
        }

        $league = new League();
        $league->setSeason($season);
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
    public function createAction(Season $season)
    {
        if ($this->picksLocked($season)) {
            $this->addMessage('warning', 'You cannot create a league after picks lock');
            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season->getSeason(),
            )));
        }

        $user = $this->getUser();
        $leagueManager = $this->getLeagueManager();

        $league = $leagueManager->createLeague($season);
        $leagueManager->addCommissionerToLeague($league, $user);

        $form = $this->getLeagueForm($league);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $pickSets = $user->getPickSetsForSeason($season);

            if (count($pickSets) === 1) {
                $pickSet = $pickSets->first();
                $this->getPicksetManager()->addPickSetToLeague($league, $pickSet);

                $return = $this->redirect($this->generateUrl('league_home', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            } else {
                $this->writeLeagueJoinCookie($league);

                $return = $this->redirect($this->generateUrl('league_assoc', array(
                    'season' => $season->getSeason(),
                    'leagueId' => $league->getId(),
                )));
            }

            $this->addMessage('success', 'League Created');

            $this->getEntityManager()->flush();
        } else {
            return array(
                'form' => $form->createView(),
                'season' => $season,
            );
        }

        return $return;
    }

    /**
     * @Route("/{leagueId}/edit", name="league_edit")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template
     */
    public function editAction(League $league, Season $season)
    {
        $form = $this->getLeagueForm($league);

        return array(
            'form' => $form->createView(),
            'season' => $season,
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
    public function updateAction(League $league, Season $season)
    {
        $form = $this->getLeagueForm($league);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('league_edit', array(
                'season' => $season->getSeason(),
                'leagueId' => $league->getId(),
            )));
        }

        $this->addMessage('error', 'Error editing the league');

        return array(
            'form' => $form->createView(),
            'season' => $season,
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
    public function inviteAction(League $league, Season $season)
    {
        $user = $this->getUser();

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $emails = $this->getEmailInputParser()->parseEmails($request->get('emails'));

            $subjectLine = sprintf('Invitation to join SofaChamps Bowl Pickem Challenge - League: %s', $league->getName());

            $fromName = trim(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));

            $this->getEmailSender()->sendToEmails($emails, 'League:invite', $subjectLine, array(
                'user' => $user,
                'league' => $league,
                'season' => $season->getSeason(),
                'from' => array($user->getEmail() => $fromName ?: $user->getUsername()),
            ));

            $em = $this->getEntityManager();
            foreach ($emails as $email) {
                $invite = new Invite($user, $email);
                $em->persist($invite);
            }
            $em->flush();

            $this->addMessage('info', sprintf('Your invitation(s) were sent to %d people', count($emails)));
        }

        return array(
            'league' => $league,
            'season' => $season,
        );
    }

    /**
     * @Route("/{leagueId}/lock", name="league_lock")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:lock.html.twig")
     */
    public function lockAction(League $league, Season $season)
    {
        $form = $this->createForm(new LeagueLockFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->addMessage('success', 'League Lock updated');
                $this->getEntityManager()->flush();
            } else {
                $this->addMessage('error', 'Error locking the league');
            }
        }

        return array(
            'form' => $form->createView(),
            'season' => $season,
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
    public function commissionersAction(League $league, Season $season)
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
    public function potentialWinnersAction(League $league, Season $season)
    {
        $users = $this->getRepository('SofaChampsCoreBundle:User')
            ->findPotentialWinersInLeague($league);

        $emailList = array_map(function($user) {
            return $user['email'];
        }, $users);

        return array(
            'league' => $league,
            'season' => $season,
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
    public function noteAction(League $league, Season $season)
    {
        $form = $this->createForm(new LeagueNoteFormType(), $league);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                $this->addMessage('success', 'League note updated');
            } else {
                $this->addMessage('error', 'Error updating the note for the league');
            }
        }

        return array(
            'form' => $form->createView(),
            'season' => $season,
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
    public function blastAction(League $league, Season $season)
    {
        $request = $this->getRequest();
        $form = $this->getBlastForm();

        if ($request->getMethod() === 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $eligibleUsers = $league->getUsers()->filter(function (User $user) {
                    return $user->getEmailFromCommish();
                });
                $data = $form->getData();

                $emails = array_map(function (User $user) {
                    return $user->getEmail();
                }, $eligibleUsers->toArray());

                $subjectLine = sprintf('League: "%s" - Commissioner Note - SofaChamps', $league->getName());

                $user = $this->getUser();
                $fromName = trim(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));

                $this->getEmailSender()->sendToEmails($emails, 'League:league-blast', $subjectLine, array(
                    'user' => $user,
                    'league' => $league,
                    'message' => $data['message'],
                    'from' => array($user->getEmail() => $fromName ?: $user->getUsername()),
                ));

                $this->addMessage('info', 'League message sent');
            } else {
                $this->addMessage('warning', 'There was an issue sending your message');
            }
        }

        return array(
            'form' => $form->createView(),
            'league' => $league,
            'season' => $season,
        );
    }

    /**
     * @Route("/{leagueId}/no-picks", name="league_nopicks")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @SecureParam(name="league", permissions="MANAGE")
     * @Template("SofaChampsBowlPickemBundle:League:nopicks.html.twig")
     */
    public function nopicksAction(League $league, Season $season)
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
     * @SecureParam(name="league", permissions="VIEW")
     * @Template
     */
    public function settingsAction(League $league, Season $season)
    {
        $user = $this->getUser();
        $pickSet = $league->getPicksetForUser($user);

        return array(
            'league' => $league,
            'season' => $season,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/leave", name="league_leave")
     * @Template
     */
    public function leaveAction(Season $season)
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
                        'season' => $season->getSeason(),
                        'leagueId' => $league->getId(),
                    )));
                }
            }
        }

        return array(
            'leagues' => $user->getLeaguesForSeason($season),
            'season' => $season,
        );
    }

    private function getLeagueForm(League $league)
    {
        return $this->createForm(new LeagueFormType(), $league);
    }

    private function getLogoUploadForm(League $league)
    {
        return $this->createForm(new LeagueLogoFormType(), $league);
    }

    private function getBlastForm()
    {
        return $this->createFormBuilder()
            ->add('message', 'textarea')
            ->getForm();
    }
}
