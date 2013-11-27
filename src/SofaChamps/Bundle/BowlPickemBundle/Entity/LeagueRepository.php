<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\EntityRepository;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\CoreBundle\Entity\User;

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

    public function removeUser(League $league, User $user)
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->executeUpdate('DELETE FROM pickset_leagues WHERE league_id = ? AND pickset_id IN (SELECT id FROM picksets WHERE user_id = ?)', array($league->getId(), $user->getId()));
        $conn->executeUpdate('DELETE FROM user_league WHERE league_id = ? AND user_id = ?', array($league->getId(), $user->getId()));
    }
}
