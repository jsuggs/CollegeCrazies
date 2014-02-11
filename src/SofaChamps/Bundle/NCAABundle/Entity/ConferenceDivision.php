<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractConferenceDivision;

/**
 * A ncaa conference division
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ncaa_conference_divisions"
 * )
 */
class ConferenceDivision extends AbstractConferenceDivision
{
    /**
     * @ORM\ManyToOne(targetEntity="Conference", inversedBy="divisions")
     * @ORM\JoinColumn(name="conference", referencedColumnName="abbr")
     */
    protected $conference;

    /**
     * @ORM\OneToMany(targetEntity="NCAAFConferenceMember", mappedBy="conferenceDivision")
     */
    protected $conferenceMemberships;
}
