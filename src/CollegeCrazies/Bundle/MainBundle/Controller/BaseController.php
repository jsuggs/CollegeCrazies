<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BaseController extends Controller
{
    protected function findGame($gameId)
    {
        $game = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')
            ->find($gameId);

        if (!$game) {
            throw new \NotFoundHttpException(sprintf('There was no game with id = %s', $gameId));
        }

        return $game;
    }

    protected function canUserViewPickSet(User $user, PickSet $pickSet)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($pickSet->getUser() == $user) {
            return true;
        }

        foreach ($pickSet->getLeagues() as $league) {
            //if ($league->picksLocked() || $league->userIsCommissioner($user)) {
            if ($league->picksLocked()) {
                return true;
            }
        }

        return false;
    }
}
