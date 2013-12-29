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
 *      name="ncaaf_conference_members"
 * )
 */
class NCAAFConferenceMember extends AbstractConferenceMember
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Conference", inversedBy="conferenceMemberships")
     * @ORM\JoinColumn(name="conference", referencedColumnName="abbr")
     */
    protected $conference;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="conferenceMemberships")
     * @ORM\JoinColumn(name="team", referencedColumnName="id")
     */
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceDivision", inversedBy="conferenceMemberships")
     * @ORM\JoinColumn(name="division", referencedColumnName="abbr")
     */
    protected $conferenceDivision;
}
