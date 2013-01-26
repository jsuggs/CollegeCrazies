<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PickRepository extends EntityRepository
{
    public function findPicksForYearOrderedByScore($year)
    {
        return $this->createQueryBuilder('p')
            ->where('p.year = :year')
            ->orderBy('p.totalPoints', 'DESC')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }
}
