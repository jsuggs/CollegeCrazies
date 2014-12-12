<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
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
            $pickSet = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')
                ->findPickSet($id, $sort);
        } else {
            $pickSet = $this
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
        $game = $this->getRepository('SofaChamps\Bundle\BowlPickemBundle\Entity\Game')
            ->find($gameId);

        if (!$game) {
            throw new NotFoundHttpException(sprintf('There was no game with id = %s', $gameId));
        }

        return $game;
    }

    protected function findPredictionSet($predictionSetId)
    {
        $predictionSet = $this
            ->getRepository('SofaChampsBowlPickemBundle:PredictionSet')
            ->find($predictionSetId);

        if (!$predictionSet) {
            throw new NotFoundHttpException(sprintf('There was no prediction set with id = %s', $predictionSetId));
        }

        return $predictionSet;
    }

    protected function addUserToLeague(League $league, User $user)
    {
        $em = $this->getEntityManager();
        $user->addLeague($league);

        $em->persist($league);
        $em->persist($user);
    }

    protected function picksLocked(Season $season)
    {
        return $this->get('sofachamps.bp.picks_locked_manager')->arePickLocked($season);
    }

    protected function getLeagueManager()
    {
        return $this->get('sofachamps.bp.league_manager');
    }

    protected function getUserSorter()
    {
        return $this->get('sofachamps.bp.user_sorter');
    }

    protected function getSeasonManager()
    {
        return $this->get('sofachamps.bp.season_manager');
    }

    protected function getPicksetManager()
    {
        return $this->container->get('sofachamps.bp.pickset_manager');
    }

    protected function getCurrentSeason()
    {
        return $this->getSeasonManager()->getCurrentSeason();
    }

    protected function writeLeagueJoinCookie(League $league)
    {
        $time = time() + 72000;
        $response = $this->getResponse();
        //$this->setCookie($response, 'bp_league_join', $league->getId());
        setcookie('bp_league_join', $league->getId(), $time, '/');
        //die('writeLeagueJoinCookie');
        //$response->sendHeaders();
    }

    protected function getLeagueJoinCookie()
    {
        $request = $this->getRequest()->cookies->get('bp_league_join');
    }

    protected function deleteLeagueJoinCookie()
    {
        $response = $this->getResponse();
        $response->headers->clearCookie('bp_league_join');
        $response->sendHeaders();
    }
}
