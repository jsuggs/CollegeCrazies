<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;

class PortfolioRepository extends EntityRepository
{
    public function getUserPortfolio(User $user, $season)
    {
        return $this->createQueryBuilder('p')
            ->where('p.bracket = :season')
            ->setParameter('season', $season)
            ->getQuery()
            ->getSingleResult();
    }
}
