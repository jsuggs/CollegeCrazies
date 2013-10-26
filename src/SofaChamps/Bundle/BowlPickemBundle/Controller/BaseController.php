<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\CoreBundle\Controller\CoreController;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseController extends CoreController
{
    protected function findLeague($id)
    {
        $league = $this->getRepository('SofaChampsBowlPickemBundle:League')->find((int) $id);

        if (!$league) {
            throw new NotFoundHttpException(sprintf('There was no league with id = %s', $id));
        }

        return $league;
    }

    protected function findPickSet($id, $loadPicks = false, $sort = 'p.confidence DESC')
    {
        if ($loadPicks) {
            $pickSet = $this->get('doctrine.orm.default_entity_manager')
                ->getRepository('SofaChampsBowlPickemBundle:PickSet')
                ->findPickSet($id, $sort);
        } else {
            $pickSet = $this
                ->get('doctrine.orm.default_entity_manager')
                ->getRepository('SofaChampsBowlPickemBundle:PickSet')
                ->find($id);
        }

        if (!$pickSet) {
            throw new NotFoundHttpException(sprintf('There was no pickSet with id = %s', $id));
        }

        return $pickSet;
    }

    protected function findGame($gameId)
    {
        $game = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('SofaChamps\Bundle\BowlPickemBundle\Entity\Game')
            ->find($gameId);

        if (!$game) {
            throw new NotFoundHttpException(sprintf('There was no game with id = %s', $gameId));
        }

        return $game;
    }

    protected function findPredictionSet($predictionSetId)
    {
        $predictionSet = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:PredictionSet')
            ->find($predictionSetId);

        if (!$predictionSet) {
            throw new NotFoundHttpException(sprintf('There was no prediction set with id = %s', $predictionSetId));
        }

        return $predictionSet;
    }

    protected function addUserToLeague(League $league, User $user)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
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
        return $this->get('sofachamps.bp.picks_locked_manager')->arePickLocked();
    }
}
