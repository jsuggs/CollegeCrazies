<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;

class GameRepository extends EntityRepository
{
    public function findUsersGamesForBracket(User $user, $bracket)
    {
        $qb = $this->createQueryBuilder('g');
        return $qb
            ->select('g')
            ->innerJoin('g.portfolios', 'p')
            ->innerJoin('g.config', 'c')
            ->innerJoin('g.bracket', 'b')
            ->where('g.bracket = :bracket')
            ->andWhere('EXISTS(
                SELECT pi.id
                FROM SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio pi
                WHERE p.user = :user
                AND pi.id = p.id
            )')
            ->setParameter('bracket', $bracket)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
