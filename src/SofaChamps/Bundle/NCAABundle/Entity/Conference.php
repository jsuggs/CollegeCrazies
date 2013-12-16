<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractConference;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A ncaa conference
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ncaa_conferences"
 * )
 */
class Conference extends AbstractConference
{
    /**
     * @ORM\OneToMany(targetEntity="ConferenceMember", mappedBy="conference")
     */
    protected $conferenceMemberships;
}
