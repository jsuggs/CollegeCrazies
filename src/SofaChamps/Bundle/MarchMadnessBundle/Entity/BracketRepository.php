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
            ->innerJoin('r.games', 'rg')
            ->innerJoin('rg.homeTeam', 'ht')
            ->innerJoin('rg.awayTeam', 'at')
            ->innerJoin('ht.team', 'htt')
            ->innerJoin('at.team', 'att')
            ->where('b.season = :season')
            ->setParameter('season', $bracket->getSeason())
            ->getQuery()
            ->getSingleResult();
    }
}
