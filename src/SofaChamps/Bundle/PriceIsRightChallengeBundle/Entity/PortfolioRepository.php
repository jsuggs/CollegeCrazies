<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;

class PortfolioRepository extends EntityRepository
{
    public function getUserPortfolio(User $user, $season)
    {
        try {
            return $this->createQueryBuilder('p')
                ->where('p.bracket = :season')
                ->setParameter('season', $season)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return new Portfolio($user);
        }
    }
}
