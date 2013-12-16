<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractConference;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A ncaa football conference
 *
 * @ORM\Entity
 */
class NCAAFConference extends Conference
{
    /**
     * @ORM\OneToMany(targetEntity="NCAAFConferenceMember", mappedBy="conference")
     */
    protected $conferenceMemberships;
}
