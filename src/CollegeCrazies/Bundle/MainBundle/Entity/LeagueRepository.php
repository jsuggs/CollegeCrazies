<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LeagueRepository extends EntityRepository
{
    public function findAllPublic()
    {
        return $this->findBy(array(
            'public' => true,
        ));
    }
}
