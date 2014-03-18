<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    public function findByIds(array $teamIds)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id IN (:teamIds)')
            ->setParameter('teamIds', $teamIds)
            ->getQuery()
            ->getResult();
    }
}
