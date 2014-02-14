<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;

class GameRepository extends EntityRepository
{
    public function findUsersGamesForBracket(User $user, $bracket)
    {
        return $this->createQueryBuilder('g')
            ->where('g.bracket = :bracket')
            ->setParameter('bracket', $bracket)
            ->getQuery()
            ->getResult();
    }
}
