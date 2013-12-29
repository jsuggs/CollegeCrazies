<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\EntityRepository;

class NCAAFConferenceMemberRepository extends EntityRepository
{
    public function findConferenceMembers(Conference $conference, $season)
    {
        return $this->createQueryBuilder('m')
            ->select('m, c, t, d')
            ->leftJoin('m.conference', 'c')
            ->leftJoin('m.team', 't')
            ->leftJoin('m.conferenceDivision', 'd')
            ->where('m.conference = :conference')
            ->andWhere('m.season = :season')
            ->setParameter('season', $season)
            ->setParameter('conference', $conference)
            ->getQuery()
            ->getResult();
    }
}
