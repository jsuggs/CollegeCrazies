<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BracketRepository extends EntityRepository
{
    public function getPopulatedBracked(Bracket $bracket)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'r', 'rg', 'ht', 'at', 'htt', 'att')
            ->innerJoin('b.regions', 'r')
            ->leftJoin('r.games', 'rg')
            ->leftJoin('rg.homeTeam', 'ht')
            ->leftJoin('rg.awayTeam', 'at')
            ->leftJoin('ht.team', 'htt')
            ->leftJoin('at.team', 'att')
            ->where('b.season = :season')
            ->setParameter('season', $bracket->getSeason())
            ->getQuery()
            ->getSingleResult();
    }
}
