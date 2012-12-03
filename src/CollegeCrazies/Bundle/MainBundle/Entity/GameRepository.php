<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.gameDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
