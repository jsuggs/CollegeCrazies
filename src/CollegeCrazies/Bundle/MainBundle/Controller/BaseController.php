<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;
use CollegeCrazies\Bundle\MainBundle\Listener\PicksLockedListener;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseController extends Controller
{
    protected function findLeague($id)
    {
        $league = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:League')
            ->find((int) $id);

        if (!$league) {
            throw new NotFoundHttpException(sprintf('There was no league with id = %s', $id));
        }

        return $league;
    }

    protected function findPickSet($id, $loadPicks = false)
    {
        if ($loadPicks) {
            $em = $this->get('doctrine.orm.entity_manager');
            $pickSet = $em->createQuery('SELECT ps, u, p from CollegeCraziesMainBundle:Pickset ps
                JOIN ps.user u
                JOIN ps.picks p
                WHERE ps.id = :id
                ORDER BY p.confidence desc'
            )
                ->setParameter('id', $id)
                ->getSingleResult();
        } else {
            $pickSet = $this
                ->get('doctrine.orm.entity_manager')
                ->getRepository('CollegeCraziesMainBundle:PickSet')
                ->find($id);
        }

        if (!$pickSet) {
            throw new NotFoundHttpException(sprintf('There was no pickSet with id = %s', $id));
        }

        return $pickSet;
    }

    protected function findGame($gameId)
    {
        $game = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')
            ->find($gameId);

        if (!$game) {
            throw new NotFoundHttpException(sprintf('There was no game with id = %s', $gameId));
        }

        return $game;
    }

    protected function findPredictionSet($predictionSetId)
    {
        $predictionSet = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:PredictionSet')
            ->find($predictionSetId);

        if (!$predictionSet) {
            throw new NotFoundHttpException(sprintf('There was no prediction set with id = %s', $predictionSetId));
        }

        return $predictionSet;
    }

    protected function canUserViewPickSet(User $user, PickSet $pickSet)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($pickSet->getUser() == $user) {
            return true;
        }

        return $this->picksLocked();
    }

    protected function canUserEditPickSet(User $user, PickSet $pickSet)
    {
        if ($this->picksLocked()) {
            return false;
        }

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($pickSet->getUser() == $user) {
            return true;
        }

        return false;
    }

    protected function addUserToLeague(League $league, User $user)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user->addLeague($league);

        $em->persist($league);
        $em->persist($user);
    }

    protected function canUserEditLeague(User $user, League $league)
    {
        return $this->get('security.context')->isGranted('ROLE_ADMIN') || $league->userIsCommissioner($user);
    }

    protected function picksLocked()
    {
        return $this->get('session')->get(PicksLockedListener::PICKS_LOCK_SESSION_KEY) < new \DateTime();
    }
}
