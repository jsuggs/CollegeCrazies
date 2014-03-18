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

    public function getPopulatedPortfolio(Portfolio $portfolio)
    {
        return $this->createQueryBuilder('p')
            ->select('p, g, b, bt, t, pt')
            ->innerJoin('p.game', 'g')
            ->innerJoin('g.bracket', 'b')
            ->innerJoin('b.teams', 'bt')
            ->innerJoin('bt.team', 't')
            ->leftJoin('p.teams', 'pt')
            ->where('p.id = :id')
            ->setParameter('id', $portfolio->getId())
            ->getQuery()
            ->getSingleResult();
    }
}
