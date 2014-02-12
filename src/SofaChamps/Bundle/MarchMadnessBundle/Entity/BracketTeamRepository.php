<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BracketTeamRepository extends EntityRepository
{
    public function getBracketTeamsByTeamIds($season, array $teamIds)
    {
        return $this->createQueryBuilder('b')
            ->where('b.bracket = :season')
            ->andWhere('b.team IN (:teamIds)')
            ->setParameter('season', $season)
            ->setParameter('teamIds', $teamIds)
            ->getQuery()
            ->getResult();
    }
}
