<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LeagueRepository extends EntityRepository
{
    public function findAllPublic()
    {
        return $this->findBy(array(
            'public' => true,
        ));
    }

    public function getUsersAndPoints(League $league)
    {
        return $this->getEntityManager()->createQuery('SELECT u, p, l, pk, pg from CollegeCraziesMainBundle:User u
            JOIN u.pickSets p
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = :leagueId
            ORDER BY pg.id')
            ->setParameter('leagueId', $league->getId())
            ->getResult();
    }
}
