<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LeagueRepository extends EntityRepository
{
    public function findAllPublic()
    {
        return $this->createQueryBuilder('l')
            ->where('l.password IS null OR length(trim(l.password)) = 0')
            ->getQuery()
            ->getResult();
    }

    public function getUsersAndPoints(League $league)
    {
        return $this->getEntityManager()->createQuery('SELECT u, p, l, pk, pg from SofaChampsCoreBundle:User u
            JOIN u.pickSets p
            JOIN u.leagues l
            JOIN p.picks pk
            JOIN pk.game pg
            WHERE l.id = :leagueId')
            ->setParameter('leagueId', $league->getId())
            ->getResult();
    }

    public function getNumberOfGames()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(g.id) FROM SofaChampsBowlPickemBundle:Game g')
            ->getSingleScalarResult();
    }
}
