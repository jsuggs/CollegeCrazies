<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractConferenceMember;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A ncaa conference
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ncaa_conference_members"
 * )
 */
class ConferenceMember extends AbstractConferenceMember
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\NCAABundle\Entity\Conference", inversedBy="conferenceMemberships")
     * @ORM\JoinColumn(name="conference", referencedColumnName="abbr")
     */
    protected $conference;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\NCAABundle\Entity\NCAAFTeam", inversedBy="conferenceMemberships")
     * @ORM\JoinColumn(name="team", referencedColumnName="id")
     */
    protected $team;
}
